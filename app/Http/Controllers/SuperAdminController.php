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

        $systemUpTime = '99.9%';

        return view('superadmin.dashboard', compact(
            'totalSystemBookings', 'totalRevenue', 'todayRevenue', 'monthRevenue',
            'pendingApprovals', 'principalApprovals', 'approvedBookings', 'rejectedBookings',
            'pendingPayments', 'paidBookings',
            'revenueGrowth', 'lastMonthRevenue', 'monthlyRevenue',
            'topRooms', 'recentBookings', 'alerts', 'systemUpTime'
        ));
    }

    public function settings()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('superadmin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'system_email'        => 'required|email',
            'principal_email'     => 'required|email',
            'hod_email'           => 'required|email',
            'hall_warden_email'   => 'required|email',
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
        ]);

        Setting::updateOrCreate(['key' => 'sender_email'],    ['value' => $request->system_email]);
        Setting::updateOrCreate(['key' => 'principal_email'], ['value' => $request->principal_email]);
        Setting::updateOrCreate(['key' => 'hod_email'],        ['value' => $request->hod_email]);
        Setting::updateOrCreate(['key' => 'hall_warden_email'], ['value' => $request->hall_warden_email]);
        Setting::updateOrCreate(['key' => 'mail_password'],   ['value' => $request->mail_password]);
        
        Setting::updateOrCreate(['key' => 'mail_host'],       ['value' => $request->mail_host]);
        Setting::updateOrCreate(['key' => 'mail_port'],       ['value' => $request->mail_port]);
        Setting::updateOrCreate(['key' => 'mail_encryption'], ['value' => $request->mail_encryption]);
        Setting::updateOrCreate(['key' => 'mail_mailer'],     ['value' => $request->mail_mailer]);
        
        Setting::updateOrCreate(['key' => 'primary_color'],   ['value' => $request->primary_color ?? '#850f0f']);
        Setting::updateOrCreate(['key' => 'secondary_color'], ['value' => $request->secondary_color ?? '#001a33']);
        Setting::updateOrCreate(['key' => 'use_secondary_color'], ['value' => $request->has('use_secondary_color') ? '1' : '0']);
        Setting::updateOrCreate(['key' => 'gst_rate'],            ['value' => $request->gst_rate ?? '5']);

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
        $admins = \App\Models\User::where('role', 'admin')->get();
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

    // Webhook Methods
    public function webhookSettings()
    {
        $endpoints = WebhookEndpoint::withCount('logs')->get();
        $availableEvents = [
            'booking.created',
            'booking.confirmed',
            'booking.cancelled',
            'payment.initiated',
            'payment.successful',
            'payment.failed'
        ];
        $settings = \App\Models\Setting::all()->pluck('value', 'key');
        return view('superadmin.webhooks', compact('endpoints', 'availableEvents', 'settings'));
    }

    public function storeWebhookEndpoint(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
            'events' => 'nullable|array',
            'events.*' => 'string'
        ]);

        WebhookEndpoint::create([
            'url' => $request->url,
            'secret' => Str::random(32),
            'events' => $request->events ?? [],
            'is_active' => true
        ]);

        return redirect()->back()->with('success', 'Webhook endpoint registered successfully.');
    }

    public function updateWebhookEndpoint(Request $request, $id)
    {
        $endpoint = WebhookEndpoint::findOrFail($id);
        
        $request->validate([
            'url' => 'required|url',
            'events' => 'nullable|array',
            'is_active' => 'boolean'
        ]);

        $endpoint->update([
            'url' => $request->url,
            'events' => $request->events ?? [],
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->back()->with('success', 'Webhook endpoint updated.');
    }

    public function deleteWebhookEndpoint($id)
    {
        WebhookEndpoint::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Webhook endpoint removed.');
    }

    public function webhookLogs(Request $request)
    {
        $query = WebhookLog::with('endpoint');

        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }
        if ($request->filled('status')) {
            if ($request->status == 'success') {
                $query->whereBetween('response_status', [200, 299]);
            } else {
                $query->where(function($q) {
                    $q->whereNotBetween('response_status', [200, 299])
                      ->orWhereNull('response_status');
                });
            }
        }

        $settings = \App\Models\Setting::all()->pluck('value', 'key');
        $logs = $query->orderBy('created_at', 'desc')->paginate(20);
        return view('superadmin.webhook_logs', compact('logs', 'settings'));
    }

    public function retryWebhook($id)
    {
        $log = WebhookLog::with('endpoint')->findOrFail($id);
        
        DispatchWebhookJob::dispatch($log->endpoint, $log->event, $log->payload);

        return redirect()->back()->with('success', 'Webhook retry queued.');
    }
}
