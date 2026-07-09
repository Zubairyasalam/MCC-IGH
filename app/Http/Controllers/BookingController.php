<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingNotification;
use App\Mail\SupportMail;

class BookingController extends Controller
{
    public function showBookingForm()
    {
        return view('booking');
    }

    public function storeBooking(Request $request)
    {
        // 1. Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'nationality' => 'required|string',
            'user_type' => 'required|string',
            'stream' => 'nullable|string',
            'level' => 'nullable|string',
            'department' => 'nullable|string',
            'primary_guest_name' => 'nullable|string',
            'no_of_persons' => 'required|integer|min:1',
            'passport_number' => $request->nationality === 'Non-Indian' ? 'required|string' : 'nullable|string',
            'visa_number' => $request->nationality === 'Non-Indian' ? 'required|string' : 'nullable|string',
            'gst_id' => 'nullable|string|max:50',
            'room_name' => 'required|string',
            'clock_in' => 'required|date',
            'clock_out' => 'required|date|after:clock_in',
            'department_other' => 'nullable|string',
            'referral_attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // Max 5MB
            'booking_reason' => 'required|string',
        ]);

        if ($request->department === 'Other' && $request->filled('department_other')) {
            $validated['department'] = $request->department_other;
        }

        // Dynamic capacity check
        $maxCapacity = 4;
        $normalizedRoom = strtolower($validated['room_name']);
        if (str_contains($normalizedRoom, 'conference-hall') || str_contains($normalizedRoom, 'conference-room')) {
            $maxCapacity = 60;
        } elseif (str_contains($normalizedRoom, 'glass-room')) {
            $maxCapacity = 20;
        } elseif (str_contains($normalizedRoom, 'suite-room')) {
            $maxCapacity = 4;
        } elseif (str_contains($normalizedRoom, 'advance')) {
            $maxCapacity = 4;
        } elseif (str_contains($normalizedRoom, 'standard')) {
            $maxCapacity = 2;
        }

        if ($validated['no_of_persons'] > $maxCapacity) {
            return back()->withInput()->with('error', "Number of persons exceeds the maximum capacity of {$maxCapacity} for this room.");
        }

        $clockIn = \Carbon\Carbon::parse($validated['clock_in']);
        $clockOut = \Carbon\Carbon::parse($validated['clock_out']);
        
        // Calculate duration in hours
        $durationHours = $clockIn->diffInHours($clockOut);
        if ($durationHours == 0) $durationHours = 1;
        
        $basePrice = 0;
        $roomName = $validated['room_name'];
        
        // Dynamic Pricing Logic based on Category
        if (str_contains(strtolower($roomName), 'standard')) {
            // Standard Rooms: ₹1400 per 12-hour block (or fraction)
            $twelveHourBlocks = ceil($durationHours / 12);
            $basePrice = $twelveHourBlocks * 1400;
        } elseif (is_numeric($roomName) || (is_numeric(substr($roomName, 0, 1)) && strlen($roomName) <= 4)) {
            // Advance Rooms (Numbered 101, 201 etc): ₹2500 per 24-hour day
            $days = ceil($durationHours / 24);
            $basePrice = $days * 2500;
        } elseif (in_array(strtolower($roomName), ['conference-hall', 'conference-room', 'glass-room', 'suite-room'])) {
            // Special Facility Rooms: ₹500 per hour (Minimum 4 hours = ₹2000)
            $billableHours = max(4, $durationHours);
            $basePrice = $billableHours * 500;
        } else {
            // Default Fallback (Previous logic)
            $basePrice = $durationHours > 4 ? 5000 : 2000;
        }
        
        // Apply Dynamic GST Rate from Settings
        $gstRate = \App\Models\Setting::where('key', 'gst_rate')->value('value') ?? 5;
        $totalPrice = $basePrice * (1 + ($gstRate / 100));

        // Double booking check
        $exists = Booking::where('room_name', $validated['room_name'])
            ->where('booking_date', $clockIn->toDateString())
            ->where('approval_status', '!=', 'Rejected')
            ->where(function ($query) use ($clockIn, $clockOut) {
                $query->where(function ($q) use ($clockIn, $clockOut) {
                    $q->where('start_time', '<', $clockOut->toTimeString())
                        ->where('end_time', '>', $clockIn->toTimeString());
                });
            })->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Selected workspace is already booked for this time slot.');
        }

        // Handle File Upload
        $attachmentPath = null;
        if ($request->hasFile('referral_attachment')) {
            $file = $request->file('referral_attachment');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $attachmentPath = $file->storeAs('referrals', $fileName, 'public');
        }

        // 2. Create the booking locally
        $booking = Booking::create(array_merge($validated, [
            'booking_date' => $clockIn->toDateString(),
            'start_time' => $clockIn->toTimeString(),
            'end_time' => $clockOut->toTimeString(),
            'total_price' => $totalPrice,
            'payment_status' => 'Pending',
            'approval_status' => 'Pending',
            'referral_attachment' => $attachmentPath
        ]));

        // Trigger Webhook
        app(\App\Services\WebhookService::class)->trigger('booking.created', $booking);

        // 3. Send notification email to the Principal
        try {
            // Get dynamic settings
            $principalEmail = \App\Models\Setting::where('key', 'principal_email')->value('value') ?? 'prasathragul75@gmail.com';
            $senderEmail    = \App\Models\Setting::where('key', 'sender_email')->value('value')    ?? 'prasathragul75@gmail.com';
            $mailPassword   = \App\Models\Setting::where('key', 'mail_password')->value('value')   ?? 'wnzt bweh qwvk gtbu';
            $mailHost       = \App\Models\Setting::where('key', 'mail_host')->value('value')       ?? 'smtp.gmail.com';
            $mailPort       = \App\Models\Setting::where('key', 'mail_port')->value('value')       ?? '587';
            $mailEncryption = \App\Models\Setting::where('key', 'mail_encryption')->value('value') ?? 'tls';
            $mailMailer     = \App\Models\Setting::where('key', 'mail_mailer')->value('value')     ?? 'smtp';

            // Override config at runtime
            config([
                'mail.default' => $mailMailer,
                'mail.mailers.smtp.host' => $mailHost,
                'mail.mailers.smtp.port' => $mailPort,
                'mail.mailers.smtp.encryption' => $mailEncryption,
                'mail.mailers.smtp.username' => $senderEmail,
                'mail.mailers.smtp.password' => $mailPassword,
                'mail.from.address' => 'noreply@mccigh.com',
                'mail.from.name' => 'MCC IGH System'
            ]);

            \Illuminate\Support\Facades\Mail::purge('smtp');

            Mail::to($principalEmail)->send(new BookingNotification($booking));
            Log::info('Booking notification sent successfully for ID: ' . $booking->id);

            // Send WhatsApp Notification to the Principal
            try {
                app(\App\Services\WhatsAppService::class)->sendBookingNotification($booking);
            } catch (\Exception $e) {
                Log::error('Failed to send WhatsApp notification for ID ' . $booking->id . ': ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            Log::error('Failed to send booking notification for ID ' . $booking->id . ': ' . $e->getMessage());
        }

        // 4. Redirect directly to the success page
        return redirect()->route('checkout.success', ['id' => $booking->id])->with('success', 'Booking submitted. Your request has been sent for approval.');
    }

    public function sendSupportMail(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        try {
            // Get dynamic settings (same as storeBooking)
            $supportEmail   = 'prasathragul75@gmail.com'; // Hardcoded as per request
            $senderEmail    = \App\Models\Setting::where('key', 'sender_email')->value('value')    ?? 'prasathragul75@gmail.com';
            $mailPassword   = \App\Models\Setting::where('key', 'mail_password')->value('value')   ?? 'wnzt bweh qwvk gtbu';
            $mailHost       = \App\Models\Setting::where('key', 'mail_host')->value('value')       ?? 'smtp.gmail.com';
            $mailPort       = \App\Models\Setting::where('key', 'mail_port')->value('value')       ?? '587';
            $mailEncryption = \App\Models\Setting::where('key', 'mail_encryption')->value('value') ?? 'tls';
            $mailMailer     = \App\Models\Setting::where('key', 'mail_mailer')->value('value')     ?? 'smtp';

            config([
                'mail.default' => $mailMailer,
                'mail.mailers.smtp.host' => $mailHost,
                'mail.mailers.smtp.port' => $mailPort,
                'mail.mailers.smtp.encryption' => $mailEncryption,
                'mail.mailers.smtp.username' => $senderEmail,
                'mail.mailers.smtp.password' => $mailPassword,
                'mail.from.address' => $senderEmail,
                'mail.from.name' => 'MCC IGH Support System'
            ]);

            \Illuminate\Support\Facades\Mail::purge('smtp');

            Mail::to($supportEmail)->send(new SupportMail($validated));
            Log::info('Support email sent to ' . $supportEmail . ' from ' . $validated['email']);

            return response()->json(['success' => true, 'message' => 'Message delivered successfully to the Support Desk!']);
        } catch (\Exception $e) {
            Log::error('Support Request Failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Mail Server Failure: ' . $e->getMessage()], 500);
        }
    }

    public function downloadReceipt($id)
    {
        $booking = Booking::findOrFail($id);
        $primaryColor = \App\Models\Setting::where('key', 'primary_color')->value('value') ?? '#7f1d1d';
        
        // Set paper to A4
        $pdf = \Pdf::loadView('emails.receipt_pdf', compact('booking', 'primaryColor'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download('MCC_Receipt_#'.str_pad($booking->id, 8, '0', STR_PAD_LEFT).'.pdf');
    }
}
