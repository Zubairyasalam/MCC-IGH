<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\PaymentLink;
use App\Services\PayUService;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController;
use App\Mail\BookingNotification;
use App\Mail\PaymentLinkMail;
use App\Mail\PaymentSuccessMail;
use Illuminate\Support\Facades\Mail;

class TestFullFlow extends Command
{
    protected $signature = 'test:full-flow';
    protected $description = 'Executes end-to-end live testing of MCC-IGH booking, approval, mail, payment, and PDF receipt workflows.';

    public function handle()
    {
        $this->info("=================================================");
        $this->info("   MCC-IGH LIVE FULL-FLOW E2E TEST SUITE        ");
        $this->info("=================================================\n");

        $passed = 0;
        $failed = 0;

        // -------------------------------------------------------------
        // TEST CASE 1: Resident Student Workflow (Warden -> Principal -> Admin -> Payment -> Success Mail)
        // -------------------------------------------------------------
        $this->warn("--- TEST 1: Resident Student Full Flow ---");
        try {
            $clockIn = now()->addDays(2)->setHour(10)->setMinute(0);
            $clockOut = now()->addDays(2)->setHour(14)->setMinute(0);

            $booking = Booking::create([
                'name' => 'Test Resident Student',
                'email' => 'student.test@mcc.edu.in',
                'phone' => '+91 9876543210',
                'nationality' => 'Indian',
                'user_type' => 'Student',
                'residence_status' => 'residence',
                'stream' => 'Shift I',
                'level' => 'Undergraduate',
                'department' => 'Computer Science',
                'primary_guest_name' => 'Self',
                'no_of_persons' => 1,
                'room_name' => 'standard-1',
                'booking_date' => $clockIn->toDateString(),
                'start_time' => $clockIn->toTimeString(),
                'end_time' => $clockOut->toTimeString(),
                'total_price' => 1470.00,
                'payment_status' => 'Pending',
                'approval_status' => 'Pending Warden Approval',
                'booking_reason' => 'Academic Conference',
            ]);

            $this->info("✔ [1.1] Booking Created (ID: {$booking->id}) with Initial Status: {$booking->approval_status}");

            // Verify BookingNotification Mailable compilation
            $mailable = new BookingNotification($booking);
            $mailable->assertSeeInHtml('Test Resident Student');
            $this->info("✔ [1.2] BookingNotification mailable compiled cleanly");

            // Simulate Warden Approval
            $bookingController = new BookingController();
            $bookingController->wardenApprove($booking->id);
            $booking->refresh();

            if ($booking->approval_status !== 'Pending Principal Approval') {
                throw new \Exception("Expected status 'Pending Principal Approval', got '{$booking->approval_status}'");
            }
            $this->info("✔ [1.3] Warden Approval completed -> Status: {$booking->approval_status}");

            // Simulate Principal Approval
            $adminController = new AdminController();
            $adminController->principalApprove($booking->id);
            $booking->refresh();

            if ($booking->approval_status !== 'Approved by Principal') {
                throw new \Exception("Expected status 'Approved by Principal', got '{$booking->approval_status}'");
            }
            $this->info("✔ [1.4] Principal Approval completed -> Status: {$booking->approval_status}");

            // Simulate Admin Approval & Payment Link Generation
            $adminController->adminApprove($booking->id);
            $booking->refresh();

            if ($booking->approval_status !== 'Approved') {
                throw new \Exception("Expected status 'Approved', got '{$booking->approval_status}'");
            }

            $link = PaymentLink::where('booking_id', $booking->id)->latest()->first();
            if (!$link || !$link->isValid()) {
                throw new \Exception("Payment link not generated or invalid");
            }
            $this->info("✔ [1.5] Admin Approval completed -> Status: Approved | Payment Token: {$link->token}");

            // Verify PaymentLinkMail mailable
            $linkMail = new PaymentLinkMail($booking, $link);
            $linkMail->assertSeeInHtml($link->token);
            $this->info("✔ [1.6] PaymentLinkMail mailable compiled cleanly with token URL");

            // Simulate PayU Transaction Payment Processing
            $txnid = 'TXN_TEST_' . strtoupper(uniqid());
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'txnid' => $txnid,
                'amount' => $booking->total_price,
                'status' => 'initiated',
            ]);

            $payuService = app(PayUService::class);
            $payuKey = env('PAYU_MERCHANT_KEY', 'uAV4rQ');
            $payuSalt = env('PAYU_MERCHANT_SALT', '7GOgumloEcYhCBLv2qdvMZBiREI3fV8j');

            // Generate reverse hash as PayU would send on callback
            // Hash format: sha512(salt|status|udf10|udf9|udf8|udf7|udf6|udf5|udf4|udf3|udf2|udf1|email|firstname|productinfo|amount|txnid|key)
            $hashString = sprintf(
                '%s|%s|||||||||%s|%s|%s|%s|%s|%.2f|%s|%s',
                $payuSalt,
                'success',
                $link->token, // udf2
                $booking->id, // udf1
                $booking->email,
                $booking->name,
                'Booking #' . $booking->id . ' - ' . $booking->room_name,
                $booking->total_price,
                $txnid,
                $payuKey
            );
            $callbackHash = hash('sha512', $hashString);

            // Execute Callback directly through PaymentController logic
            $mockRequest = new \Illuminate\Http\Request([
                'txnid' => $txnid,
                'status' => 'success',
                'mihpayid' => '123456789',
                'mode' => 'UPI',
                'hash' => $callbackHash,
                'udf1' => $booking->id,
                'udf2' => $link->token,
                'email' => $booking->email,
                'firstname' => $booking->name,
                'productinfo' => 'Booking #' . $booking->id . ' - ' . $booking->room_name,
                'amount' => number_format($booking->total_price, 2, '.', ''),
            ]);

            $paymentController = new PaymentController($payuService);
            $response = $paymentController->callback($mockRequest);

            $booking->refresh();
            $payment->refresh();
            $link->refresh();

            if ($booking->payment_status !== 'Paid') {
                throw new \Exception("Booking payment status expected 'Paid', got '{$booking->payment_status}'");
            }
            if ($payment->status !== 'success') {
                throw new \Exception("Payment record status expected 'success', got '{$payment->status}'");
            }
            if (!$link->is_used) {
                throw new \Exception("Payment link is_used flag should be true");
            }
            $this->info("✔ [1.7] PayU Callback verified -> Booking Payment Status: Paid | Link Inactivated");

            // Test PaymentSuccessMail & PDF Generation
            $successMail = new PaymentSuccessMail($booking, $payment);
            $builtMessage = $successMail->build();
            $this->info("✔ [1.8] PaymentSuccessMail & PDF Invoice rendered successfully");

            $passed++;
        } catch (\Throwable $e) {
            $this->error("❌ TEST 1 FAILED: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            $failed++;
        }

        // -------------------------------------------------------------
        // TEST CASE 2: Day Scholar Student Workflow (HOD -> Principal)
        // -------------------------------------------------------------
        $this->warn("\n--- TEST 2: Day Scholar Student Flow ---");
        try {
            $clockIn = now()->addDays(3)->setHour(11)->setMinute(0);
            $clockOut = now()->addDays(3)->setHour(15)->setMinute(0);

            $booking = Booking::create([
                'name' => 'Test DayScholar Student',
                'email' => 'dayscholar.test@mcc.edu.in',
                'phone' => '+91 9876543211',
                'nationality' => 'Indian',
                'user_type' => 'Student',
                'residence_status' => 'non residence',
                'stream' => 'Shift II',
                'level' => 'Postgraduate',
                'department' => 'English',
                'primary_guest_name' => 'Self',
                'no_of_persons' => 1,
                'room_name' => 'standard-2',
                'booking_date' => $clockIn->toDateString(),
                'start_time' => $clockIn->toTimeString(),
                'end_time' => $clockOut->toTimeString(),
                'total_price' => 1470.00,
                'payment_status' => 'Pending',
                'approval_status' => 'Pending HOD Approval',
                'booking_reason' => 'Research Seminar',
            ]);

            $this->info("✔ [2.1] Booking Created (ID: {$booking->id}) with Initial Status: {$booking->approval_status}");

            $bookingController = new BookingController();
            $bookingController->hodApprove($booking->id);
            $booking->refresh();

            if ($booking->approval_status !== 'Pending Principal Approval') {
                throw new \Exception("Expected status 'Pending Principal Approval', got '{$booking->approval_status}'");
            }
            $this->info("✔ [2.2] HOD Approval completed -> Status: {$booking->approval_status}");

            $passed++;
        } catch (\Throwable $e) {
            $this->error("❌ TEST 2 FAILED: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            $failed++;
        }

        // -------------------------------------------------------------
        // TEST CASE 3: Non-Indian Guest Workflow & PDF Receipt Download
        // -------------------------------------------------------------
        $this->warn("\n--- TEST 3: Non-Indian Guest & PDF Receipt Download ---");
        try {
            $clockIn = now()->addDays(4)->setHour(14)->setMinute(0);
            $clockOut = now()->addDays(5)->setHour(14)->setMinute(0);

            $booking = Booking::create([
                'name' => 'Global Guest John',
                'email' => 'john.global@example.com',
                'phone' => '+1 4085550199',
                'nationality' => 'Non-Indian',
                'user_type' => 'General Guest',
                'passport_number' => 'PASS_US_998877',
                'visa_number' => 'VISA_IN_112233',
                'primary_guest_name' => 'Global Guest John',
                'no_of_persons' => 2,
                'room_name' => '101',
                'booking_date' => $clockIn->toDateString(),
                'start_time' => $clockIn->toTimeString(),
                'end_time' => $clockOut->toTimeString(),
                'total_price' => 2625.00,
                'payment_status' => 'Paid',
                'approval_status' => 'Approved',
                'booking_reason' => 'Academic Guest Speaker',
            ]);

            $this->info("✔ [3.1] Non-Indian Booking Created (ID: {$booking->id}) with Passport: {$booking->passport_number} & Visa: {$booking->visa_number}");

            // Render PDF receipt directly
            $primaryColor = \App\Models\Setting::where('key', 'primary_color')->value('value') ?? '#7f1d1d';
            $pdf = \Pdf::loadView('emails.receipt_pdf', compact('booking', 'primaryColor'))
                      ->setPaper('a4', 'portrait');
            $pdfOutput = $pdf->output();

            if (empty($pdfOutput) || strpos($pdfOutput, '%PDF-') !== 0) {
                throw new \Exception("PDF receipt generation returned invalid or empty stream");
            }
            $this->info("✔ [3.2] PDF Receipt compilation verified (PDF Size: " . strlen($pdfOutput) . " bytes)");

            $passed++;
        } catch (\Throwable $e) {
            $this->error("❌ TEST 3 FAILED: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            $failed++;
        }

        // Cleanup test entries safely
        Booking::whereIn('email', ['student.test@mcc.edu.in', 'dayscholar.test@mcc.edu.in', 'john.global@example.com'])->delete();

        $this->info("\n=================================================");
        $this->info("   TEST RESULTS: Passed: {$passed} | Failed: {$failed}");
        $this->info("=================================================");

        return $failed === 0 ? 0 : 1;
    }
}
