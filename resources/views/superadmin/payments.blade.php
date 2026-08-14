<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment & Booking Details - SuperAdmin</title>
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
            --danger: #ef4444;
            --info: #3b82f6;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { font-family: 'Inter', sans-serif; background: var(--bg-color); display: flex; min-height: 100vh; }

        /* Sidebar & Main Layout (Standardized) */
        .sidebar { width: var(--sidebar-width); background: white; height: 100vh; border-right: 1px solid var(--border); position: fixed; display: flex; flex-direction: column; z-index: 100; }
        .sidebar-header {
            height: 72px;
            padding: 0 1.25rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-sizing: border-box;
        }
        .sidebar-logo { display: flex; align-items: center; }
        .sidebar-logo img { height: 44px; width: auto; max-width: 135px; object-fit: contain; }
        .superadmin-badge {
            font-size: 0.65rem; font-weight: 800; color: var(--primary-color, #850f0f);
            background: rgba(133, 15, 15, 0.08); padding: 3px 8px; border-radius: 6px;
            letter-spacing: 0.5px; text-transform: uppercase; border: 1px solid rgba(133, 15, 15, 0.15);
            white-space: nowrap;
        }
        .sidebar-menu { flex: 1; padding: 1rem 0.75rem; }
        .menu-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; color: var(--text-muted); text-decoration: none; border-radius: 8px; font-weight: 500; font-size: 0.85rem; transition: all 0.2s; margin-bottom: 0.25rem; }
        .menu-item:hover, .menu-item.active { background: rgba(133, 15, 15, 0.08); color: var(--primary-color); }
        .sidebar-footer { padding: 1rem; border-top: 1px solid var(--border); }
        .logout-btn { width: 100%; display: flex; align-items: center; gap: 0.75rem; background: none; border: none; padding: 0.75rem 1rem; color: var(--danger); cursor: pointer; font-weight: 600; border-radius: 8px; font-size: 0.85rem; }

        .main-content { margin-left: var(--sidebar-width); flex: 1; display: flex; flex-direction: column; width: calc(100% - var(--sidebar-width)); }
        .topbar { height: 72px; background: white; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; padding: 0 2rem; position: sticky; top: 0; z-index: 90; box-shadow: 0 1px 2px rgba(0,0,0,0.03); }
        .page-body { padding: 2rem; max-width: 100%; width: 100%; margin: 0 auto; box-sizing: border-box; }

        .topbar-right { display: flex; align-items: center; gap: 1rem; }
        .admin-profile-btn { width: 36px; height: 36px; background: #f8fafc; border: 1px solid var(--border); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #475569; cursor: pointer; font-size: 1.2rem; transition: all 0.2s; }
        .admin-profile-menu { position: absolute; top: calc(100% + 8px); right: 0; display: none; z-index: 2000; background: #ffffff; border: 1px solid rgba(0,0,0,0.08); border-radius: 12px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1); min-width: 140px; padding: 6px; }
        .admin-profile-menu.open { display: block; }
        .admin-logout-btn { display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 10px; background: #fff1f2; border: 1px solid #fecdd3; color: #ef4444; font-weight: 700; font-size: 0.85rem; border-radius: 8px; cursor: pointer; transition: all 0.2s; }

        /* Master Table Styling */
        .card { background: white; border: 1px solid var(--border); border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); overflow: hidden; }
        .card-header { padding: 1.5rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; }
        .card-title { font-size: 1.1rem; font-weight: 700; color: var(--text-main); display: flex; align-items: center; gap: 0.5rem; }

        /* Filter Section */
        .filters { padding: 1.25rem 1.5rem; background: #fafafa; border-bottom: 1px solid var(--border); display: flex; gap: 1rem; flex-wrap: wrap; }
        .filter-group { display: flex; flex-direction: column; gap: 0.4rem; }
        .filter-group label { font-size: 0.7rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; }
        .filter-control { padding: 0.5rem 0.75rem; border: 1px solid var(--border); border-radius: 8px; font-size: 0.85rem; min-width: 150px; outline: none; }
        .btn-filter { background: var(--primary-color, #850f0f); color: white; border: none; padding: 0 1rem; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 0.4rem; font-size: 0.8rem; height: 34px; margin-top: auto; transition: all 0.15s ease; text-decoration: none; }
        .btn-filter:hover { background: #6b0c0c; opacity: 0.95; }

        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th { font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; font-weight: 600; padding: 10px 12px; background: #f8fafc; text-align: left; border-bottom: 1px solid var(--border); position: sticky; top: 0; z-index: 10; }
        .data-table td { padding: 10px 12px; border-bottom: 1px solid var(--border); font-size: 0.82rem; vertical-align: middle; }
        .data-table tr:hover td { background: #fcfcfc; }

        /* Status Badges */
        .badge { display: inline-flex; align-items: center; gap: 4px; padding: 0.25rem 0.6rem; border-radius: 999px; font-size: 0.65rem; font-weight: 700; text-transform: uppercase; }
        .badge-paid { background: #dcfce7; color: #15803d; }
        .badge-pending { background: #fef9c3; color: #a16207; }
        .badge-failed { background: #fee2e2; color: #b91c1c; }
        .badge-confirmed { background: #eff6ff; color: #1d4ed8; }
        .badge-cancelled { background: #f1f5f9; color: #64748b; }
        .badge-booked { background: #fff7ed; color: #c2410c; }
        .badge-available { background: #059669; color: #ffffff; font-weight: 700; }

        .copy-btn { border: 1px solid var(--border); background: white; padding: 4px 8px; border-radius: 4px; cursor: pointer; color: var(--text-muted); transition: all 0.2s; }
        .copy-btn:hover { color: var(--primary-color); border-color: var(--primary-color); }

        /* Pagination Styling */
        .pagination-container {
            padding: 1.25rem 1.5rem;
            border-top: 1px solid var(--border);
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 0.75rem;
            background: #f8fafc;
        }

        .pagination-info {
            font-size: 0.875rem;
            color: var(--text-muted);
            white-space: nowrap;
        }

        .pagination-links {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }

        .pagination { display: flex; flex-wrap: wrap; list-style: none; padding: 0; margin: 0; gap: 4px; }
        .page-item .page-link {
            display: flex; align-items: center; justify-content: center;
            min-width: 36px; height: 36px; padding: 0 10px;
            border: 1px solid #e2e8f0; border-radius: 8px;
            color: #64748b; font-size: 0.875rem; font-weight: 500;
            text-decoration: none; transition: all 0.2s;
            background: white;
        }
        .page-item.active .page-link {
            background: var(--primary-color); border-color: var(--primary-color); color: white;
            box-shadow: 0 4px 10px rgba(133, 15, 15, 0.25);
        }
        .page-item.disabled .page-link { opacity: 0.5; cursor: not-allowed; background: #f8fafc; }
        .page-item:not(.active):not(.disabled) .page-link:hover {
            background: #f1f5f9; color: var(--primary-color); border-color: var(--primary-color);
        }

        @media (max-width: 640px) {
            .pagination-container {
                flex-direction: column !important;
                align-items: flex-start !important;
                padding: 1rem !important;
                gap: 0.75rem !important;
            }
            .pagination-links { width: 100% !important; }
            .pagination { flex-wrap: wrap !important; gap: 4px !important; }
            .page-item .page-link { min-width: 32px !important; height: 32px !important; font-size: 0.8rem !important; }
        }

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
            <div class="sidebar-logo"><img src="/assets/logo_transparent.png" alt="MCC-MRF Logo"></div>
            <span class="superadmin-badge">SUPERADMIN</span>
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
            <a href="{{ route('superadmin.reports') }}" class="menu-item {{ Route::is('superadmin.reports') ? 'active' : '' }}">
                <i class="ph ph-chart-bar"></i> Reports
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
            <div style="display: flex; align-items: center; gap: 1rem;">
                <button id="sidebarToggle"><i class="ph ph-list" style="font-size: 1.5rem;"></i></button>
                <div style="font-weight: 700; font-size: 1.15rem; color: var(--text-main);">Payment & Booking Master</div>
            </div>
            <div class="topbar-right">
                <div style="font-size: 0.82rem; color: var(--text-muted); font-weight: 500;">{{ now()->format('d M Y, H:i A') }}</div>
                <div style="position: relative;">
                    <button class="admin-profile-btn" id="adminProfileBtn"><i class="ph-fill ph-user"></i></button>
                    <div class="admin-profile-menu" id="adminProfileMenu">
                        <form action="{{ route('superadmin.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="admin-logout-btn">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-body">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title"><i class="ph-bold ph-receipt" style="color: var(--primary-color);"></i> Transaction Ledger</h2>
                </div>

                <form action="{{ route('superadmin.payments') }}" method="GET" class="filters">
                    <div class="filter-group">
                        <label>Search Room</label>
                        <input type="text" name="room" value="{{ request('room') }}" class="filter-control" placeholder="e.g. 101">
                    </div>
                    <div class="filter-group">
                        <label>Payment Status</label>
                        <select name="payment_status" class="filter-control">
                            <option value="">All Status</option>
                            <option value="Paid" {{ request('payment_status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                            <option value="Pending" {{ request('payment_status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>From Date</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="filter-control">
                    </div>
                    <div class="filter-group">
                        <label>To Date</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="filter-control">
                    </div>
                    <button type="submit" class="btn-filter"><i class="ph ph-funnel"></i> Filter</button>
                    <a href="{{ route('superadmin.payments') }}" class="btn-filter" style="background: #f1f5f9; color: #64748b;"><i class="ph ph-arrow-counter-clockwise"></i> Reset</a>
                </form>

                <div style="overflow-x: auto;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Guest Details</th>
                                <th>Room / Space</th>
                                <th>Booking Metadata</th>
                                <th>Payment Info</th>
                                <th>Statuses</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                            <tr>
                                <td>
                                    <div style="font-weight: 700; color: var(--text-main);">MCC/IGH/{{ $booking->created_at->format('Y') }}/{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</div>
                                    <div style="font-size: 0.7rem; color: var(--text-muted);">Created: {{ $booking->created_at->format('d/m/y H:i') }}</div>
                                </td>
                                <td>
                                    <div style="font-weight: 600;">{{ $booking->name }}</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $booking->phone }}</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $booking->email }}</div>
                                </td>
                                <td>
                                    <a href="{{ route('superadmin.room.history', ['room_name' => str_replace(' ', '-', $booking->room_name)]) }}" style="color: var(--primary-color); font-weight: 700; text-decoration: none;">
                                        {{ $booking->room_name }}
                                    </a>
                                </td>
                                <td>
                                    <div style="font-weight: 500;">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M, Y') }}</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">{{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}</div>
                                    <div style="font-size: 0.75rem; font-weight: 600; margin-top: 4px;"><i class="ph ph-users"></i> {{ $booking->no_of_persons }} Guests</div>
                                </td>
                                <td>
                                    <div style="font-weight: 800; color: var(--primary-color);">₹{{ number_format($booking->total_price, 2) }}</div>
                                    @if($booking->payments->count() > 0)
                                        @php $p = $booking->payments->first(); @endphp
                                        <div style="font-size: 0.75rem; display: flex; align-items: center; gap: 5px; margin-top: 4px;">
                                            <span style="font-family: monospace; background: #f1f5f9; padding: 2px 4px; border-radius: 4px;">{{ $p->txnid }}</span>
                                            <button class="copy-btn" onclick="navigator.clipboard.writeText('{{ $p->txnid }}')" title="Copy TXN ID"><i class="ph ph-copy"></i></button>
                                        </div>
                                        <div style="font-size: 0.7rem; color: var(--text-muted); margin-top: 2px;">{{ $p->payment_mode ?? 'Online' }}</div>
                                    @else
                                        <div style="font-size: 0.7rem; color: #94a3b8; font-style: italic;">No transaction yet</div>
                                    @endif
                                </td>
                                <td>
                                    <div style="display: flex; flex-direction: column; gap: 4px;">
                                        <span class="badge {{ $booking->payment_status == 'Paid' ? 'badge-paid' : 'badge-pending' }}">
                                            <i class="ph-fill {{ $booking->payment_status == 'Paid' ? 'ph-check-circle' : 'ph-clock' }}"></i>
                                            Payment: {{ $booking->payment_status }}
                                        </span>
                                        
                                        <span class="badge {{ $booking->approval_status == 'Approved' ? 'badge-confirmed' : ($booking->approval_status == 'Rejected' ? 'badge-failed' : 'badge-pending') }}">
                                            <i class="ph-fill {{ $booking->approval_status == 'Approved' ? 'ph-sparkle' : 'ph-info' }}"></i>
                                            Booking: {{ $booking->approval_status }}
                                        </span>

                                        @php
                                            // Real-time Check: Is this room ALREADY BOOKED (Paid) by anyone on this specific date?
                                            $isLocked = \App\Models\Booking::where('room_name', $booking->room_name)
                                                ->where('booking_date', $booking->booking_date)
                                                ->where('payment_status', 'Paid')
                                                ->exists();
                                        @endphp
                                        <span class="badge {{ $isLocked ? 'badge-booked' : 'badge-available' }}">
                                            <i class="ph-fill {{ $isLocked ? 'ph-lock' : 'ph-lock-open' }}"></i>
                                            Room: {{ $isLocked ? 'ALREADY BOOKED' : 'AVAILABLE' }}
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 3rem; color: var(--text-muted);">
                                    <i class="ph ph-folder-open" style="font-size: 2rem; opacity: 0.2; display: block; margin: 0 auto 1rem;"></i>
                                    No payment or booking records match your filter.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($bookings->hasPages())
                <div class="pagination-container">
                    <div class="pagination-info">
                        Showing {{ $bookings->firstItem() }} to {{ $bookings->lastItem() }} of {{ $bookings->total() }} entries
                    </div>
                    <div class="pagination-links">
                        {{ $bookings->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        const adminProfileBtn = document.getElementById('adminProfileBtn');
        const adminProfileMenu = document.getElementById('adminProfileMenu');
        if (adminProfileBtn) {
            adminProfileBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                adminProfileMenu.classList.toggle('open');
            });
        }

        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('.sidebar');
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                sidebar.classList.toggle('open');
            });
        }

        document.addEventListener('click', (e) => {
            if (adminProfileMenu) adminProfileMenu.classList.remove('open');
            if (window.innerWidth <= 1024 && sidebar && sidebar.classList.contains('open') && !sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        });
    </script>
</body>
</html>

