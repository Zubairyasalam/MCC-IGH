<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Analytics: {{ $room_name }} - SuperAdmin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <style>
        :root {
            --sidebar-width: 260px;
            --bg-color: #f8fafc;
            --primary-color: #850f0f;
            --border: #e2e8f0;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --success: #22c55e;
            --warning: #f59e0b;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { font-family: 'Inter', sans-serif; background: var(--bg-color); display: flex; min-height: 100vh; }

        .sidebar { width: var(--sidebar-width); background: white; height: 100vh; border-right: 1px solid var(--border); position: fixed; display: flex; flex-direction: column; z-index: 100; }
        .sidebar-header { padding: 1.5rem; border-bottom: 1px solid var(--border); }
        .sidebar-logo { display: flex; align-items: center; padding: 0.25rem 0; }
        .sidebar-logo img { height: 80px; width: auto; object-fit: contain; }
        .sidebar-menu { flex: 1; padding: 1rem 0.75rem; }
        .menu-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; color: var(--text-muted); text-decoration: none; border-radius: 8px; font-weight: 500; font-size: 0.85rem; transition: all 0.2s; margin-bottom: 0.25rem; }
        .menu-item:hover, .menu-item.active { background: rgba(133, 15, 15, 0.08); color: var(--primary-color); }
        .sidebar-footer { padding: 1rem; border-top: 1px solid var(--border); }
        .logout-btn { width: 100%; display: flex; align-items: center; gap: 0.75rem; background: none; border: none; padding: 0.75rem 1rem; color: #ef4444; cursor: pointer; font-weight: 600; border-radius: 8px; font-size: 0.85rem; }

        .main-content { margin-left: var(--sidebar-width); flex: 1; display: flex; flex-direction: column; width: calc(100% - var(--sidebar-width)); }
        .topbar { height: 72px; background: white; border-bottom: 1px solid var(--border); display: flex; align-items: center; padding: 0 2rem; position: sticky; top: 0; z-index: 90; gap: 1rem; }
        .page-body { padding: 2.5rem; max-width: 1200px; width: 100%; margin: 0 auto; box-sizing: border-box; }

        .btn-back { display: inline-flex; align-items: center; gap: 0.5rem; color: var(--text-muted); text-decoration: none; font-weight: 600; font-size: 0.85rem; transition: color 0.2s; }
        .btn-back:hover { color: var(--primary-color); }

        .room-hero { background: white; border: 1px solid var(--border); border-radius: 20px; padding: 2.5rem; margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); }
        .room-info h1 { font-size: 2rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.5rem; }
        .room-info p { color: var(--text-muted); font-size: 1rem; }
        
        .status-card { text-align: right; }
        .status-label { font-size: 0.7rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.5rem; }
        .status-badge { padding: 0.5rem 1.25rem; border-radius: 12px; font-weight: 800; font-size: 0.9rem; }
        .badge-live { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .badge-locked { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }

        .timeline-card { background: white; border: 1px solid var(--border); border-radius: 20px; padding: 2rem; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); }
        .timeline-title { font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin-bottom: 2rem; display: flex; align-items: center; gap: 0.5rem; }

        .history-table { width: 100%; border-collapse: collapse; }
        .history-table th { background: #f8fafc; padding: 1rem; text-align: left; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; color: var(--text-muted); border-bottom: 1px solid var(--border); }
        .history-table td { padding: 1.25rem 1rem; border-bottom: 1px solid var(--border); font-size: 0.9rem; }
        .history-table tr:last-child td { border-bottom: none; }

        .pill { display: inline-block; padding: 0.2rem 0.6rem; border-radius: 999px; font-size: 0.7rem; font-weight: 700; }
        .pill-paid { background: #dcfce7; color: #15803d; }
        .pill-pending { background: #fef9c3; color: #a16207; }

        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s ease; }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; width: 100%; }
            #sidebarToggle { display: flex !important; }
        }
        #sidebarToggle { display: none; width: 44px; height: 44px; padding: 0; align-items: center; justify-content: center; border-radius: 12px; border: 1px solid var(--border); background: white; color: var(--text-main); cursor: pointer; }
    </style>
    @include('partials.dynamic-styles')
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo"><img src="/assets/logo_transparent.png" alt="MCC-MRF Logo" style="height:80px; width:auto; object-fit:contain;"></div>
        </div>
        <nav class="sidebar-menu">
            <a href="{{ route('superadmin.dashboard') }}" class="menu-item">
                <i class="ph ph-squares-four"></i> Overview
            </a>
            <a href="{{ route('superadmin.admins') }}" class="menu-item {{ Route::is('superadmin.admins') ? 'active' : '' }}">
                <i class="ph ph-users"></i> Manage Admins
            </a>
            <a href="{{ route('superadmin.payments') }}" class="menu-item active">
                <i class="ph ph-wallet"></i> Payment Details
            </a>
            <a href="{{ route('superadmin.webhooks') }}" class="menu-item {{ Route::is('superadmin.webhooks') ? 'active' : '' }}">
                <i class="ph-bold ph-plugs-connected"></i> Webhooks
            </a>
            <a href="{{ route('superadmin.webhooks.logs') }}" class="menu-item {{ Route::is('superadmin.webhooks.logs') ? 'active' : '' }}">
                <i class="ph-bold ph-article"></i> Webhook Logs
            </a>
            <a href="{{ route('superadmin.settings') }}" class="menu-item">
                <i class="ph ph-gear"></i> System Settings
            </a>
            <a href="{{ route('home') }}" class="menu-item" target="_blank">
                <i class="ph ph-globe"></i> Visit Site
            </a>
        </nav>
        <div class="sidebar-footer">
            <form action="{{ route('superadmin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn"><i class="ph-bold ph-sign-out"></i> Logout</button>
            </form>
        </div>
    </div>

    <div class="main-content">
        <div class="topbar">
            <button id="sidebarToggle"><i class="ph ph-list" style="font-size: 1.5rem;"></i></button>
            <a href="{{ route('superadmin.payments') }}" class="btn-back">
                <i class="ph ph-arrow-left"></i> Back to Ledger
            </a>
        </div>

        <div class="page-body">
            <div class="room-hero">
                <div class="room-info">
                    <p>Asset Analytics</p>
                    <h1>{{ $room_name }}</h1>
                    <div style="display:flex; gap: 1rem; align-items:center; margin-top: 0.5rem;">
                        <span style="font-weight: 600; color: var(--text-muted);"><i class="ph ph-tag"></i> MCC Guest House Resource</span>
                        <span style="width: 4px; height: 4px; background: #cbd5e1; border-radius: 50%;"></span>
                        <span style="font-weight: 600; color: var(--text-muted);"><i class="ph ph-users"></i> Multi-use Space</span>
                    </div>
                </div>
                <div class="status-card">
                    <div class="status-label">Current Availability</div>
                    <div class="status-badge {{ $roomStatus == 'ALREADY BOOKED' ? 'badge-locked' : 'badge-live' }}">
                        <i class="ph-fill {{ $roomStatus == 'ALREADY BOOKED' ? 'ph-lock' : 'ph-check-circle' }}"></i>
                        {{ $roomStatus }}
                    </div>
                    <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 8px;">As of {{ date('d M, Y') }}</div>
                </div>
            </div>

            <div class="timeline-card">
                <div class="timeline-title">
                    <i class="ph ph-clock-counter-clockwise"></i> Booking History for this Resource
                </div>
                <div style="overflow-x: auto;">
                    <table class="history-table">
                        <thead>
                            <tr>
                                <th>Guest</th>
                                <th>Schedule</th>
                                <th>Payment</th>
                                <th>Transaction</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                            <tr>
                                <td>
                                    <div style="font-weight: 700;">{{ $booking->name }}</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $booking->email }}</div>
                                </td>
                                <td>
                                    <div style="font-weight: 600;">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M, Y') }}</div>
                                    <div style="font-size: 0.75rem; color: var(--primary-color); font-weight: 700;">{{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}</div>
                                </td>
                                <td>
                                    <div style="font-weight: 700; color: var(--text-main);">₹{{ number_format($booking->total_price, 2) }}</div>
                                </td>
                                <td>
                                    @if($booking->payments->count() > 0)
                                        <div style="font-family: monospace; font-size: 0.8rem; background: #f1f5f9; padding: 4px 8px; border-radius: 6px; display: inline-block;">{{ $booking->payments->first()->txnid }}</div>
                                    @else
                                        <span style="color: #94a3b8; font-style: italic; font-size: 0.8rem;">No record</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="pill {{ $booking->payment_status == 'Paid' ? 'pill-paid' : 'pill-pending' }}">
                                        {{ $booking->payment_status }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" style="text-align:center; padding: 3rem; color: var(--text-muted);">This room has no recorded booking history.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('.sidebar');
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                sidebar.classList.toggle('open');
            });
        }
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 1024 && sidebar && sidebar.classList.contains('open') && !sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        });
    </script>
</body>
</html>

