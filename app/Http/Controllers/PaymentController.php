<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\PaymentLink;
use App\Services\PayUService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentSuccessMail;

class PaymentController extends Controller
{
    protected PayUService $payu;

    public function __construct(PayUService $payu)
    {
        $this->payu = $payu;
    }

    /**
     * Show payment summary page to guest
     */
    public function show($token)
    {
        $link = PaymentLink::with('booking')->where('token', $token)->firstOrFail();

        if (!$link->isValid()) {
            return view('payment.status', [
                'status' => 'expired',
                'message' => 'This payment link has expired or has already been used.'
            ]);
        }

        return view('payment.show', compact('link'));
    }

    /**
     * Initiate PayU transaction
     */
    public function process($token)
    {
        $link = PaymentLink::with('booking')->where('token', $token)->firstOrFail();

        if (!$link->isValid()) {
            return back()->with('error', 'Invalid or expired payment link.');
        }

        $booking = $link->booking;
        $txnid = 'TXN_' . strtoupper(uniqid());

        // Create transaction intent
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'txnid' => $txnid,
            'amount' => $booking->total_price,
            'status' => 'initiated',
        ]);

        app(\App\Services\WebhookService::class)->trigger('payment.initiated', [
            'booking_id' => $booking->id,
            'txnid' => $txnid,
            'amount' => $booking->total_price,
            'payment_id' => $payment->id
        ]);

        $params = [
            'txnid' => $txnid,
            'amount' => number_format($booking->total_price, 2, '.', ''),
            'productinfo' => 'Booking #' . $booking->id . ' - ' . $booking->room_name,
            'firstname' => $booking->name,
            'email' => $booking->email,
            'phone' => $booking->phone,
            'surl' => route('payment.callback'),
            'furl' => route('payment.callback'),
            'udf1' => $booking->id,
            'udf2' => $token,
        ];

        $hash = $this->payu->generateHash($params);

        return view('payment.process', [
            'payu_url' => $this->payu->getUrl(),
            'params' => $params,
            'key' => $this->payu->getKey(),
            'hash' => $hash
        ]);
    }

    /**
     * Handle PayU callback (POST)
     */
    public function callback(Request $request)
    {
        Log::info('PayU Callback Received', $request->all());

        if (!$this->payu->verifyHash($request->all())) {
            Log::error('PayU Hash Verification Failed', $request->all());
            return view('payment.status', [
                'status' => 'failed',
                'message' => 'Security check failed. Transaction signature mismatch.'
            ]);
        }

        $txnid = $request->txnid;
        $status = $request->status; // success, failure
        $payment = Payment::where('txnid', $txnid)->firstOrFail();
        $booking = $payment->booking;
        $token = $request->udf2;

        DB::beginTransaction();
        try {
            $payment->update([
                'payu_id' => $request->mihpayid,
                'status' => $status === 'success' ? 'success' : 'failed',
                'payment_mode' => $request->mode,
                'error_message' => $request->error_Message,
                'raw_response' => $request->all(),
            ]);

            app(\App\Services\WebhookService::class)->trigger(
                $status === 'success' ? 'payment.successful' : 'payment.failed', 
                [
                    'booking_id' => $booking->id,
                    'txnid' => $txnid,
                    'payment_id' => $payment->id,
                    'payu_id' => $request->mihpayid,
                    'amount' => $payment->amount,
                    'status' => $status,
                    'error' => $request->error_Message
                ]
            );

            if ($status === 'success') {
                $booking->update([
                    'payment_status' => 'Paid',
                    'razorpay_payment_id' => "PAYU_" . $request->mihpayid, // Reusing existing column for now
                ]);

                // Invalidate link
                PaymentLink::where('token', $token)->update(['is_used' => true]);

                // Send Success Email to both Guest and Principal
                try {
                    // Apply dynamic mail config at runtime
                    $senderEmail    = \App\Models\Setting::where('key', 'sender_email')->value('value')    ?? 'prasathragul75@gmail.com';
                    $mailPassword   = \App\Models\Setting::where('key', 'mail_password')->value('value')   ?? 'wnzt bweh qwvk gtbu';
                    $mailHost       = \App\Models\Setting::where('key', 'mail_host')->value('value')       ?? 'smtp.gmail.com';
                    $mailPort       = \App\Models\Setting::where('key', 'mail_port')->value('value')       ?? '587';
                    $mailEncryption = \App\Models\Setting::where('key', 'mail_encryption')->value('value') ?? 'tls';
                    $mailMailer     = \App\Models\Setting::where('key', 'mail_mailer')->value('value')     ?? 'smtp';
                    $principalEmail = \App\Models\Setting::where('key', 'principal_email')->value('value') ?? 'prasathragul75@gmail.com';

                    config([
                        'mail.default' => $mailMailer,
                        'mail.mailers.smtp.host' => $mailHost,
                        'mail.mailers.smtp.port' => $mailPort,
                        'mail.mailers.smtp.encryption' => $mailEncryption,
                        'mail.mailers.smtp.username' => $senderEmail,
                        'mail.mailers.smtp.password' => $mailPassword,
                        'mail.from.address' => $senderEmail,
                        'mail.from.name' => 'MCC IGH Payment System'
                    ]);
                    \Illuminate\Support\Facades\Mail::purge('smtp');

                    // Notify Guest and Principal
                    Mail::to($booking->email)->cc($principalEmail)->send(new PaymentSuccessMail($booking, $payment));
                    
                    Log::info("Payment success invoice sent to " . $booking->email);
                } catch (\Exception $e) {
                    Log::error('Failed to send payment success mail: ' . $e->getMessage());
                }
            }

            DB::commit();

            return view('payment.status', [
                'status' => $status === 'success' ? 'success' : 'failed',
                'message' => $status === 'success' 
                    ? 'Payment successful! Your reservation is confirmed.' 
                    : 'Payment failed: ' . $request->error_Message,
                'booking' => $booking
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing PayU callback: ' . $e->getMessage());
            return view('payment.status', [
                'status' => 'error',
                'message' => 'An internal error occurred while processing your payment status.'
            ]);
        }
    }

    /**
     * Handle PayU Webhook (Server-to-Server POST)
     */
    public function webhook(Request $request)
    {
        Log::info('PayU Webhook Received', $request->all());

        if (!$this->payu->verifyHash($request->all())) {
            Log::error('PayU Webhook Hash Verification Failed');
            return response()->json(['status' => 'error', 'message' => 'Invalid hash'], 400);
        }

        $txnid = $request->txnid;
        $status = $request->status;
        $payment = Payment::where('txnid', $txnid)->first();

        if (!$payment || $payment->status === 'success') {
            return response()->json(['status' => 'ok', 'message' => 'Already processed or not found']);
        }

        DB::beginTransaction();
        try {
            $payment->update([
                'payu_id' => $request->mihpayid,
                'status' => $status === 'success' ? 'success' : 'failed',
                'payment_mode' => $request->mode,
                'error_message' => $request->error_Message,
                'raw_response' => $request->all(),
            ]);

            app(\App\Services\WebhookService::class)->trigger(
                $status === 'success' ? 'payment.successful' : 'payment.failed', 
                [
                    'booking_id' => $payment->booking_id,
                    'txnid' => $txnid,
                    'payment_id' => $payment->id,
                    'payu_id' => $request->mihpayid,
                    'amount' => $payment->amount,
                    'status' => $status,
                    'error' => $request->error_Message
                ]
            );

            if ($status === 'success') {
                $booking = $payment->booking;
                $booking->update(['payment_status' => 'Paid']);

                // Send Success Email to both Guest and Principal
                try {
                    // Apply dynamic mail config at runtime
                    $senderEmail    = \App\Models\Setting::where('key', 'sender_email')->value('value')    ?? 'prasathragul75@gmail.com';
                    $mailPassword   = \App\Models\Setting::where('key', 'mail_password')->value('value')   ?? 'wnzt bweh qwvk gtbu';
                    $mailHost       = \App\Models\Setting::where('key', 'mail_host')->value('value')       ?? 'smtp.gmail.com';
                    $mailPort       = \App\Models\Setting::where('key', 'mail_port')->value('value')       ?? '587';
                    $mailEncryption = \App\Models\Setting::where('key', 'mail_encryption')->value('value') ?? 'tls';
                    $mailMailer     = \App\Models\Setting::where('key', 'mail_mailer')->value('value')     ?? 'smtp';
                    $principalEmail = \App\Models\Setting::where('key', 'principal_email')->value('value') ?? 'prasathragul75@gmail.com';

                    config([
                        'mail.default' => $mailMailer,
                        'mail.mailers.smtp.host' => $mailHost,
                        'mail.mailers.smtp.port' => $mailPort,
                        'mail.mailers.smtp.encryption' => $mailEncryption,
                        'mail.mailers.smtp.username' => $senderEmail,
                        'mail.mailers.smtp.password' => $mailPassword,
                        'mail.from.address' => $senderEmail,
                        'mail.from.name' => 'MCC IGH Payment System'
                    ]);
                    \Illuminate\Support\Facades\Mail::purge('smtp');

                    // Notify Guest and Principal
                    Mail::to($booking->email)->cc($principalEmail)->send(new PaymentSuccessMail($booking, $payment));
                    
                    Log::info("Payment success invoice sent (webhook) to " . $booking->email);
                } catch (\Exception $e) {
                    Log::error('Failed to send payment success mail (webhook): ' . $e->getMessage());
                }
            }

            DB::commit();
            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Webhook error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }
}
