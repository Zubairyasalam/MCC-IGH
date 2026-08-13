<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminNotification;
use App\Mail\BookingApproved;
use App\Models\PaymentLink;
use App\Mail\PaymentLinkMail;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function index()
    {
        $totalBookings = Booking::where('payment_status', 'Paid')->count();
        $todayBookings = Booking::whereDate('booking_date', Carbon::today())->count();
        $totalRevenue = Booking::where('payment_status', 'Paid')->sum('total_price');
        $todayRevenue = Booking::whereDate('booking_date', Carbon::today())
            ->where('payment_status', 'Paid')
            ->sum('total_price');
        
        // Status Counts
        $completedBookings = Booking::where('approval_status', 'Approved')->count();
        $pendingApprovals = Booking::whereIn('approval_status', ['Pending', 'Pending HOD Approval', 'Pending Warden Approval', 'Pending Principal Approval'])->count();
        $principalApprovals = Booking::whereIn('approval_status', ['Principal Approved', 'Approved by Principal'])->count();
        $pendingPayments = Booking::where('payment_status', 'Pending')->count();
        $cancelledBookings = Booking::where('payment_status', 'Failed')->count();

        // Feed for the Notification Center (Only Unread)
        $notificationBookings = Booking::whereIn('approval_status', ['Pending', 'Pending HOD Approval', 'Pending Warden Approval', 'Pending Principal Approval', 'Principal Approved', 'Approved by Principal'])
            ->where('is_admin_read', false)
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        // Active spaces
        $activeWorkspaces = Booking::where('payment_status', 'Paid')
            ->distinct('room_name')
            ->count('room_name');

        $recentBookings = Booking::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $upcomingBookings = Booking::where('booking_date', '>=', Carbon::today())
            ->where('payment_status', 'Paid')
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->take(5)
            ->get();

        // Revenue analytics
        $dailyRevenue = Booking::where('payment_status', 'Paid')
            ->where('booking_date', '>=', Carbon::now()->subDays(7))
            ->select(DB::raw('DATE(booking_date) as date'), DB::raw('SUM(total_price) as revenue'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $monthlyRevenue = Booking::where('payment_status', 'Paid')
            ->where('booking_date', '>=', Carbon::now()->subMonths(6))
            ->select(DB::raw("strftime('%Y-%m', booking_date) as month"), DB::raw('SUM(total_price) as revenue'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Workspace Analytics
        $workspaceData = Booking::where('payment_status', 'Paid')
            ->select('room_name', DB::raw('count(*) as total_bookings'))
            ->groupBy('room_name')
            ->orderBy('total_bookings', 'desc')
            ->get();
            
        $totalPaidBookings = Booking::where('payment_status', 'Paid')->count();
        foreach($workspaceData as $workspace) {
            $workspace->usage_percentage = $totalPaidBookings > 0 
                ? round(($workspace->total_bookings / $totalPaidBookings) * 100, 1) 
                : 0;
        }

        // Dynamic Insights
        $insights = [];
        if ($todayBookings > 0) {
            $insights[] = "You have $todayBookings bookings scheduled for today.";
        }
        if ($pendingPayments > 0) {
            $insights[] = "There are $pendingPayments bookings awaiting payment confirmation.";
        }
        if ($principalApprovals > 0) {
            $insights[] = "You have $principalApprovals bookings approved by the Principal awaiting your final confirmation.";
        }
        $topSpace = $workspaceData->first();
        if ($topSpace) {
            $insights[] = "{$topSpace->room_name} is your most popular workspace this month.";
        }

        // Real Website Room & Space Availability Status
        $allRoomsList = [
            'Standard Rooms' => [
                'Room 1', 'Room 2', 'Room 3', 'Room 4', 'Room 5',
                'Room 6', 'Room 7', 'Room 8', 'Room 9', 'Room 10',
                'Room 11', 'Room 12', 'Room 13', 'Room 14', 'Room 15',
                'Room 16', 'Room 17', 'Room 18', 'Room 19', 'Room 20'
            ],
            'Advance Rooms' => [
                'Room 101', 'Room 102', 'Room 103', 'Room 104',
                'Room 201', 'Room 203', 'Room 204', 'Room 205', 'Room 206', 'Room 207'
            ],
            'Conference & Special Facilities' => [
                'Conference Room', 'Glass Room', 'Suite Room (202)'
            ]
        ];

        // Fetch active bookings for today
        $todayActiveBookings = Booking::whereDate('booking_date', Carbon::today())
            ->where('approval_status', '!=', 'Rejected')
            ->get();

        $roomAvailabilityStatus = [];
        $totalRoomsCount = 0;
        $totalAvailableRooms = 0;
        $totalReservedRooms = 0;
        $assignedGenericBookingIds = [];

        foreach ($allRoomsList as $category => $rooms) {
            $roomAvailabilityStatus[$category] = [];
            foreach ($rooms as $rName) {
                $totalRoomsCount++;
                $rNameLower = strtolower($rName);

                // Extract numeric identifier if present (e.g. 1-20, 101-207, 202)
                $rNum = null;
                if (preg_match('/\b(\d+)\b/', $rName, $m)) {
                    $rNum = (int)$m[1];
                }

                $matchingBooking = null;

                foreach ($todayActiveBookings as $b) {
                    $bRoomRaw = $b->room_name;
                    $roomTokens = array_map('trim', explode(',', $bRoomRaw));

                    foreach ($roomTokens as $token) {
                        $tLower = strtolower($token);

                        // 1. Direct match (e.g. "Room 2", "Room 101", "Conference Room")
                        if ($tLower === $rNameLower || $tLower === str_replace('room ', '', $rNameLower)) {
                            $matchingBooking = $b;
                            break 2;
                        }

                        // 2. Room number match
                        if ($rNum !== null) {
                            if (preg_match('/\b' . $rNum . '\b/', $tLower)) {
                                // Disambiguate single-digit standard rooms (1-20) from advance room 101/201
                                if ($rNum < 100 && preg_match('/\b(1|2)\d{2}\b/', $tLower)) {
                                    // Skip advance room numbers like 101, 201 when matching Room 1
                                } else {
                                    $matchingBooking = $b;
                                    break 2;
                                }
                            }
                        }

                        // 3. Facility keyword match
                        if (str_contains($rNameLower, 'conference') && str_contains($tLower, 'conference')) {
                            $matchingBooking = $b;
                            break 2;
                        }
                        if (str_contains($rNameLower, 'glass') && str_contains($tLower, 'glass')) {
                            $matchingBooking = $b;
                            break 2;
                        }
                        if (str_contains($rNameLower, 'suite') && (str_contains($tLower, 'suite') || str_contains($tLower, '202'))) {
                            $matchingBooking = $b;
                            break 2;
                        }
                    }

                    // 4. Generic unnumbered room booking fallback
                    if (!$matchingBooking && !in_array($b->id, $assignedGenericBookingIds)) {
                        $bLowerAll = strtolower($bRoomRaw);
                        if (str_contains($rNameLower, 'standard') || ($rNum !== null && $rNum <= 20)) {
                            if (str_contains($bLowerAll, 'standard')) {
                                $matchingBooking = $b;
                                $assignedGenericBookingIds[] = $b->id;
                                break;
                            }
                        }
                        if (str_contains($rNameLower, 'advance') || ($rNum !== null && $rNum >= 101 && $rNum <= 207 && $rNum !== 202)) {
                            if (str_contains($bLowerAll, 'advance') || str_contains($bLowerAll, 'executive')) {
                                $matchingBooking = $b;
                                $assignedGenericBookingIds[] = $b->id;
                                break;
                            }
                        }
                    }
                }

                if ($matchingBooking) {
                    $totalReservedRooms++;
                    $roomAvailabilityStatus[$category][] = [
                        'name' => $rName,
                        'is_available' => false,
                        'status' => 'Reserved',
                        'guest_name' => $matchingBooking->name,
                        'user_type' => $matchingBooking->user_type ?? 'Guest',
                        'time' => ($matchingBooking->start_time && $matchingBooking->end_time) ? (\Carbon\Carbon::parse($matchingBooking->start_time)->format('H:i') . ' - ' . \Carbon\Carbon::parse($matchingBooking->end_time)->format('H:i')) : 'Full Day',
                        'payment_status' => $matchingBooking->payment_status,
                        'approval_status' => $matchingBooking->approval_status,
                        'booking_id' => $matchingBooking->id,
                    ];
                } else {
                    $totalAvailableRooms++;
                    $roomAvailabilityStatus[$category][] = [
                        'name' => $rName,
                        'is_available' => true,
                        'status' => 'Available',
                        'guest_name' => null,
                    ];
                }
            }
        }

        // Fetch all bookings for current month for Admin Calendar
        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $endOfMonth = Carbon::now()->endOfMonth()->toDateString();

        $monthBookings = Booking::where('approval_status', '!=', 'Rejected')
            ->whereBetween('booking_date', [$startOfMonth, $endOfMonth])
            ->orderBy('start_time')
            ->get();

        $calendarBookings = [];
        foreach ($monthBookings as $b) {
            $dayNum = (int) Carbon::parse($b->booking_date)->day;
            if (!isset($calendarBookings[$dayNum])) {
                $calendarBookings[$dayNum] = [];
            }
            $calendarBookings[$dayNum][] = [
                'id' => $b->id,
                'name' => $b->name,
                'email' => $b->email,
                'phone' => $b->phone ?? 'N/A',
                'user_type' => $b->user_type ?? 'Guest',
                'room_name' => $b->room_name,
                'booking_date' => \Carbon\Carbon::parse($b->booking_date)->format('d M Y'),
                'clock_in' => $b->clock_in ? \Carbon\Carbon::parse($b->clock_in)->format('d M Y, h:i A') : ($b->booking_date . ' ' . $b->start_time),
                'clock_out' => $b->clock_out ? \Carbon\Carbon::parse($b->clock_out)->format('d M Y, h:i A') : ($b->booking_date . ' ' . $b->end_time),
                'no_of_persons' => $b->no_of_persons ?? 1,
                'approval_status' => $b->approval_status,
                'payment_status' => $b->payment_status,
                'total_price' => number_format($b->total_price, 2),
                'details_url' => route('admin.bookings.show', $b->id),
            ];
        }

        return view('admin.dashboard', compact(
            'totalBookings', 'todayBookings', 'totalRevenue', 'todayRevenue', 
            'pendingPayments', 'pendingApprovals', 'principalApprovals', 'completedBookings', 'cancelledBookings', 'activeWorkspaces',
            'recentBookings', 'upcomingBookings', 'dailyRevenue', 'monthlyRevenue', 'workspaceData', 'insights', 'notificationBookings',
            'calendarBookings', 'roomAvailabilityStatus', 'totalAvailableRooms', 'totalReservedRooms', 'totalRoomsCount'
        ));
    }

    public function bookings(Request $request)
    {
        $query = Booking::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('id', 'LIKE', "%{$search}%")
                  ->orWhere('razorpay_payment_id', 'LIKE', "%{$search}%");
            });
        }

        // Filters
        if ($request->filled('date')) {
            $query->whereDate('booking_date', $request->date);
        }

        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        if ($request->filled('workspace')) {
            $query->where('room_name', $request->workspace);
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(10);
        
        $workspaces = Booking::distinct('room_name')->pluck('room_name');

        return view('admin.bookings', compact('bookings', 'workspaces'));
    }

    public function exportCsv(Request $request)
    {
        $query = Booking::query();

        $preset = $request->get('preset', '');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        if ($preset === '20days') {
            $query->where('booking_date', '>=', \Carbon\Carbon::now()->subDays(20)->startOfDay()->toDateString());
        } elseif ($preset === '30days') {
            $query->where('booking_date', '>=', \Carbon\Carbon::now()->subDays(30)->startOfDay()->toDateString());
        } elseif ($preset === 'this_month') {
            $query->whereMonth('booking_date', \Carbon\Carbon::now()->month)
                  ->whereYear('booking_date', \Carbon\Carbon::now()->year);
        } elseif ($startDate || $endDate) {
            if ($startDate) $query->where('booking_date', '>=', $startDate);
            if ($endDate)   $query->where('booking_date', '<=', $endDate);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('id', 'LIKE', "%{$search}%");
            });
        }
        if ($request->filled('date'))      $query->whereDate('booking_date', $request->date);
        if ($request->filled('status'))    $query->where('payment_status', $request->status);
        if ($request->filled('workspace')) $query->where('room_name', $request->workspace);

        $bookings = $query->orderBy('booking_date', 'desc')->orderBy('created_at', 'desc')->get();

        $filename = 'bookings_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($bookings) {
            $handle = fopen('php://output', 'w');

            // CSV Header Row
            fputcsv($handle, [
                'Booking ID', 'Guest Name', 'Email', 'Phone', 'Nationality', 'Passport Number', 'Visa Number',
                'Room / Space', 'Booking Date', 'Start Time', 'End Time',
                'No. of Persons', 'User Type', 'Residence Status', 'Approval Status', 'Payment Status',
                'Total Price (₹)', 'Payment ID', 'Submitted At'
            ]);

            foreach ($bookings as $b) {
                fputcsv($handle, [
                    $b->id,
                    $b->name,
                    $b->email,
                    $b->phone ?? '',
                    $b->nationality ?? 'Indian',
                    $b->passport_number ?? '',
                    $b->visa_number ?? '',
                    $b->room_name,
                    \Carbon\Carbon::parse($b->booking_date)->format('d M Y'),
                    \Carbon\Carbon::parse($b->start_time)->format('H:i'),
                    \Carbon\Carbon::parse($b->end_time)->format('H:i'),
                    $b->no_of_persons ?? '',
                    $b->user_type ?? '',
                    $b->residence_status ? ucwords($b->residence_status) : '',
                    $b->approval_status,
                    $b->payment_status,
                    number_format($b->total_price, 2),
                    $b->razorpay_payment_id ?? '',
                    $b->created_at->format('d M Y, H:i'),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function reports(Request $request)
    {
        $query = Booking::query();

        $preset = $request->get('preset', '');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        if ($preset === '20days') {
            $query->where('booking_date', '>=', Carbon::now()->subDays(20)->startOfDay()->toDateString());
        } elseif ($preset === '30days') {
            $query->where('booking_date', '>=', Carbon::now()->subDays(30)->startOfDay()->toDateString());
        } elseif ($preset === 'this_month') {
            $query->whereMonth('booking_date', Carbon::now()->month)
                  ->whereYear('booking_date', Carbon::now()->year);
        } elseif ($startDate || $endDate) {
            if ($startDate) {
                $query->where('booking_date', '>=', $startDate);
            }
            if ($endDate) {
                $query->where('booking_date', '<=', $endDate);
            }
        } else {
            // Default preset to 20 days if no explicit date filter is passed
            $preset = '20days';
            $query->where('booking_date', '>=', Carbon::now()->subDays(20)->startOfDay()->toDateString());
        }

        $bookings = $query->orderBy('booking_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalRevenue = $bookings->where('payment_status', 'Paid')->sum('total_price');
        $gstVal = \App\Models\Setting::where('key', 'gst_rate')->value('value');
        $gstRate = $gstVal !== null ? (float) $gstVal : 5.0;
        $gstFactor = 1 + ($gstRate / 100);
        $netRevenue = $gstFactor > 0 ? ($totalRevenue / $gstFactor) : $totalRevenue;
        $totalGst = $totalRevenue - $netRevenue;

        return view('admin.reports', compact('bookings', 'totalRevenue', 'netRevenue', 'totalGst', 'gstRate', 'preset', 'startDate', 'endDate'));
    }

    public function uploadDocument(Request $request, $id)
    {
        $request->validate([
            'admin_document' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        $booking = Booking::findOrFail($id);

        if ($request->hasFile('admin_document')) {
            $file = $request->file('admin_document');
            $fileName = 'admin_doc_' . $booking->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('admin_documents', $fileName, 'public');

            $booking->update([
                'admin_document' => $path
            ]);

            return back()->with('success', 'Softcopy document uploaded successfully for Booking #' . $booking->id);
        }

        return back()->with('error', 'Failed to upload document.');
    }

    public function show($id)
    {
        $booking = Booking::findOrFail($id);
        
        // Mark as read when admin views details
        if (!$booking->is_admin_read) {
            $booking->update(['is_admin_read' => true]);
        }

        $relatedBookings = Booking::where('reference_id', $booking->reference_id)
            ->where('id', '!=', $booking->id)
            ->get();
        
        return view('admin.booking_details', compact('booking', 'relatedBookings'));
    }

    public function principalApprove($id)
    {
        $booking = Booking::findOrFail($id);
        $status = $booking->approval_status;

        $isPending = in_array($status, ['Pending', 'Pending Principal Approval']);

        if (!$isPending) {
            $statusDisplay = in_array($status, ['Approved', 'Approved by Principal', 'Principal Approved']) 
                ? 'Approved' 
                : ($status === 'Rejected' ? 'Rejected' : $status);

            return view('approval_status', [
                'actionTitle' => 'Approve Booking',
                'booking' => $booking,
                'alreadyReviewed' => true,
                'statusDisplay' => $statusDisplay
            ]);
        }

        $booking->update(['approval_status' => 'Approved by Principal']);

        return view('approval_status', [
            'actionTitle' => 'Approve Booking',
            'booking' => $booking,
            'alreadyReviewed' => false,
            'statusDisplay' => 'Approved',
            'success' => 'Booking approved by Principal. Admin has been notified for final confirmation.'
        ]);
    }

    public function adminApprove($id)
    {
        $booking = Booking::findOrFail($id);
        
        if ($booking->approval_status === 'Rejected') {
            return back()->with('error', 'This booking has already been rejected and cannot be approved.');
        }

        if ($booking->approval_status !== 'Principal Approved' && $booking->approval_status !== 'Approved by Principal' && $booking->approval_status !== 'Approved') {
            return back()->with('error', 'Strict Enforced: This booking must be approved by the Principal first.');
        }

        if ($booking->approval_status !== 'Approved') {
            $booking->update(['approval_status' => 'Approved']);
            app(\App\Services\WebhookService::class)->trigger('booking.confirmed', $booking);
        }
        
        // Generate Secure Payment Token
        $token = Str::random(32);
        $paymentLink = PaymentLink::create([
            'booking_id' => $id,
            'token' => $token,
            'expires_at' => Carbon::now()->addHours(24),
            'is_used' => false
        ]);

        // Notify Guest
        try {
            // Apply Dynamic Mail Config
            $this->applyMailConfig();

            Mail::to($booking->email)->send(new PaymentLinkMail($booking, $paymentLink));
        } catch (\Exception $e) {
            \Log::error('Failed to send guest payment link: ' . $e->getMessage());
        }

        return back()->with('success', 'Booking approved. Payment link has been sent to the guest.');
    }

    public function resendPaymentLink($id)
    {
        $booking = Booking::findOrFail($id);
        
        if ($booking->payment_status === 'Paid') {
            return back()->with('error', 'This booking is already paid.');
        }

        // Generate New Secure Payment Token (invalidates previous if we want, but typically we just send a fresh one)
        $token = Str::random(32);
        $paymentLink = PaymentLink::create([
            'booking_id' => $id,
            'token' => $token,
            'expires_at' => Carbon::now()->addHours(24),
            'is_used' => false
        ]);

        try {
            $this->applyMailConfig();
            Mail::to($booking->email)->send(new PaymentLinkMail($booking, $paymentLink));
        } catch (\Exception $e) {
            \Log::error('Failed to resend guest payment link: ' . $e->getMessage());
            return back()->with('error', 'Failed to send email. Check logs.');
        }

        return back()->with('success', 'A new payment link has been sent to the guest.');
    }

    private function applyMailConfig()
    {
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
    }

    public function reject($id, Request $request)
    {
        $booking = Booking::findOrFail($id);
        $status = $booking->approval_status;

        $isAlreadyReviewed = in_array($status, ['Approved', 'Approved by Principal', 'Principal Approved', 'Rejected']);

        if ($isAlreadyReviewed) {
            $statusDisplay = in_array($status, ['Approved', 'Approved by Principal', 'Principal Approved']) 
                ? 'Approved' 
                : ($status === 'Rejected' ? 'Rejected' : $status);

            if ($request->isMethod('post') && $request->header('Referer') && str_contains($request->header('Referer'), '/admin')) {
                return back()->with('error', "This booking has already been reviewed: {$statusDisplay}. Status cannot be changed.");
            }

            return view('approval_status', [
                'actionTitle' => 'Reject Booking',
                'booking' => $booking,
                'alreadyReviewed' => true,
                'statusDisplay' => $statusDisplay
            ]);
        }

        // If GET request, show rejection reason form with booking details
        if ($request->isMethod('get')) {
            return view('approval_status', [
                'actionTitle' => 'Reject Booking',
                'booking' => $booking,
                'alreadyReviewed' => false,
                'showRejectForm' => true
            ]);
        }

        // Process rejection (POST request)
        $rejectionReason = $request->input('rejection_reason') ?? 'Rejected by authority.';
        $booking->update([
            'approval_status' => 'Rejected',
            'rejection_reason' => $rejectionReason
        ]);

        app(\App\Services\WebhookService::class)->trigger('booking.cancelled', $booking);

        if ($request->header('Referer') && str_contains($request->header('Referer'), '/admin')) {
            return back()->with('success', 'Booking rejected successfully.');
        }

        return view('approval_status', [
            'actionTitle' => 'Reject Booking',
            'booking' => $booking,
            'alreadyReviewed' => true,
            'statusDisplay' => 'Rejected'
        ]);
    }

    public function markAsPaid($id)
    {
        $booking = Booking::findOrFail($id);
        
        $booking->update([
            'payment_status' => 'Paid',
            'razorpay_payment_id' => 'COUNTER_' . uniqid()
        ]);

        return back()->with('success', 'Booking marked as Paid at counter.');
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return redirect()->route('admin.bookings')->with('success', 'Booking deleted successfully.');
    }

    public function markNotificationsRead()
    {
        Booking::whereIn('approval_status', ['Pending', 'Pending HOD Approval', 'Pending Warden Approval', 'Pending Principal Approval', 'Principal Approved', 'Approved by Principal'])
            ->where('is_admin_read', false)
            ->update(['is_admin_read' => true]);
            
        return back()->with('success', 'All notifications marked as read.');
    }

    public function downloadReport(Request $request)
    {
        $query = Booking::query();

        if ($request->filled('start_date')) {
            $query->whereDate('booking_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('booking_date', '<=', $request->end_date);
        }

        $bookings = $query->orderBy('booking_date', 'desc')->get();
        
        // Calculate dynamic totals for the PDF report
        $gstRate = \App\Models\Setting::where('key', 'gst_rate')->value('value') ?? 5;
        $gstFactor = 1 + ($gstRate / 100);
        
        $totalRevenue = $bookings->sum('total_price');
        $netRevenue = $totalRevenue / $gstFactor;
        $totalGst = $totalRevenue - $netRevenue;
        
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $primaryColor = \App\Models\Setting::where('key', 'primary_color')->value('value') ?? '#850f0f';

        // Using the global 'Pdf' alias which is auto-discovered
        $pdf = \Pdf::loadView('admin.report_pdf', compact('bookings', 'startDate', 'endDate', 'primaryColor', 'totalRevenue', 'netRevenue', 'totalGst', 'gstRate'));
        
        return $pdf->download('Revenue_Report_'.now()->format('dM_Y').'.pdf');
    }

    public function showCollegeGuestForm()
    {
        $rooms = [
            'Standard Rooms' => [
                'Standard Room 1' => 'Standard Room 1',
                'Standard Room 2' => 'Standard Room 2',
                'Standard Room 3' => 'Standard Room 3',
                'Standard Room 4' => 'Standard Room 4',
                'Standard Room 5' => 'Standard Room 5',
                'Standard Room 6' => 'Standard Room 6',
                'Standard Room 7' => 'Standard Room 7',
                'Standard Room 8' => 'Standard Room 8',
            ],
            'Advance Rooms' => [
                '101' => 'Room 101 (College Guest Room)',
                '102' => 'Room 102 (Premium Guest Room with Upgraded Interiors)',
                '103' => 'Room 103 (Premium Guest Room with Upgraded Interiors)',
                '104' => 'Room 104 (Premium Guest Room with Upgraded Interiors)',
                '201' => 'Room 201 (Premium Guest Room with Upgraded Interiors)',
                '203' => 'Room 203 (Premium Guest Room with Upgraded Interiors)',
                '204' => 'Room 204 (Premium Guest Room with Upgraded Interiors)',
                '205' => 'Room 205 (Premium Guest Room with Upgraded Interiors)',
                '206' => 'Room 206 (Premium Guest Room with Upgraded Interiors)',
                '207' => 'Room 207 (Premium Guest Room with Upgraded Interiors)',
            ],
            'Conference & Special Rooms' => [
                'Conference Room' => 'Conference Room',
                'Glass Room' => 'Glass Room',
                'Suite Room' => 'Suite Room',
            ],
        ];

        $bookedRooms = [];
        $items = Booking::where('approval_status', '!=', 'Rejected')
            ->whereDate('booking_date', '>=', now()->toDateString())
            ->get();
        foreach ($items as $item) {
            $names = array_map('trim', explode(',', $item->room_name));
            foreach ($names as $r) {
                if (!empty($r)) {
                    $bookedRooms[$r] = ['date' => $item->booking_date, 'time' => $item->end_time];
                }
            }
        }

        return view('admin.college_guest', compact('rooms', 'bookedRooms'));
    }

    public function checkAvailability(Request $request)
    {
        $request->validate([
            'clock_in' => 'required|date',
            'clock_out' => 'required|date|after:clock_in',
        ]);

        $clockIn = \Carbon\Carbon::parse($request->clock_in);
        $clockOut = \Carbon\Carbon::parse($request->clock_out);

        if ($clockOut->lte($clockIn)) {
            return response()->json(['booked_rooms' => [], 'error' => 'Check-out time must be after check-in time.'], 422);
        }

        // Find all rooms booked during this period
        $rawBooked = Booking::where('approval_status', '!=', 'Rejected')
            ->where('booking_date', $clockIn->toDateString())
            ->where(function ($query) use ($clockIn, $clockOut) {
                $query->where(function ($q) use ($clockIn, $clockOut) {
                    $q->where('start_time', '<', $clockOut->toTimeString())
                      ->where('end_time', '>', $clockIn->toTimeString());
                });
            })
            ->pluck('room_name')
            ->toArray();

        $bookedRooms = [];
        foreach ($rawBooked as $rStr) {
            $names = array_map('trim', explode(',', $rStr));
            foreach ($names as $n) {
                if (!empty($n) && !in_array($n, $bookedRooms)) {
                    $bookedRooms[] = $n;
                }
            }
        }

        return response()->json([
            'booked_rooms' => $bookedRooms
        ]);
    }

    public function storeCollegeGuestBooking(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'room_name' => 'required|string|max:255',
            'payment_type' => 'nullable|string|in:non_payment,payment',
            'clock_in' => 'required|date',
            'clock_out' => 'required|date|after:clock_in',
            'no_of_persons' => 'required|integer|min:1',
            'booking_reason' => 'nullable|string',
        ]);

        $clockIn = \Carbon\Carbon::parse($request->clock_in);
        $clockOut = \Carbon\Carbon::parse($request->clock_out);

        if ($clockOut->lte($clockIn)) {
            return back()->withInput()->with('error', 'Check-Out date and time must be strictly after Check-In date and time.');
        }

        // Parse selected rooms (supports single room or multi-room selection)
        $selectedRooms = array_filter(array_map('trim', explode(',', $request->room_name)));
        if (empty($selectedRooms)) {
            $selectedRooms = [$request->room_name];
        }

        // Calculate combined maximum capacity for all selected rooms
        $maxCapacity = 0;
        foreach ($selectedRooms as $rName) {
            $roomVal = strtolower($rName);
            if (str_contains($roomVal, 'conference-hall') || str_contains($roomVal, 'conference-room')) {
                $maxCapacity += 60;
            } elseif (str_contains($roomVal, 'glass')) {
                $maxCapacity += 20;
            } elseif (str_contains($roomVal, 'suite')) {
                $maxCapacity += 4;
            } elseif (str_contains($roomVal, 'standard')) {
                $maxCapacity += 2;
            } elseif (is_numeric($rName) || (is_numeric(substr($rName, 0, 1)) && strlen($rName) <= 4) || str_contains($roomVal, 'advance')) {
                $maxCapacity += 4;
            } else {
                $maxCapacity += 4;
            }
        }

        if ($request->no_of_persons > $maxCapacity) {
            return back()->withInput()->with('error', "Number of guests exceeds the maximum total capacity of {$maxCapacity} for your selected room(s).");
        }

        // Check availability for each selected room individually
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
                return back()->withInput()->with('error', "{$singleRoom} is already booked for this date and time slot.");
            }
        }

        $paymentType = $request->input('payment_type', 'non_payment');
        $totalPrice = 0;

        if ($paymentType === 'payment') {
            $durationMinutes = $clockIn->diffInMinutes($clockOut);
            $durationHours = max(0.25, $durationMinutes / 60.0);

            $basePrice = 0;
            foreach ($selectedRooms as $rName) {
                $normR = strtolower($rName);
                if (str_contains($normR, 'standard')) {
                    $twelveHourBlocks = (int) ceil($durationHours / 12.0);
                    $basePrice += max(1, $twelveHourBlocks) * 1400;
                } elseif (is_numeric($rName) || (is_numeric(substr($rName, 0, 1)) && strlen($rName) <= 4) || str_contains($normR, 'advance')) {
                    $days = (int) ceil($durationHours / 24.0);
                    $basePrice += max(1, $days) * 2500;
                } elseif (in_array($normR, ['conference-hall', 'conference-room', 'glass-room', 'suite-room']) || str_contains($normR, 'conference') || str_contains($normR, 'glass') || str_contains($normR, 'suite')) {
                    $billableHours = max(4, (int) ceil($durationHours));
                    $basePrice += $billableHours * 500;
                } else {
                    $basePrice += $durationHours > 4 ? 5000 : 2000;
                }
            }

            try {
                $gstRate = \App\Models\Setting::where('key', 'gst_rate')->value('value') ?? 5;
            } catch (\Throwable $e) {
                $gstRate = 5;
            }
            $totalPrice = $basePrice * (1 + ($gstRate / 100));
        }

        // Create booking
        $booking = new Booking();
        $booking->name = $request->name;
        $booking->email = $request->email;
        $booking->phone = $request->phone;
        $booking->nationality = 'Indian';
        $booking->user_type = 'College Guest';
        $booking->stream = 'College';
        $booking->level = 'N/A';
        $booking->department = $request->designation;
        $booking->primary_guest_name = $request->name;
        $booking->no_of_persons = $request->no_of_persons;
        $booking->room_name = $request->room_name;
        $booking->booking_date = $clockIn->toDateString();
        $booking->start_time = $clockIn->toTimeString();
        $booking->end_time = $clockOut->toTimeString();
        $booking->clock_in = $clockIn->toDateTimeString();
        $booking->clock_out = $clockOut->toDateTimeString();

        if ($paymentType === 'payment') {
            $booking->total_price = $totalPrice;
            $booking->payment_status = 'Pending';
            $booking->approval_status = 'Approved';
            $booking->razorpay_order_id = 'PAYMENT_LINK';
            $booking->razorpay_payment_id = null;
        } else {
            $booking->total_price = 0;
            $booking->payment_status = 'Paid';
            $booking->approval_status = 'Approved';
            $booking->razorpay_order_id = 'COLLEGE_GUEST';
            $booking->razorpay_payment_id = 'FREE_EXEMPT';
        }

        $booking->is_admin_read = true;
        $booking->booking_reason = $request->booking_reason;
        $booking->save();

        if ($paymentType === 'payment') {
            $token = Str::random(32);
            $paymentLink = PaymentLink::create([
                'booking_id' => $booking->id,
                'token' => $token,
                'expires_at' => Carbon::now()->addHours(24),
                'is_used' => false
            ]);

            try {
                $this->applyMailConfig();
                Mail::to($booking->email)->send(new PaymentLinkMail($booking, $paymentLink));
            } catch (\Throwable $e) {
                \Log::error('Failed to send college guest payment link email: ' . $e->getMessage());
            }

            return redirect()->route('admin.bookings')->with('success', 'College guest booking created. Payment link has been emailed to ' . $booking->email);
        }

        return redirect()->route('admin.bookings')->with('success', 'College guest booking created successfully (Complimentary / Non-Payment).');
    }

    public function addRoomToBooking(Request $request, $id)
    {
        $originalBooking = Booking::findOrFail($id);
        
        $request->validate([
            'room_name' => 'required|string',
            'clock_in' => 'required|date',
            'clock_out' => 'required|date|after:clock_in',
            'no_of_persons' => 'required|integer|min:1',
        ]);

        $clockIn = Carbon::parse($request->clock_in);
        $clockOut = Carbon::parse($request->clock_out);
        
        if ($clockOut->lte($clockIn)) {
            return back()->withInput()->with('error', 'Check-Out date and time must be strictly after Check-In date and time.');
        }

        // Double booking check
        $exists = Booking::where('room_name', $request->room_name)
            ->where('booking_date', $clockIn->toDateString())
            ->where('approval_status', '!=', 'Rejected')
            ->where(function ($query) use ($clockIn, $clockOut) {
                $query->where(function ($q) use ($clockIn, $clockOut) {
                    $q->where('start_time', '<', $clockOut->toTimeString())
                        ->where('end_time', '>', $clockIn->toTimeString());
                });
            })->exists();

        if ($exists) {
            return back()->with('error', 'Selected room is already booked for this time slot.');
        }

        // Calculate duration in hours
        $durationMinutes = $clockIn->diffInMinutes($clockOut);
        if ($durationMinutes < 15) {
            return back()->withInput()->with('error', 'Booking duration must be at least 15 minutes.');
        }
        $durationHours = $durationMinutes / 60.0;
        
        $basePrice = 0;
        $roomName = $request->room_name;
        
        // Dynamic Pricing Logic based on Category
        if (str_contains(strtolower($roomName), 'standard')) {
            $twelveHourBlocks = (int) ceil($durationHours / 12.0);
            $basePrice = max(1, $twelveHourBlocks) * 1400;
        } elseif (is_numeric($roomName) || (is_numeric(substr($roomName, 0, 1)) && strlen($roomName) <= 4) || str_contains(strtolower($roomName), 'advance')) {
            $days = (int) ceil($durationHours / 24.0);
            $basePrice = max(1, $days) * 2500;
        } elseif (in_array(strtolower($roomName), ['conference-hall', 'conference-room', 'glass-room', 'suite-room'])) {
            $billableHours = max(4, (int) ceil($durationHours));
            $basePrice = $billableHours * 500;
        } else {
            $basePrice = $durationHours > 4 ? 5000 : 2000;
        }
        
        $gstRate = \App\Models\Setting::where('key', 'gst_rate')->value('value') ?? 5;
        $totalPrice = $basePrice * (1 + ($gstRate / 100));

        // Create the new booking record
        $newBooking = Booking::create([
            'reference_id' => $originalBooking->reference_id,
            'name' => $originalBooking->name,
            'email' => $originalBooking->email,
            'phone' => $originalBooking->phone,
            'nationality' => $originalBooking->nationality,
            'user_type' => $originalBooking->user_type,
            'stream' => $originalBooking->stream,
            'level' => $originalBooking->level,
            'department' => $originalBooking->department,
            'primary_guest_name' => $originalBooking->primary_guest_name,
            'no_of_persons' => $request->no_of_persons,
            'passport_number' => $originalBooking->passport_number,
            'visa_number' => $originalBooking->visa_number,
            'gst_id' => $originalBooking->gst_id,
            'room_name' => $request->room_name,
            'booking_date' => $clockIn->toDateString(),
            'start_time' => $clockIn->toTimeString(),
            'end_time' => $clockOut->toTimeString(),
            'clock_in' => $clockIn->toDateTimeString(),
            'clock_out' => $clockOut->toDateTimeString(),
            'total_price' => $totalPrice,
            'payment_status' => 'Pending',
            'approval_status' => 'Approved',
            'referral_attachment' => $originalBooking->referral_attachment,
            'passport_attachment' => $originalBooking->passport_attachment,
            'visa_attachment' => $originalBooking->visa_attachment,
            'passport_visa_attachment' => $originalBooking->passport_visa_attachment,
            'is_admin_read' => true,
            'booking_reason' => $originalBooking->booking_reason,
            'residence_status' => $originalBooking->residence_status
        ]);

        // Trigger Webhook
        app(\App\Services\WebhookService::class)->trigger('booking.created', $newBooking);
        app(\App\Services\WebhookService::class)->trigger('booking.confirmed', $newBooking);

        // Generate Secure Payment Token
        $token = Str::random(32);
        $paymentLink = PaymentLink::create([
            'booking_id' => $newBooking->id,
            'token' => $token,
            'expires_at' => Carbon::now()->addHours(24),
            'is_used' => false
        ]);

        // Notify Guest with Payment Link only for this new room
        try {
            $this->applyMailConfig();
            Mail::to($newBooking->email)->send(new PaymentLinkMail($newBooking, $paymentLink));
        } catch (\Exception $e) {
            \Log::error('Failed to send guest payment link for added room: ' . $e->getMessage());
        }

        return back()->with('success', 'Additional room added successfully and payment link sent to the guest.');
    }
}
