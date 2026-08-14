<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SuperAdmin Dashboard - MCC IGH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-color);
            margin: 0;
            padding: 0;
        }

        /* -- Sidebar -- */
        .sidebar {
            width: var(--sidebar-width);
            background: white;
            height: 100vh;
            border-right: 1px solid var(--border);
            position: fixed;
            display: flex;
            flex-direction: column;
            z-index: 100;
        }

        .sidebar-header {
            height: 72px;
            padding: 0 1.25rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-sizing: border-bottom;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
        }

        .sidebar-logo img { height: 44px; width: auto; max-width: 135px; object-fit: contain; }

        .superadmin-badge {
            font-size: 0.65rem;
            font-weight: 800;
            color: var(--primary-color, #850f0f);
            background: rgba(133, 15, 15, 0.08);
            padding: 3px 8px;
            border-radius: 6px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            border: 1px solid rgba(133, 15, 15, 0.15);
            white-space: nowrap;
        }

        .sidebar-menu {
            flex: 1;
            padding: 1rem 0.75rem;
            display: flex;
            flex-direction: column;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.2s;
            margin-bottom: 0.25rem;
        }

        .menu-item:hover, .menu-item.active {
            background: rgba(133, 15, 15, 0.08);
            color: var(--primary-color);
        }

        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid var(--border);
        }

        .logout-btn {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: none;
            border: none;
            padding: 0.75rem 1rem;
            color: var(--danger);
            cursor: pointer;
            font-weight: 600;
            border-radius: 8px;
            font-size: 0.9rem;
            font-family: inherit;
            transition: background 0.2s;
        }

        .logout-btn:hover { background: #fef2f2; }

        /* -- Main -- */
        .main-content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            display: flex;
            flex-direction: column;
            background: var(--bg);
        }

        .topbar {
            height: 56px; background: white; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between; padding: 0 1.5rem;
            position: sticky; top: 0; z-index: 90;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        }

        .topbar-title {
            font-weight: 700;
            font-size: 0.95rem;
            color: #0f172a;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }


        .page-body { padding: 1.25rem 1.5rem; max-width: 1400px; width: 100%; margin: 0 auto; box-sizing: border-box; }

        /* -- Welcome Banner -- */
        .welcome-banner {
            background: linear-gradient(135deg, #ff8a00 0%, #e65c00 100%);
            border-radius: 12px;
            padding: 1.25rem 1.75rem;
            color: white;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .welcome-banner h1 { font-size: 1.3rem; font-weight: 700; margin-bottom: 0.2rem; }
        .welcome-banner p  { opacity: 0.88; font-size: 0.85rem; }

        .welcome-stats {
            display: flex;
            gap: 1.5rem;
        }

        .welcome-stat {
            text-align: center;
        }

        .welcome-stat .val { font-size: 1.25rem; font-weight: 700; }
        .welcome-stat .lbl { font-size: 0.65rem; opacity: 0.85; text-transform: uppercase; letter-spacing: 0.5px; }

        /* Stats Cards - Refined Professional Enterprise Grid */
        .stats-grid {
            display: grid; 
            grid-template-columns: repeat(4, 1fr); 
            gap: 0.75rem; 
            margin-bottom: 1.25rem;
            width: 100%;
            max-width: 100%;
        }

        .stat-card-colored {
            border-radius: 8px;
            padding: 0.75rem 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.65rem;
            color: #ffffff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
            text-decoration: none;
            position: relative;
            min-height: 72px;
        }

        .stat-card-colored:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        }

        .stat-card-colored .card-icon-badge {
            width: 34px;
            height: 34px;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: #ffffff;
            flex-shrink: 0;
        }

        .stat-card-colored .card-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-width: 0;
            flex: 1;
        }

        .stat-card-colored .card-label {
            font-size: 0.68rem;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 2px;
            line-height: 1.2;
            white-space: normal;
            word-break: normal;
            overflow: visible;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            opacity: 0.92;
        }

        .stat-card-colored .card-value {
            font-size: 1.2rem;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.1;
            letter-spacing: -0.01em;
        }

        .stat-card-colored .card-subtext {
            font-size: 0.65rem;
            color: rgba(255, 255, 255, 0.88);
            margin-top: 2px;
            font-weight: 500;
            line-height: 1.2;
        }

        /* Solid Matte Rich Color Variants */
        .card-bg-blue { background: #0284c7; }
        .card-bg-navy { background: #0f172a; }
        .card-bg-orange { background: #d97706; }
        .card-bg-purple { background: #4f46e5; }
        .card-bg-green { background: #16a34a; }
        .card-bg-red { background: #dc2626; }
        .card-bg-sky { background: #0288d1; }
        .card-bg-pink { background: #9333ea; }

        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        /* -- Grid Layout -- */
        .row-2 { display: grid; grid-template-columns: 2fr 1fr; gap: 1.25rem; margin-bottom: 1.5rem; }
        .row-2-equal { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.5rem; }

        /* -- Card -- */
        .card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.25rem;
        }

        .card-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* -- Chart -- */
        .chart-wrap { height: 220px; position: relative; }

        /* -- Table -- */
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th {
            font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.05em;
            color: var(--muted); font-weight: 700; padding: 0.6rem 0.75rem;
            background: #f8fafc; text-align: left; border-bottom: 1px solid var(--border);
        }
        .data-table td {
            font-size: 0.8rem; color: var(--text); padding: 0.7rem 0.75rem;
            border-bottom: 1px solid var(--border);
        }
        .data-table tr:last-child td { border-bottom: none; }
        .data-table tr:hover td { background: #fafafa; }

        /* -- Status Pills -- */
        .pill { display: inline-block; padding: 0.15rem 0.55rem; border-radius: 999px; font-size: 0.65rem; font-weight: 700; }
        .pill-paid     { background: #dcfce7; color: #15803d; }
        .pill-pending  { background: #fef9c3; color: #a16207; }
        .pill-approved { background: #dcfce7; color: #15803d; }
        .pill-approved-by-principal { background: #dbeafe; color: #1d4ed8; }
        .pill-principal-approved { background: #dbeafe; color: #1d4ed8; }
        .pill-rejected { background: #fee2e2; color: #b91c1c; }

        /* -- Alerts -- */
        .alert-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 0.75rem;
            border-radius: 8px;
            margin-bottom: 0.75rem;
            font-size: 0.82rem;
            font-weight: 500;
        }
        .alert-warning { background: #fffbeb; border: 1px solid #fde68a; color: #92400e; }
        .alert-info    { background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; }
        .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #14532d; }

        /* -- Progress Bar -- */
        .progress-bg   { height: 6px; background: #f1f5f9; border-radius: 999px; overflow: hidden; margin-top: 4px; }
        .progress-fill { height: 100%; background: var(--primary); border-radius: 999px; }

        #sidebarToggle { display: none; }

        /* -- RESPONSIVE -- */
        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s ease; }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0 !important; width: 100% !important; }
            .topbar { padding: 0 1rem !important; }
            .page-body { padding: 1.25rem !important; }
            #sidebarToggle { display: flex !important; margin-right: 0.5rem; }
            .stat-card { padding: 1rem !important; }
            .stat-value { font-size: 1.4rem !important; }
            .topbar-title { font-size: 1rem !important; }
            .welcome-banner { padding: 1.5rem !important; flex-direction: column; text-align: center; gap: 1.5rem; }
            .welcome-stats { gap: 1rem; width: 100%; justify-content: center; }
        }

        @media (max-width: 640px) {
            .stats-grid { grid-template-columns: 1fr 1fr !important; }
            .row-2, .row-2-equal { grid-template-columns: 1fr !important; }
            .welcome-stats { flex-wrap: wrap; }
            .welcome-banner h1 { font-size: 1.4rem !important; }
            .topbar-right { display: none !important; }
        }

        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: 1fr !important; }
        }

        /* Refined Admin Profile Dropdown - Polished Card Style */
        .admin-profile-wrap { position: relative; display: inline-flex; align-items: center; }
        .admin-profile-btn {
            width: 36px; height: 36px;
            background: #f8fafc; border: 1px solid var(--border);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: #475569; cursor: pointer; font-size: 1.2rem;
            transition: all 0.2s;
        }
        .admin-profile-btn:hover { background: #f1f5f9; color: var(--primary-color); border-color: var(--primary-color); }
        .admin-profile-menu {
            position: absolute; top: calc(100% + 8px); right: 0;
            display: none; z-index: 2000;
            background: #ffffff;
            border: 1px solid rgba(0,0,0,0.08);
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1);
            min-width: 140px;
            padding: 6px;
        }
        .admin-profile-menu.open { display: block; animation: dropdownIn 0.2s ease-out; }
        @keyframes dropdownIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .admin-logout-form { margin: 0; padding: 0; }
        .admin-logout-btn {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            width: 100%; padding: 10px;
            background: #fff1f2; border: 1px solid #fecdd3;
            color: #ef4444; font-weight: 700;
            font-size: 0.85rem; border-radius: 8px;
            cursor: pointer; font-family: 'Inter', sans-serif;
            transition: all 0.2s;
        }
        .admin-logout-btn:hover { background: #ef4444; color: white; border-color: #ef4444; }
    </style>
    @include('partials.dynamic-styles')
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <img src="/assets/logo_transparent.png" alt="MCC-MRF Innovation Park">
            </div>
            <span class="superadmin-badge">SUPERADMIN</span>
        </div>
        <nav class="sidebar-menu">
            <a href="{{ route('superadmin.dashboard') }}" class="menu-item {{ Route::is('superadmin.dashboard') ? 'active' : '' }}">
                <i class="ph ph-squares-four"></i> Overview
            </a>
            <a href="{{ route('superadmin.admins') }}" class="menu-item {{ Route::is('superadmin.admins') ? 'active' : '' }}">
                <i class="ph ph-users"></i> Manage Admins
            </a>
            <a href="{{ route('superadmin.payments') }}" class="menu-item {{ Route::is('superadmin.payments') ? 'active' : '' }}">
                <i class="ph ph-wallet"></i> Payment Details
            </a>
            <a href="{{ route('superadmin.reports') }}" class="menu-item {{ Route::is('superadmin.reports') ? 'active' : '' }}">
                <i class="ph ph-chart-bar"></i> Reports
            </a>
            <a href="{{ route('superadmin.settings') }}" class="menu-item {{ Route::is('superadmin.settings') ? 'active' : '' }}">
                <i class="ph ph-gear"></i> System Settings
            </a>
            <a href="{{ route('home') }}" class="menu-item" target="_blank" rel="noopener noreferrer">
                <i class="ph ph-globe"></i> Visit Site
            </a>
        </nav>
        <div class="sidebar-footer">
            <form action="{{ route('superadmin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="ph-bold ph-sign-out"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Main -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <button id="sidebarToggle" style="width: 40px; height: 40px; padding: 0; align-items: center; justify-content: center; border-radius: 8px; border: 1px solid var(--border); background: white; color: var(--text-main); cursor: pointer; box-shadow: none; flex-shrink: 0;">
                    <i class="ph ph-list" style="font-size: 1.35rem;"></i>
                </button>
                <div class="topbar-title" style="display: inline-flex; align-items: center; gap: 6px;">
                    System Overview
                    <span style="width: 6px; height: 6px; background: var(--primary-color, #850f0f); border-radius: 50%; display: inline-block;"></span>
                </div>
            </div>
            <div class="topbar-right">
                <div title="Current Theme Color" style="
                    width: 14px; height: 14px;
                    border-radius: 50%;
                    background: var(--primary-color, var(--primary-color));
                    border: 2px solid rgba(255,255,255,0.4);
                    box-shadow: 0 0 0 2px var(--primary-color, var(--primary-color));
                    flex-shrink: 0;
                "></div>
                <div style="font-size: 0.8rem; color: var(--text-muted);">{{ now()->format('d M Y, H:i') }}</div>
                <div class="admin-profile-wrap">
                    <button class="admin-profile-btn" id="adminProfileBtn" aria-label="Account menu">
                        <i class="ph-fill ph-user"></i>
                    </button>
                    <div class="admin-profile-menu" id="adminProfileMenu">
                        <form class="admin-logout-form" action="{{ route('superadmin.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="admin-logout-btn"><i class="ph-bold ph-sign-out"></i> Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-body">


            <!-- Stat Cards Row (SuperAdmin System-Level Metrics) -->
            <div class="stats-grid">
                <!-- Card 1: Total System Revenue -->
                <div class="stat-card-colored card-bg-blue">
                    <div class="card-icon-badge">
                        <i class="ph-bold ph-currency-inr"></i>
                    </div>
                    <div class="card-content">
                        <span class="card-label">Total System Revenue</span>
                        <div class="card-value">₹{{ number_format($totalRevenue) }}</div>
                    </div>
                </div>

                <!-- Card 2: Monthly Revenue & Growth -->
                <div class="stat-card-colored card-bg-navy">
                    <div class="card-icon-badge">
                        <i class="ph-bold ph-trend-up"></i>
                    </div>
                    <div class="card-content">
                        <span class="card-label">This Month Revenue</span>
                        <div class="card-value">₹{{ number_format($monthRevenue) }}</div>
                        @if($revenueGrowth !== null)
                            <span class="card-subtext" style="color: {{ $revenueGrowth >= 0 ? '#bbf7d0' : '#fecdd3' }}; font-weight: 700;">
                                {{ $revenueGrowth >= 0 ? '+' : '' }}{{ $revenueGrowth }}% vs last month
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Card 3: Total System Bookings -->
                <div class="stat-card-colored card-bg-orange">
                    <div class="card-icon-badge">
                        <i class="ph-bold ph-calendar-check"></i>
                    </div>
                    <div class="card-content">
                        <span class="card-label">Total System Bookings</span>
                        <div class="card-value">{{ $totalSystemBookings }}</div>
                    </div>
                </div>

                <!-- Card 4: Admin Accounts -->
                <div class="stat-card-colored card-bg-sky">
                    <div class="card-icon-badge">
                        <i class="ph-bold ph-user-gear"></i>
                    </div>
                    <div class="card-content">
                        <span class="card-label">Admin Accounts</span>
                        <div class="card-value">{{ $totalAdmins }}</div>
                    </div>
                </div>

                <!-- Card 5: System Users & Guests -->
                <div class="stat-card-colored card-bg-green">
                    <div class="card-icon-badge">
                        <i class="ph-bold ph-users"></i>
                    </div>
                    <div class="card-content">
                        <span class="card-label">Registered Guests/Users</span>
                        <div class="card-value">{{ $totalUsers }}</div>
                    </div>
                </div>

                <!-- Card 6: Paid & Settled Bookings -->
                <div class="stat-card-colored card-bg-purple">
                    <div class="card-icon-badge">
                        <i class="ph-bold ph-check-circle"></i>
                    </div>
                    <div class="card-content">
                        <span class="card-label">Paid & Settled Bookings</span>
                        <div class="card-value">{{ $paidBookings }}</div>
                    </div>
                </div>

                <!-- Card 7: Rejected Bookings (Audit) -->
                <div class="stat-card-colored card-bg-pink">
                    <div class="card-icon-badge">
                        <i class="ph-bold ph-x-circle"></i>
                    </div>
                    <div class="card-content">
                        <span class="card-label">Rejected / Cancelled</span>
                        <div class="card-value">{{ $rejectedBookings }}</div>
                    </div>
                </div>

                <!-- Card 8: Pending Counter Payments -->
                <div class="stat-card-colored card-bg-red">
                    <div class="card-icon-badge">
                        <i class="ph-bold ph-clock"></i>
                    </div>
                    <div class="card-content">
                        <span class="card-label">Pending Counter Payments</span>
                        <div class="card-value">{{ $pendingPayments }}</div>
                    </div>
                </div>
            </div>

            <!-- Row: Revenue Chart + Alerts -->
            <div class="row-2" style="margin-bottom: 1.5rem;">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="ph-bold ph-chart-line-up" style="color: var(--primary);"></i>
                            Revenue Trend (Last 6 Months)
                        </div>
                    </div>
                    <div class="chart-wrap">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="ph-bold ph-warning-circle" style="color: var(--warning);"></i>
                            System Alerts
                        </div>
                    </div>
                    @foreach($alerts as $alert)
                        <div class="alert-item alert-{{ $alert['type'] }}">
                            <i class="ph ph-{{ $alert['type'] === 'success' ? 'check-circle' : ($alert['type'] === 'warning' ? 'warning' : 'info') }}" style="font-size: 1rem; flex-shrink:0; margin-top:1px;"></i>
                            {{ $alert['msg'] }}
                        </div>
                    @endforeach

                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border);">
                        <div style="font-size: 0.75rem; font-weight: 700; color: var(--muted); margin-bottom: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Booking Status Summary</div>
                        @php $total = max($totalSystemBookings, 1); @endphp
                        @foreach([
                            ['label' => 'Approved', 'count' => $approvedBookings, 'color' => '#22c55e'],
                            ['label' => 'Pending',  'count' => $pendingApprovals, 'color' => '#f59e0b'],
                            ['label' => 'Rejected', 'count' => $rejectedBookings, 'color' => '#ef4444'],
                        ] as $row)
                        <div style="margin-bottom: 0.6rem;">
                            <div style="display:flex; justify-content:space-between; font-size:0.78rem; margin-bottom:3px;">
                                <span style="font-weight:600;">{{ $row['label'] }}</span>
                                <span style="color:var(--muted);">{{ $row['count'] }} / {{ $total }}</span>
                            </div>
                            <div class="progress-bg">
                                <div class="progress-fill" style="width: {{ round(($row['count']/$total)*100) }}%; background: {{ $row['color'] }};"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Row: Top Rooms + Booking Status -->
            <div class="row-2-equal">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="ph-bold ph-buildings" style="color: var(--primary);"></i>
                            Top Rooms by Bookings
                        </div>
                    </div>
                    <div style="overflow-x: auto;">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Room</th>
                                    <th>Bookings</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topRooms as $i => $room)
                                <tr>
                                    <td style="color: var(--muted);">{{ $i + 1 }}</td>
                                    <td style="font-weight: 600;">{{ $room->room_name }}</td>
                                    <td>{{ $room->total }}</td>
                                    <td style="font-weight: 700; color: var(--primary);">₹{{ number_format($room->revenue) }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="4" style="text-align:center; padding:1.5rem; color:var(--muted);">No data yet</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="ph-bold ph-plugs-connected" style="color: var(--primary-color)"></i>
                            Payment Status Breakdown
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                        @foreach([
                            ['label' => 'Paid',     'val' => $paidBookings,     'icon' => 'check-circle', 'color' => '#22c55e', 'bg' => '#f0fdf4'],
                            ['label' => 'Pending Payment', 'val' => $pendingPayments,  'icon' => 'clock', 'color' => '#f59e0b', 'bg' => '#fffbeb'],
                            ['label' => 'Approved', 'val' => $approvedBookings, 'icon' => 'check-square', 'color' => '#3b82f6', 'bg' => '#eff6ff'],
                            ['label' => 'Rejected', 'val' => $rejectedBookings, 'icon' => 'x-circle',  'color' => '#ef4444', 'bg' => '#fef2f2'],
                        ] as $s)
                        <div style="background: {{ $s['bg'] }}; border-radius: 10px; padding: 1rem; text-align: center;">
                            <i class="ph ph-{{ $s['icon'] }}" style="font-size: 1.5rem; color: {{ $s['color'] }};"></i>
                            <div style="font-size: 1.4rem; font-weight: 800; color: var(--text); margin-top: 0.25rem;">{{ $s['val'] }}</div>
                            <div style="font-size: 0.65rem; font-weight: 700; color: var(--muted); text-transform: uppercase;">{{ $s['label'] }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Recent Bookings (Full Width) -->
            <div class="card" style="margin-bottom: 2rem;">
                <div class="card-header">
                    <div class="card-title">
                        <i class="ph-bold ph-clock-counter-clockwise" style="color: var(--primary);"></i>
                        Recent Bookings
                    </div>
                    <a href="{{ route('admin.bookings') }}" style="font-size: 0.78rem; color: var(--primary); text-decoration: none; font-weight: 700;">View All ?</a>
                </div>
                <div style="overflow-x: auto;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Guest Name</th>
                                <th>Room</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Approval</th>
                                <th>Payment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentBookings as $b)
                            <tr>
                                <td style="font-weight: 600;">{{ $b->name }}</td>
                                <td>{{ $b->room_name }}</td>
                                <td style="color: var(--muted);">{{ \Carbon\Carbon::parse($b->booking_date)->format('d M Y') }}</td>
                                <td style="font-weight: 700;">₹{{ number_format($b->total_price, 2) }}</td>
                                <td>
                                    <span class="pill pill-{{ strtolower(str_replace(' ', '-', $b->approval_status)) }}
                                        {{ $b->approval_status === 'Approved' ? 'pill-approved' : ($b->approval_status === 'Rejected' ? 'pill-rejected' : 'pill-pending') }}">
                                        {{ $b->approval_status }}
                                    </span>
                                </td>
                                <td>
                                    <span class="pill {{ $b->payment_status === 'Paid' ? 'pill-paid' : 'pill-pending' }}">
                                        {{ $b->payment_status }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" style="text-align:center; padding: 2rem; color: var(--muted);">No bookings yet</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Profile Dropdown Toggle
        const adminProfileBtn = document.getElementById('adminProfileBtn');
        const adminProfileMenu = document.getElementById('adminProfileMenu');
        if (adminProfileBtn) {
            adminProfileBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                adminProfileMenu.classList.toggle('open');
            });
        }

        // Sidebar Toggle
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('.sidebar');
        
        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                sidebar.classList.toggle('open');
            });
        }

        document.addEventListener('click', (event) => {
            if (adminProfileMenu) adminProfileMenu.classList.remove('open');
            if (window.innerWidth <= 1024 && sidebar && sidebar.classList.contains('open')) {
                const isClickInsideSidebar = sidebar.contains(event.target);
                const isClickOnToggle = sidebarToggle && sidebarToggle.contains(event.target);
                
                if (!isClickInsideSidebar && !isClickOnToggle) {
                    sidebar.classList.remove('open');
                }
            }
        });

        // Revenue Chart
        const ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($monthlyRevenue->pluck('month')->map(fn($m) => \Carbon\Carbon::parse($m)->format('M Y'))) !!},
                datasets: [{
                    label: 'Revenue (₹)',
                    data: {!! json_encode($monthlyRevenue->pluck('revenue')) !!},
                    backgroundColor: 'rgba(59, 130, 246, 0.75)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1,
                    borderRadius: 6,
                    borderSkipped: false,
                }, {
                    label: 'Bookings',
                    data: {!! json_encode($monthlyRevenue->pluck('count')) !!},
                    type: 'line',
                    borderColor: '#f97316',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    pointBackgroundColor: '#f97316',
                    pointRadius: 4,
                    yAxisID: 'y2',
                    tension: 0.4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true, position: 'top', labels: { font: { size: 11 }, boxWidth: 12 } }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: { font: { size: 10 }, callback: v => '₹' + v.toLocaleString() }
                    },
                    y2: {
                        beginAtZero: true,
                        position: 'right',
                        grid: { display: false },
                        ticks: { font: { size: 10 } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10 } }
                    }
                }
            }
        });
    </script>
</body>
</html>

