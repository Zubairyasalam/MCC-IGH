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
        try {
            // 1. Validate the incoming request
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => $request->nationality === 'Non-Indian' ? 'nullable|string|max:255' : 'required|string|max:255',
                'nationality' => 'required|string',
                'user_type' => 'required|string',
                'residence_status' => $request->user_type === 'Student' ? 'required|string|in:residence,non residence' : 'nullable|string',
                'stream' => 'nullable|string',
                'level' => 'nullable|string',
                'department' => 'nullable|string',
                'primary_guest_name' => 'nullable|string',
                'no_of_persons' => 'required|integer|min:1',
                'passport_number' => $request->nationality === 'Non-Indian' ? 'required|string' : 'nullable|string',
                'visa_number' => $request->nationality === 'Non-Indian' ? 'required|string' : 'nullable|string',
                'passport_attachment' => $request->nationality === 'Non-Indian' ? 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120' : 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
                'visa_attachment' => $request->nationality === 'Non-Indian' ? 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120' : 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
                'passport_visa_attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
                'gst_id' => 'nullable|string|max:50',
                'room_name' => 'required|string',
                'clock_in' => 'required|date',
                'clock_out' => 'required|date|after:clock_in',
                'department_other' => 'nullable|string',
                'referral_attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // Max 5MB
                'booking_reason' => 'required|string',
                'staff_type' => 'nullable|string|in:Teaching,Non-Teaching',
            ]);

            if ($request->user_type === 'Staff') {
                if ($request->staff_type === 'Teaching') {
                    $validated['level'] = 'Teaching Staff';
                } else {
                    $validated['department'] = 'Non-Teaching';
                    $validated['level'] = 'Non-Teaching Staff';
                }
            } elseif ($request->department === 'Other' && $request->filled('department_other')) {
                $validated['department'] = $request->department_other;
            }

            // Parse selected rooms (supports single room or multi-room comma-separated selection)
            $selectedRooms = array_filter(array_map('trim', explode(',', $validated['room_name'])));
            if (empty($selectedRooms)) {
                $selectedRooms = [$validated['room_name']];
            }

            // Dynamic combined capacity check for all selected rooms
            $maxCapacity = 0;
            foreach ($selectedRooms as $rName) {
                $normalizedRoom = strtolower($rName);
                if (str_contains($normalizedRoom, 'conference-hall') || str_contains($normalizedRoom, 'conference-room')) {
                    $maxCapacity += 60;
                } elseif (str_contains($normalizedRoom, 'glass-room')) {
                    $maxCapacity += 20;
                } elseif (str_contains($normalizedRoom, 'suite-room')) {
                    $maxCapacity += 4;
                } elseif (str_contains($normalizedRoom, 'advance') || is_numeric($rName) || (is_numeric(substr($rName, 0, 1)) && strlen($rName) <= 4)) {
                    $maxCapacity += 4;
                } elseif (str_contains($normalizedRoom, 'standard')) {
                    $maxCapacity += 2;
                } else {
                    $maxCapacity += 4;
                }
            }

            if ($validated['no_of_persons'] > $maxCapacity) {
                return back()->withInput()->with('error', "Number of persons exceeds the maximum total capacity of {$maxCapacity} for your selected room(s).");
            }

            $clockIn = \Carbon\Carbon::parse($validated['clock_in']);
            $clockOut = \Carbon\Carbon::parse($validated['clock_out']);
            
            if ($clockOut->lte($clockIn)) {
                return back()->withInput()->with('error', 'Check-Out date and time must be strictly after Check-In date and time.');
            }

            // Calculate duration in hours precisely
            $durationMinutes = $clockIn->diffInMinutes($clockOut);
            if ($durationMinutes < 15) {
                return back()->withInput()->with('error', 'Booking duration must be at least 15 minutes.');
            }
            $durationHours = $durationMinutes / 60.0;
            
            // Combined Base Pricing Logic for all selected rooms
            $basePrice = 0;
            foreach ($selectedRooms as $rName) {
                $normR = strtolower($rName);
                if (str_contains($normR, 'standard')) {
                    // Standard Rooms: ₹1400 per 12-hour block (or fraction)
                    $twelveHourBlocks = (int) ceil($durationHours / 12.0);
                    $basePrice += max(1, $twelveHourBlocks) * 1400;
                } elseif (is_numeric($rName) || (is_numeric(substr($rName, 0, 1)) && strlen($rName) <= 4) || str_contains($normR, 'advance')) {
                    // Advance Rooms (Numbered 101, 201 etc): ₹2500 per 24-hour day (or fraction)
                    $days = (int) ceil($durationHours / 24.0);
                    $basePrice += max(1, $days) * 2500;
                } elseif (in_array($normR, ['conference-hall', 'conference-room', 'glass-room', 'suite-room']) || str_contains($normR, 'conference') || str_contains($normR, 'glass') || str_contains($normR, 'suite')) {
                    // Special Facility Rooms: ₹500 per hour (Minimum 4 hours = ₹2000)
                    $billableHours = max(4, (int) ceil($durationHours));
                    $basePrice += $billableHours * 500;
                } else {
                    // Default Fallback
                    $basePrice += $durationHours > 4 ? 5000 : 2000;
                }
            }
            
            // Apply Dynamic GST Rate from Settings
            try {
                $gstRate = \App\Models\Setting::where('key', 'gst_rate')->value('value') ?? 5;
            } catch (\Throwable $e) {
                $gstRate = 5;
            }
            $totalPrice = $basePrice * (1 + ($gstRate / 100));

            // Double booking check for each selected room individually
            foreach ($selectedRooms as $singleRoom) {
                $exists = Booking::where('approval_status', '!=', 'Rejected')
                    ->where('booking_date', $clockIn->toDateString())
                    ->where(function ($query) use ($clockIn, $clockOut) {
                        $query->where(function ($q) use ($clockIn, $clockOut) {
                            $q->where('start_time', '<', $clockOut->toTimeString())
                                ->where('end_time', '>', $clockIn->toTimeString());
                        });
                    })
                    ->where(function ($query) use ($singleRoom) {
                        $query->where('room_name', $singleRoom)
                            ->orWhere('room_name', 'LIKE', '%' . $singleRoom . '%');
                    })
                    ->exists();

                if ($exists) {
                    return back()->withInput()->with('error', "{$singleRoom} is already booked for this selected time slot.");
                }
            }

            // Handle File Upload
            $attachmentPath = null;
            if ($request->hasFile('referral_attachment')) {
                $file = $request->file('referral_attachment');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $attachmentPath = $file->storeAs('referrals', $fileName, 'public');
            }

            $passportPath = null;
            if ($request->hasFile('passport_attachment')) {
                $file = $request->file('passport_attachment');
                $fileName = time() . '_passport_' . $file->getClientOriginalName();
                $passportPath = $file->storeAs('passport_visa', $fileName, 'public');
            }

            $visaPath = null;
            if ($request->hasFile('visa_attachment')) {
                $file = $request->file('visa_attachment');
                $fileName = time() . '_visa_' . $file->getClientOriginalName();
                $visaPath = $file->storeAs('passport_visa', $fileName, 'public');
            }

            $passportVisaPath = null;
            if ($request->hasFile('passport_visa_attachment')) {
                $file = $request->file('passport_visa_attachment');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $passportVisaPath = $file->storeAs('passport_visa', $fileName, 'public');
            } elseif ($passportPath || $visaPath) {
                $passportVisaPath = $passportPath ?? $visaPath;
            }

            // 2. Create the booking locally
            $userType = strtolower(trim($request->user_type ?? ''));
            $residenceStatus = strtolower(trim($request->residence_status ?? ''));

            $initialStatus = 'Pending';
            if ($userType === 'student') {
                if ($residenceStatus === 'residence' || $residenceStatus === 'resident') {
                    $initialStatus = 'Pending Warden Approval';
                } elseif (str_contains($residenceStatus, 'non') || $residenceStatus === 'dayscholar') {
                    $initialStatus = 'Pending HOD Approval';
                }
            }

            $booking = Booking::create(array_merge($validated, [
                'booking_date' => $clockIn->toDateString(),
                'start_time' => $clockIn->toTimeString(),
                'end_time' => $clockOut->toTimeString(),
                'clock_in' => $clockIn->toDateTimeString(),
                'clock_out' => $clockOut->toDateTimeString(),
                'total_price' => $totalPrice,
                'payment_status' => 'Pending',
                'approval_status' => $initialStatus,
                'referral_attachment' => $attachmentPath,
                'passport_attachment' => $passportPath,
                'visa_attachment' => $visaPath,
                'passport_visa_attachment' => $passportVisaPath
            ]));

            // Trigger Webhook safely
            app(\App\Services\WebhookService::class)->trigger('booking.created', $booking);

            // 3. Send notification email safely
            try {
                $this->setupMailConfig();

                $getSetting = function($key, $default) {
                    $val = \App\Models\Setting::where('key', $key)->value('value');
                    return (!is_null($val) && trim((string)$val) !== '') ? trim((string)$val) : $default;
                };

                $bookingUserType = strtolower(trim($booking->user_type ?? ''));
                $bookingResidenceStatus = strtolower(trim($booking->residence_status ?? ''));

                if ($bookingUserType === 'student' && ($bookingResidenceStatus === 'residence' || $bookingResidenceStatus === 'resident')) {
                    $wardenEmail = $getSetting('hall_warden_email', 'praveenrock2609@gmail.com');
                    $approveUrl = route('bookings.approve.warden', $booking->id);
                    $rejectUrl = route('bookings.reject.warden', $booking->id);
                    Mail::to($wardenEmail)->send(new BookingNotification($booking, $approveUrl, $rejectUrl));
                    Log::info("Booking notification sent to Hall Warden ({$wardenEmail}) for ID: " . $booking->id);
                } elseif ($bookingUserType === 'student' && (str_contains($bookingResidenceStatus, 'non') || $bookingResidenceStatus === 'dayscholar')) {
                    $hodEmail = $getSetting('hod_email', 'unfortunately2909@gmail.com');
                    $approveUrl = route('bookings.approve.hod', $booking->id);
                    $rejectUrl = route('bookings.reject.hod', $booking->id);
                    Mail::to($hodEmail)->send(new BookingNotification($booking, $approveUrl, $rejectUrl));
                    Log::info("Booking notification sent to HOD ({$hodEmail}) for ID: " . $booking->id);
                } else {
                    $principalEmails = \App\Models\Setting::getEmails('principal_email', 'prasathragul75@gmail.com');
                    Mail::to($principalEmails)->send(new BookingNotification($booking));
                    Log::info("Booking notification sent to Principal (" . implode(', ', $principalEmails) . ") for ID: " . $booking->id);
                }

                // Send WhatsApp Notification to the Principal safely
                try {
                    app(\App\Services\WhatsAppService::class)->sendBookingNotification($booking);
                } catch (\Throwable $e) {
                    Log::error('Failed to send WhatsApp notification for ID ' . $booking->id . ': ' . $e->getMessage());
                }
            } catch (\Throwable $e) {
                Log::error('Failed to send booking notification for ID ' . $booking->id . ': ' . $e->getMessage());
            }

            // 4. Redirect directly to the success page
            return redirect()->route('checkout.success', ['id' => $booking->id])->with('success', 'Booking submitted. Your request has been sent for approval.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('storeBooking Error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->withInput()->with('error', 'Booking request error: ' . $e->getMessage());
        }
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

    private function setupMailConfig()
    {
        try {
            $getSetting = function($key, $default) {
                $val = \App\Models\Setting::where('key', $key)->value('value');
                return (!is_null($val) && trim((string)$val) !== '') ? trim((string)$val) : $default;
            };

            $senderEmail    = $getSetting('sender_email', env('MAIL_USERNAME', 'prasathragul75@gmail.com'));
            $mailPassword   = $getSetting('mail_password', env('MAIL_PASSWORD', 'wnzt bweh qwvk gtbu'));
            $mailHost       = $getSetting('mail_host', env('MAIL_HOST', 'smtp.gmail.com'));
            $mailPort       = $getSetting('mail_port', env('MAIL_PORT', 587));
            $mailEncryption = $getSetting('mail_encryption', env('MAIL_ENCRYPTION', 'tls'));
            $mailMailer     = $getSetting('mail_mailer', env('MAIL_MAILER', 'smtp'));

            config([
                'mail.default' => $mailMailer,
                'mail.mailers.smtp.scheme' => null,
                'mail.mailers.smtp.host' => $mailHost,
                'mail.mailers.smtp.port' => (int)$mailPort,
                'mail.mailers.smtp.encryption' => $mailEncryption,
                'mail.mailers.smtp.username' => $senderEmail,
                'mail.mailers.smtp.password' => $mailPassword,
                'mail.from.address' => $senderEmail,
                'mail.from.name' => 'MCC IGH System'
            ]);

            \Illuminate\Support\Facades\Mail::purge('smtp');
        } catch (\Throwable $e) {
            Log::error('setupMailConfig failed: ' . $e->getMessage());
        }
    }

    public function hodApprove($id)
    {
        $booking = Booking::findOrFail($id);
        $status = $booking->approval_status;

        if ($status !== 'Pending HOD Approval' && $status !== 'Pending') {
            $statusDisplay = in_array($status, ['Approved', 'Approved by Principal', 'Principal Approved', 'Pending Principal Approval']) 
                ? 'Approved' 
                : ($status === 'Rejected' ? 'Rejected' : $status);

            return view('approval_status', [
                'actionTitle' => 'Approve Booking (HOD)',
                'booking' => $booking,
                'alreadyReviewed' => true,
                'statusDisplay' => $statusDisplay
            ]);
        }

        $booking->update(['approval_status' => 'Pending Principal Approval']);
        
        // Notify Principal
        try {
            $this->setupMailConfig();
            $principalEmails = \App\Models\Setting::getEmails('principal_email', 'prasathragul75@gmail.com');
            Mail::to($principalEmails)->send(new BookingNotification($booking));
            Log::info('Booking notification sent to Principal (' . implode(', ', $principalEmails) . ') after HOD approval for ID: ' . $booking->id);
        } catch (\Exception $e) {
            Log::error('Failed to send Principal notification after HOD approval: ' . $e->getMessage());
        }

        return view('approval_status', [
            'actionTitle' => 'Approve Booking (HOD)',
            'booking' => $booking,
            'alreadyReviewed' => false,
            'statusDisplay' => 'Approved by HOD',
            'success' => 'Booking approved by HOD. Sent to Principal for final approval.'
        ]);
    }

    public function wardenApprove($id)
    {
        $booking = Booking::findOrFail($id);
        $status = $booking->approval_status;

        if ($status !== 'Pending Warden Approval' && $status !== 'Pending') {
            $statusDisplay = in_array($status, ['Approved', 'Approved by Principal', 'Principal Approved', 'Pending Principal Approval']) 
                ? 'Approved' 
                : ($status === 'Rejected' ? 'Rejected' : $status);

            return view('approval_status', [
                'actionTitle' => 'Approve Booking (Warden)',
                'booking' => $booking,
                'alreadyReviewed' => true,
                'statusDisplay' => $statusDisplay
            ]);
        }

        $booking->update(['approval_status' => 'Pending Principal Approval']);
        
        // Notify Principal
        try {
            $this->setupMailConfig();
            $principalEmails = \App\Models\Setting::getEmails('principal_email', 'prasathragul75@gmail.com');
            Mail::to($principalEmails)->send(new BookingNotification($booking));
            Log::info('Booking notification sent to Principal (' . implode(', ', $principalEmails) . ') after Warden approval for ID: ' . $booking->id);
        } catch (\Exception $e) {
            Log::error('Failed to send Principal notification after Warden approval: ' . $e->getMessage());
        }

        return view('approval_status', [
            'actionTitle' => 'Approve Booking (Warden)',
            'booking' => $booking,
            'alreadyReviewed' => false,
            'statusDisplay' => 'Approved by Warden',
            'success' => 'Booking approved by Hall Warden. Sent to Principal for final approval.'
        ]);
    }

    public function hodReject($id, \Illuminate\Http\Request $request)
    {
        return app(\App\Http\Controllers\AdminController::class)->reject($id, $request);
    }

    public function wardenReject($id, \Illuminate\Http\Request $request)
    {
        return app(\App\Http\Controllers\AdminController::class)->reject($id, $request);
    }
}
