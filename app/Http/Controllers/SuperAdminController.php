<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Booking;
use App\Models\WebhookEndpoint;
use App\Models\WebhookLog;
use App\Jobs\DispatchWebhookJob;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SuperAdminController extends Controller
{
    public function index()
    {
        // ── Core System Stats ──────────────────────────────────────────
        $totalSystemBookings  = Booking::count();
        $totalRevenue         = Booking::where('payment_status', 'Paid')->sum('total_price');
        $todayRevenue         = Booking::whereDate('booking_date', Carbon::today())
                                    ->where('payment_status', 'Paid')->sum('total_price');
        $monthRevenue         = Booking::whereMonth('booking_date', Carbon::now()->month)
                                    ->whereYear('booking_date', Carbon::now()->year)
                                    ->where('payment_status', 'Paid')->sum('total_price');

        // ── Booking Status Breakdown ───────────────────────────────────
        $pendingApprovals     = Booking::whereIn('approval_status', ['Pending', 'Pending HOD Approval', 'Pending Warden Approval', 'Pending Principal Approval'])->count();
        $principalApprovals   = Booking::whereIn('approval_status', ['Principal Approved', 'Approved by Principal'])->count();
        $approvedBookings     = Booking::where('approval_status', 'Approved')->count();
        $rejectedBookings     = Booking::where('approval_status', 'Rejected')->count();
        $pendingPayments      = Booking::where('payment_status', 'Pending')->count();
        $paidBookings         = Booking::where('payment_status', 'Paid')->count();

        // ── Month-over-Month Revenue Growth ───────────────────────────
        $lastMonthRevenue = Booking::whereMonth('booking_date', Carbon::now()->subMonth()->month)
                                ->whereYear('booking_date', Carbon::now()->subMonth()->year)
                                ->where('payment_status', 'Paid')->sum('total_price');
        $revenueGrowth = $lastMonthRevenue > 0
            ? round((($monthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : null;

        // ── Monthly Revenue Chart (last 6 months) ─────────────────────
        $monthlyRevenue = Booking::where('payment_status', 'Paid')
            ->where('booking_date', '>=', Carbon::now()->subMonths(6))
            ->select(
                DB::raw("strftime('%Y-%m', booking_date) as month"),
                DB::raw('SUM(total_price) as revenue'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // ── Top Rooms by Booking Volume ────────────────────────────────
        $topRooms = Booking::select('room_name', DB::raw('count(*) as total'), DB::raw('SUM(total_price) as revenue'))
            ->groupBy('room_name')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();

        // ── Recent Bookings ────────────────────────────────────────────
        $recentBookings = Booking::orderBy('created_at', 'desc')->take(8)->get();

        // ── Actionable Alerts ──────────────────────────────────────────
        $alerts = [];
        if ($pendingApprovals > 0)
            $alerts[] = ['type' => 'warning', 'msg' => "$pendingApprovals booking(s) pending principal approval."];
        if ($principalApprovals > 0)
            $alerts[] = ['type' => 'info', 'msg' => "$principalApprovals booking(s) approved by Principal, awaiting admin final action."];
        if ($pendingPayments > 0)
            $alerts[] = ['type' => 'warning', 'msg' => "$pendingPayments approved booking(s) awaiting counter payment."];
        if (empty($alerts))
            $alerts[] = ['type' => 'success', 'msg' => 'All systems are running smoothly. No pending actions.'];

        // ── SuperAdmin System Level Stats ──────────────────────────────
        $totalAdmins          = \App\Models\User::whereIn('role', ['admin', 'superadmin'])->count();
        $totalUsers           = \App\Models\User::count();
        $webhookLogsCount     = WebhookLog::count();
        $systemUpTime         = '99.9%';

        return view('superadmin.dashboard', compact(
            'totalSystemBookings', 'totalRevenue', 'todayRevenue', 'monthRevenue',
            'pendingApprovals', 'principalApprovals', 'approvedBookings', 'rejectedBookings',
            'pendingPayments', 'paidBookings', 'totalAdmins', 'totalUsers', 'webhookLogsCount',
            'revenueGrowth', 'lastMonthRevenue', 'monthlyRevenue',
            'topRooms', 'recentBookings', 'alerts', 'systemUpTime'
        ));
    }

    public function settings()
    {
        $settings = Setting::all()->pluck('value', 'key');
        
        $hallsList = [
            'martin hall'       => ['name' => 'Martin Hall', 'default' => 'martinhall@mcc.edu.in'],
            'barnes hall'       => ['name' => 'Barnes Hall', 'default' => 'wardenbarneshall@mcc.edu.in'],
            'bishop heber hall' => ['name' => 'Bishop Heber Hall', 'default' => 'wardenbishopheberhall@mcc.edu.in'],
            'margaret hall'     => ['name' => 'Margaret Hall', 'default' => 'wardenmargaret@mcc.edu.in'],
            'selaiyur hall'     => ['name' => 'Selaiyur Hall', 'default' => 'wardenselaiyurhall@mcc.edu.in'],
            'st. thomas hall'   => ['name' => 'St. Thomas Hall', 'default' => 'sthwarden@mcc.edu.in'],
        ];

        $deptsList = [
            'mathematics'                             => ['name' => 'Mathematics (Aided)', 'default' => 'hodmaths@mcc.edu.in'],
            'mathematics (sfs)'                       => ['name' => 'Mathematics (SFS)', 'default' => 'hodmathematics-sfs@mcc.edu.in'],
            'philosophy'                              => ['name' => 'Philosophy', 'default' => 'hodphilosophy@mcc.edu.in'],
            'political science'                       => ['name' => 'Political Science', 'default' => 'hodpoliticalscience@mcc.edu.in'],
            'statistics'                              => ['name' => 'Statistics', 'default' => 'hodstatistics@mcc.edu.in'],
            'economics'                               => ['name' => 'Economics', 'default' => 'hodeconomics@mcc.edu.in'],
            'zoology'                                 => ['name' => 'Zoology', 'default' => 'hodzoology@mcc.edu.in'],
            'botany'                                  => ['name' => 'Botany', 'default' => 'hodbotany@mcc.edu.in'],
            'chemistry (aided)'                       => ['name' => 'Chemistry (Aided)', 'default' => 'hodchemistry-aided@mcc.edu.in'],
            'chemistry (sfs)'                         => ['name' => 'Chemistry (SFS)', 'default' => 'hodchemistry-sfs@mcc.edu.in'],
            'commerce (aided)'                        => ['name' => 'Commerce (Aided)', 'default' => 'hodcommerce-aided@mcc.edu.in'],
            'commerce (sfs)'                          => ['name' => 'Commerce (SFS)', 'default' => 'hodcommerce-sfs@mcc.edu.in'],
            'english'                                 => ['name' => 'English (Aided)', 'default' => 'hodenglish@mcc.edu.in'],
            'english language and literature'         => ['name' => 'English Language & Literature (ELL)', 'default' => 'hodell@mcc.edu.in'],
            'history'                                 => ['name' => 'History', 'default' => 'hodhistory@mcc.edu.in'],
            'languages (aided)'                       => ['name' => 'Languages (Aided)', 'default' => 'hodlanguages-aided@mcc.edu.in'],
            'languages (sfs)'                         => ['name' => 'Languages (SFS)', 'default' => 'hodlanguages-sfs@mcc.edu.in'],
            'physics (aided)'                         => ['name' => 'Physics (Aided)', 'default' => 'hodphysics-aided@mcc.edu.in'],
            'physics (sfs)'                           => ['name' => 'Physics (SFS)', 'default' => 'hodphysics-sfs@mcc.edu.in'],
            'public administration'                   => ['name' => 'Public Administration', 'default' => 'hodpublicadministration@mcc.edu.in'],
            'social work (aided)'                     => ['name' => 'Social Work (Aided)', 'default' => 'hodsocialwork-aided@mcc.edu.in'],
            'social work (sfs)'                       => ['name' => 'Social Work (SFS)', 'default' => 'hodsocialwork-sfs@mcc.edu.in'],
            'tamil (aided)'                           => ['name' => 'Tamil (Aided)', 'default' => 'hodtamil-aided@mcc.edu.in'],
            'tamil (sfs)'                             => ['name' => 'Tamil (SFS)', 'default' => 'hodtamil-sfs@mcc.edu.in'],
            'communication'                           => ['name' => 'Communication', 'default' => 'hodcommunication@mcc.edu.in'],
            'data science'                            => ['name' => 'Data Science', 'default' => 'hoddatascience@mcc.edu.in'],
            'computer science'                        => ['name' => 'Computer Science', 'default' => 'hodcomputerscience@mcc.edu.in'],
            'tourism studies'                         => ['name' => 'Tourism Studies', 'default' => 'hodtourismstudies@mcc.edu.in'],
            'business administration'                 => ['name' => 'Business Administration (BBA)', 'default' => 'hodbba@mcc.edu.in'],
            'computer application (bca)'              => ['name' => 'Computer Application (BCA)', 'default' => 'hodbca@mcc.edu.in'],
            'geography'                               => ['name' => 'Geography', 'default' => 'hodgttm@mcc.edu.in'],
            'journalism'                              => ['name' => 'Journalism', 'default' => 'hodjournalism@mcc.edu.in'],
            'mca'                                     => ['name' => 'Master of Computer Applications (MCA)', 'default' => 'hodmca@mcc.edu.in'],
            'microbiology'                            => ['name' => 'Microbiology', 'default' => 'hodmicrobiology@mcc.edu.in'],
            'physical education'                      => ['name' => 'Physical Education', 'default' => 'hodphysicaleducation@mcc.edu.in'],
            'psychology'                              => ['name' => 'Psychology', 'default' => 'hodpsychology@mcc.edu.in'],
            'visual communication'                    => ['name' => 'Visual Communication (Viscom)', 'default' => 'hodviscom@mcc.edu.in'],
        ];

        $savedHodMap = json_decode($settings['hod_email_map'] ?? '[]', true) ?: [];
        $savedWardenMap = json_decode($settings['warden_email_map'] ?? '[]', true) ?: [];
        $superAdminUser = \App\Models\User::where('role', 'superadmin')->first();

        return view('superadmin.settings', compact('settings', 'hallsList', 'deptsList', 'savedHodMap', 'savedWardenMap', 'superAdminUser'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'system_email'        => 'required|email',
            'principal_email'     => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $emails = array_filter(array_map('trim', explode(',', $value)));
                    if (empty($emails)) {
                        $fail('The Principal Email address field is required.');
                        return;
                    }
                    foreach ($emails as $email) {
                        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $fail("The email address '{$email}' is not valid.");
                        }
                    }
                }
            ],
            'hod_email'           => 'nullable|string',
            'hall_warden_email'   => 'nullable|string',
            'mail_password'       => 'required',
            'mail_host'           => 'required|string',
            'mail_port'           => 'required|integer',
            'mail_encryption'     => 'required|string',
            'mail_mailer'         => 'required|string',
            'primary_color'       => 'nullable|string',
            'secondary_color'     => 'nullable|string',
            'use_secondary_color' => 'nullable',
            'gst_rate'            => 'required|numeric|min:0|max:100',
            'whatsapp_enabled'    => 'nullable',
            'principal_phone'     => 'nullable|string',
            'whatsapp_provider'   => 'nullable|string',
            'whatsapp_sender'     => 'nullable|string',
            'whatsapp_sid'        => 'nullable|string',
            'whatsapp_token'      => 'nullable|string',
            'payu_status'         => 'nullable|string',
            'payu_test_mode'      => 'nullable|string',
            'payu_merchant_key'   => 'nullable|string',
            'payu_merchant_salt'  => 'nullable|string',
            'superadmin_name'     => 'nullable|string|max:255',
            'superadmin_email'    => 'nullable|email|max:255',
            'superadmin_password' => 'nullable|string|min:6',
        ]);

        // Update SuperAdmin Account Credentials if provided
        $superAdmin = \App\Models\User::where('role', 'superadmin')->first();
        if ($superAdmin) {
            if ($request->filled('superadmin_name')) {
                $superAdmin->name = $request->superadmin_name;
            }
            if ($request->filled('superadmin_email')) {
                $superAdmin->email = $request->superadmin_email;
            }
            if ($request->filled('superadmin_password')) {
                $superAdmin->password = \Illuminate\Support\Facades\Hash::make($request->superadmin_password);
            }
            $superAdmin->save();
        }

        Setting::updateOrCreate(['key' => 'sender_email'],    ['value' => $request->system_email]);
        Setting::updateOrCreate(['key' => 'principal_email'], ['value' => $request->principal_email]);
        Setting::updateOrCreate(['key' => 'hod_email'],        ['value' => $request->hod_email ?? 'unfortunately2909@gmail.com']);
        Setting::updateOrCreate(['key' => 'hall_warden_email'], ['value' => $request->hall_warden_email ?? 'praveenrock2609@gmail.com']);
        Setting::updateOrCreate(['key' => 'mail_password'],   ['value' => $request->mail_password]);
        
        Setting::updateOrCreate(['key' => 'mail_host'],       ['value' => $request->mail_host]);
        Setting::updateOrCreate(['key' => 'mail_port'],       ['value' => $request->mail_port]);
        Setting::updateOrCreate(['key' => 'mail_encryption'], ['value' => $request->mail_encryption]);
        Setting::updateOrCreate(['key' => 'mail_mailer'],     ['value' => $request->mail_mailer]);
        
        Setting::updateOrCreate(['key' => 'primary_color'],   ['value' => $request->primary_color ?? '#850f0f']);
        Setting::updateOrCreate(['key' => 'secondary_color'], ['value' => $request->secondary_color ?? '#001a33']);
        Setting::updateOrCreate(['key' => 'use_secondary_color'], ['value' => $request->has('use_secondary_color') ? '1' : '0']);
        Setting::updateOrCreate(['key' => 'gst_rate'],            ['value' => $request->gst_rate ?? '5']);

        // PayU Settings
        Setting::updateOrCreate(['key' => 'payu_status'],        ['value' => $request->payu_status ?? 'active']);
        Setting::updateOrCreate(['key' => 'payu_test_mode'],     ['value' => $request->payu_test_mode ?? 'deactive']);
        Setting::updateOrCreate(['key' => 'payu_merchant_key'],  ['value' => trim($request->payu_merchant_key ?? '')]);
        Setting::updateOrCreate(['key' => 'payu_merchant_salt'], ['value' => trim($request->payu_merchant_salt ?? '')]);

        // Save Per-Department and Per-Hall Maps
        if ($request->has('hod_emails') && is_array($request->hod_emails)) {
            $hodMap = array_filter(array_map('trim', $request->hod_emails));
            Setting::updateOrCreate(['key' => 'hod_email_map'], ['value' => json_encode($hodMap)]);
        }

        if ($request->has('warden_emails') && is_array($request->warden_emails)) {
            $wardenMap = array_filter(array_map('trim', $request->warden_emails));
            Setting::updateOrCreate(['key' => 'warden_email_map'], ['value' => json_encode($wardenMap)]);
        }

        // WhatsApp Settings
        Setting::updateOrCreate(['key' => 'whatsapp_enabled'],  ['value' => $request->has('whatsapp_enabled') ? '1' : '0']);
        Setting::updateOrCreate(['key' => 'principal_phone'],   ['value' => $request->principal_phone ?? '']);
        Setting::updateOrCreate(['key' => 'whatsapp_provider'], ['value' => $request->whatsapp_provider ?? 'ultramsg']);
        Setting::updateOrCreate(['key' => 'whatsapp_sender'],   ['value' => $request->whatsapp_sender ?? '']);
        Setting::updateOrCreate(['key' => 'whatsapp_sid'],      ['value' => $request->whatsapp_sid ?? '']);
        Setting::updateOrCreate(['key' => 'whatsapp_token'],    ['value' => $request->whatsapp_token ?? '']);

        return redirect()->back()->with('success', 'System settings updated successfully.');
    }

    public function manageAdmins()
    {
        $admins = \App\Models\User::where('role', 'admin')->orderBy('created_at', 'desc')->get();
        return view('superadmin.admins', compact('admins'));
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'admin',
        ]);

        return redirect()->back()->with('success', 'Admin user created successfully.');
    }

    public function updateAdmin(Request $request, $id)
    {
        $admin = \App\Models\User::where('role', 'admin')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;
        if ($request->filled('password')) {
            $admin->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }
        $admin->save();

        return redirect()->back()->with('success', 'Admin credentials updated successfully.');
    }

    public function deleteAdmin($id)
    {
        $admin = \App\Models\User::where('role', 'admin')->findOrFail($id);
        $admin->delete();

        return redirect()->back()->with('success', 'Admin user removed.');
    }

    public function payments(Request $request)
    {
        $query = Booking::with(['payments' => function($q) {
            $q->orderBy('created_at', 'desc');
        }]);

        // Filters
        if ($request->filled('room')) {
            $query->where('room_name', 'like', '%' . $request->room . '%');
        }
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->filled('approval_status')) {
            $query->where('approval_status', $request->approval_status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('booking_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('booking_date', '<=', $request->date_to);
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('superadmin.payments', compact('bookings'));
    }

    public function roomHistory($room_name)
    {
        // Decode room name if it comes from URL
        $room_name = str_replace('-', ' ', $room_name);
        
        $bookings = Booking::with('payments')
            ->where('room_name', 'like', $room_name)
            ->orderBy('booking_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();

        // Calculate current status
        $isBooked = Booking::where('room_name', 'like', $room_name)
            ->whereDate('booking_date', Carbon::today())
            ->where('payment_status', 'Paid')
            ->exists();

        $roomStatus = $isBooked ? 'ALREADY BOOKED' : 'AVAILABLE';

        return view('superadmin.room_history', compact('bookings', 'room_name', 'roomStatus'));
    }

    public function reports(Request $request)
    {
        $query = Booking::query();

        $preset = $request->get('preset', '');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        if ($preset === 'all') {
            // All time history - no date restriction
        } elseif ($preset === '20days') {
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
            $preset = 'all';
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

        return view('superadmin.reports', compact('bookings', 'totalRevenue', 'netRevenue', 'totalGst', 'gstRate', 'preset', 'startDate', 'endDate'));
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
        
        $gstRate = \App\Models\Setting::where('key', 'gst_rate')->value('value') ?? 5;
        $gstFactor = 1 + ($gstRate / 100);
        
        $totalRevenue = $bookings->sum('total_price');
        $netRevenue = $totalRevenue / $gstFactor;
        $totalGst = $totalRevenue - $netRevenue;
        
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $primaryColor = \App\Models\Setting::where('key', 'primary_color')->value('value') ?? '#850f0f';

        $pdf = \Pdf::loadView('admin.report_pdf', compact('bookings', 'startDate', 'endDate', 'primaryColor', 'totalRevenue', 'netRevenue', 'totalGst', 'gstRate'));
        
        return $pdf->download('System_Revenue_Report_'.now()->format('dM_Y').'.pdf');
    }

    public function exportCsv(Request $request)
    {
        $query = Booking::query();

        if ($request->filled('start_date')) {
            $query->whereDate('booking_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('booking_date', '<=', $request->end_date);
        }

        $bookings = $query->orderBy('booking_date', 'desc')->get();

        $csvFileName = 'System_Bookings_Export_' . now()->format('Y-m-d_H-i') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Booking ID', 'Reference ID', 'Guest Name', 'Email', 'Phone', 'User Type', 'Room Name', 'Booking Date', 'Start Time', 'End Time', 'Total Price', 'Payment Status', 'Approval Status', 'Created At'];

        $callback = function() use ($bookings, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($bookings as $b) {
                fputcsv($file, [
                    'BK-' . str_pad($b->id, 6, '0', STR_PAD_LEFT),
                    $b->reference_id ?? 'N/A',
                    $b->name,
                    $b->email,
                    $b->phone ?? 'N/A',
                    $b->user_type ?? 'Guest',
                    $b->room_name,
                    $b->booking_date,
                    $b->start_time,
                    $b->end_time,
                    $b->total_price,
                    $b->payment_status,
                    $b->approval_status,
                    $b->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
