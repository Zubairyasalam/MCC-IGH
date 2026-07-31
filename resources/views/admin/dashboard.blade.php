<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Admin Dashboard - Space Booking</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <style>
        :root {
            --sidebar-width: 240px;
            --bg-color: #f8fafc;
            --primary-color: #850f0f;
            --border: #e2e8f0;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --success: #22c55e;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
            --card-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        }

        * {
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-color);
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            font-family: 'Inter', sans-serif;
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: #ffffff;
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 100;
            transition: transform 0.3s ease;
        }

        .sidebar.open {
            transform: translateX(0) !important;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border);
        }

        .sidebar-logo {
            font-weight: 800;
            color: var(--text-main);
            font-size: 1.15rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .sidebar-logo img { height: 80px; width: auto; object-fit: contain; }

        .sidebar-menu {
            padding: 1rem 0.75rem; flex: 1;
            display: flex; flex-direction: column;
        }

        .menu-item {
            display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem;
            color: #64748b; text-decoration: none; border-radius: 8px; font-weight: 500;
            transition: all 0.2s ease; margin-bottom: 0.25rem;
        }

        .menu-item:hover {
            background: rgba(133, 15, 15, 0.08); color: var(--primary-color);
        }

        .menu-item.active {
            background: rgba(133, 15, 15, 0.1);
            color: var(--primary-color);
            font-weight: 600;
            border-left: 3px solid var(--primary-color);
            padding-left: calc(1rem - 3px);
        }

        .sidebar-footer { padding: 1rem; border-top: 1px solid var(--border); }

        /* Main Content */
        .admin-main {
            margin-left: 240px;
            width: calc(100vw - 240px - 17px); /* 17px scrollbar */
            max-width: calc(100vw - 240px);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            box-sizing: border-box;
            overflow-x: hidden;
        }

        .top-navbar {
            height: 72px; background: white; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between; padding: 0 2rem;
            position: sticky; top: 0; z-index: 90;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        }

        .nav-right { display: flex; align-items: center; gap: 1.25rem; }

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

        .notification-bell {
            position: relative; font-size: 1.25rem; color: #64748b; cursor: pointer;
            display: flex; align-items: center;
        }

        .notification-badge {
            position: absolute; top: -3px; right: -5px; background: var(--danger); color: white;
            font-size: 0.65rem; min-width: 18px; height: 18px; border-radius: 10px; display: flex;
            align-items: center; justify-content: center; border: 2px solid white; font-weight: 800;
            padding: 0 4px;
        }

        .notification-dropdown {
            position: absolute; top: 100%; right: 0; width: 320px; background: white;
            border-radius: 12px; box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1); border: 1px solid var(--border);
            display: none; z-index: 1000; margin-top: 10px; overflow: hidden;
        }

        .notification-dropdown.active { display: block; }

        .notification-header {
            padding: 1rem; border-bottom: 1px solid var(--border); background: #f8fafc;
            display: flex; justify-content: space-between; align-items: center;
        }

        .notification-item {
            padding: 1rem; border-bottom: 1px solid var(--border); text-decoration: none;
            display: block; transition: background 0.2s;
        }
        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); width: var(--sidebar-width) !important; }
            .sidebar { transform: translateX(-100%); transition: transform 0.3s ease; }
            .sidebar.open { transform: translateX(0) !important; }
            .admin-main { margin-left: 0 !important; width: 100% !important; }
            .top-navbar { padding: 0 1rem !important; height: 60px !important; }
            .admin-body { padding: 1rem 1rem 1rem 1rem !important; }
            .stats-grid { grid-template-columns: 1fr 1fr !important; gap: 0.75rem !important; }
            .row-grid { grid-template-columns: 1fr !important; gap: 0.75rem !important; margin-bottom: 0.75rem !important; }
            .right-box { flex-direction: row !important; gap: 0.75rem !important; }
            .right-box .dashboard-section { flex: 1 !important; }
            .quick-actions { grid-template-columns: repeat(4, 1fr) !important; gap: 0.5rem !important; }
            .menu-toggle { display: flex !important; }
            .topbar-title { font-size: 1rem !important; }
            .chart-container { height: 180px !important; }
        }

        @media (max-width: 640px) {
            .stats-grid { grid-template-columns: 1fr 1fr !important; gap: 0.6rem !important; }
            .stat-card { padding: 0.85rem 1rem !important; }
            .stat-value { font-size: 1.35rem !important; }
            .stat-icon { width: 32px !important; height: 32px !important; font-size: 1rem !important; }
            .nav-right .user-info div:first-child { display: none !important; }
            .admin-body { padding: 0.75rem !important; }
            .row-grid { grid-template-columns: 1fr !important; }
            .right-box { flex-direction: column !important; }
            .mini-table th, .mini-table td { padding: 0.5rem 0.4rem !important; font-size: 0.72rem !important; }
            .chart-container { height: 160px !important; }
            .dashboard-section { padding: 1rem !important; }
            .section-header h3 { font-size: 0.85rem !important; }
            .quick-actions { grid-template-columns: repeat(4, 1fr) !important; gap: 0.4rem !important; }
            .action-btn { padding: 0.5rem 0.25rem !important; font-size: 0.65rem !important; }
            .action-btn i { font-size: 1rem !important; }
        }

        .notification-item:hover { background: #f8fafc; }

        .notification-item .title { font-weight: 600; font-size: 0.85rem; color: #1e293b; margin-bottom: 0.25rem; }
        .notification-item .desc { font-size: 0.75rem; color: #64748b; }
        .notification-item .time { font-size: 0.7rem; color: #94a3b8; margin-top: 0.25rem; }

        .admin-body {
            padding: 1.5rem 2rem 2rem 1.5rem; 
            max-width: 100%;
            width: 100%;
            margin: 0;
            box-sizing: border-box;
            overflow-x: hidden;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); 
            gap: 1rem; 
            margin-bottom: 2rem;
            width: 100%;
            max-width: 100%;
        }

        .stat-card {
            background: white; 
            padding: 1.5rem; 
            border-radius: 16px; 
            border: 1px solid var(--border);
            display: flex; 
            flex-direction: column; 
            gap: 0.5rem; 
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05), 0 2px 4px -2px rgb(0 0 0 / 0.05);
            min-width: 0;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        }

        .stat-header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 0.25rem;
            gap: 0.5rem;
        }

        .stat-icon {
            width: 40px; 
            height: 40px; 
            border-radius: 10px; 
            display: flex; 
            align-items: center;
            justify-content: center; 
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .icon-blue { background: #eff6ff; color: #3b82f6; }
        .icon-orange { background: #fff7ed; color: #f97316; }
        .icon-green { background: #f0fdf4; color: #22c55e; }
        .icon-purple { background: #faf5ff; color: #a855f7; }
        .icon-red { background: #fef2f2; color: #ef4444; }

        .stat-value { 
            font-size: 1.75rem; 
            font-weight: 700; 
            color: #1e293b;
            line-height: 1.2;
            margin-top: auto;
        }

        .stat-label { 
            font-size: 0.7rem; 
            color: #64748b; 
            font-weight: 700; 
            text-transform: uppercase; 
            letter-spacing: 0.05em;
            line-height: 1.2;
            word-wrap: break-word;
            display: block;
        }

        /* Generic Section Card */
        .dashboard-section {
            background: white; 
            border-radius: 16px; 
            border: 1px solid var(--border); 
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05); 
            box-sizing: border-box;
        }

        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; }

        .section-header h3 {
            font-size: 1rem; font-weight: 600; color: #1e293b; display: flex; align-items: center;
            gap: 0.5rem; margin: 0;
        }

        /* Custom Grids */
        .row-grid {
            display: grid; 
            grid-template-columns: 2fr 1fr; 
            gap: 1.25rem; 
            margin-bottom: 1.25rem; 
            align-items: start;
            min-width: 0;
        }

        /* All direct children of the grid must not overflow their column */
        .row-grid > * {
            min-width: 0;
            overflow: hidden;
        }

        .right-box, .right-box > * {
            min-width: 0;
            max-width: 100%;
        }

        /* Chart Area */
        .chart-container { height: 240px; position: relative; width: 100%; }

        .chart-controls { display: flex; background: #f1f5f9; padding: 0.2rem; border-radius: 6px; }

        .chart-toggle {
            padding: 0.3rem 0.6rem; font-size: 0.75rem; font-weight: 600; border: none;
            background: transparent; color: #64748b; cursor: pointer; border-radius: 4px; transition: all 0.2s;
        }

        .chart-toggle.active { background: white; color: var(--primary-color); box-shadow: 0 1px 2px rgba(0,0,0,0.05); }

        /* Quick Actions & Calendar */
        .right-box { display: flex; flex-direction: column; gap: 1.25rem; }

        .quick-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }

        .action-btn {
            display: flex; flex-direction: column; align-items: center; padding: 0.75rem;
            background: #f8fafc; border: 1px solid var(--border); border-radius: 8px; gap: 0.4rem;
            text-decoration: none; color: #334155; font-size: 0.75rem; font-weight: 600; transition: all 0.2s;
        }

        .action-btn:hover { background: white; border-color: var(--primary-color); color: var(--primary-color); transform: translateY(-1px); }

        .action-btn i { font-size: 1.25rem; }

        /* Calendar */
        .mini-calendar { 
            display: grid; 
            grid-template-columns: repeat(7, minmax(0, 1fr)); 
            gap: 2px; 
            text-align: center; 
            margin-top: 0.5rem;
            width: 100%;
        }

        .cal-day-name { 
            font-size: 0.65rem; 
            font-weight: 700; 
            color: #94a3b8; 
            padding-bottom: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cal-day { 
            font-size: 0.75rem; 
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px; 
            color: #64748b; 
            transition: all 0.2s;
        }

        .cal-day.active { 
            background: var(--primary-color); 
            color: white; 
            font-weight: 700; 
            box-shadow: 0 2px 4px rgba(133, 15, 15, 0.3);
        }

        .cal-month-year {
            font-size: 0.8rem;
            font-weight: 700;
            color: #475569;
        }

        /* Tables */
        .mini-table { width: 100%; border-collapse: collapse; }

        .mini-table th, .mini-table td { padding: 0.75rem; text-align: left; border-bottom: 1px solid var(--border); }

        .mini-table th { font-size: 0.7rem; text-transform: uppercase; color: #64748b; font-weight: 700; background: #f8fafc; }

        .mini-table td { font-size: 0.8rem; color: #334155; }

        .status-pill { padding: 0.15rem 0.5rem; border-radius: 999px; font-size: 0.65rem; font-weight: 700; display: inline-block; }

        .pill-paid { background: #dcfce7; color: #15803d; }
        .pill-pending { background: #fef9c3; color: #a16207; }
        .pill-failed { background: #fee2e2; color: #b91c1c; }

        /* Space Usage */
        .usage-item { margin-bottom: 0.75rem; }
        .usage-info { display: flex; justify-content: space-between; font-size: 0.8rem; margin-bottom: 0.25rem; }
        .usage-bar-bg { height: 6px; background: #f1f5f9; border-radius: 3px; overflow: hidden; }
        .usage-bar-fill { height: 100%; background: var(--primary-color); }

    </style>
    @include('partials.dynamic-styles')
</head>
<body class="admin-page">
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo"><img src="/assets/logo.png" alt="MCC-MRF Logo" style="height:80px; width:auto; object-fit:contain;"></div>
        </div>
        <div class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}" class="menu-item active">
                <i class="ph ph-squares-four"></i> Dashboard
            </a>
            <a href="{{ route('admin.bookings') }}" class="menu-item">
                <i class="ph ph-calendar-check"></i> Bookings
            </a>
            <a href="{{ route('admin.college-guest') }}" class="menu-item">
                <i class="ph ph-user-gear"></i> College Guests
            </a>
            <a href="{{ route('admin.reports') }}" class="menu-item">
                <i class="ph ph-file-text"></i> Reports
            </a>
            <a href="{{ route('home') }}" class="menu-item" target="_blank" rel="noopener noreferrer">
                <i class="ph ph-globe"></i> Visit Website
            </a>
            <div style="margin-top: auto; padding: 1.5rem; border-top: 1px solid rgba(0,0,0,0.05);">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" style="width: 100%; display: flex; align-items: center; gap: 0.75rem; background: none; border: none; padding: 0.75rem 1rem; color: #ef4444; cursor: pointer; font-weight: 600; border-radius: 8px; transition: background 0.2s;">
                        <i class="ph-bold ph-sign-out" style="font-size: 1.25rem;"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <main class="admin-main">
        <div class="top-navbar">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <button id="sidebarToggle" class="menu-toggle" style="display: none; background: #fff; border: 1px solid var(--border); border-radius: 8px; width: 40px; height: 40px; align-items: center; justify-content: center; color: var(--text-main); cursor: pointer; font-size: 1.25rem;">
                    <i class="ph ph-list"></i>
                </button>
                <div class="topbar-title" style="font-weight: 700; font-size: 1.15rem; color: var(--text-main); display: inline-flex; align-items: center; gap: 6px;">
                    Dashboard Overview
                    <span style="width: 6px; height: 6px; background: var(--primary-color); border-radius: 50%; display: inline-block;"></span>
                </div>
            </div>
            <div class="nav-right">
                <div title="Current Theme Color" style="
                    width: 14px; height: 14px;
                    border-radius: 50%;
                    background: var(--primary-color);
                    border: 2px solid rgba(255,255,255,0.4);
                    box-shadow: 0 0 0 2px var(--primary-color);
                    flex-shrink: 0;
                "></div>
                <div class="notification-bell" id="notifToggle">
                    <i class="ph ph-bell"></i>
                    @if($notificationBookings->count() > 0)
                        <div class="notification-badge">{{ $notificationBookings->count() }}</div>
                    @endif
                    <div class="notification-dropdown" id="notifDropdown">
                        <div class="notification-header">
                            <span style="font-weight: 700; font-size: 0.9rem;">Notifications</span>
                            <form action="{{ route('admin.notifications.read') }}" method="POST" id="markReadForm">
                                @csrf
                                <span style="font-size: 0.7rem; color: var(--primary-color); cursor: pointer;" onclick="document.getElementById('markReadForm').submit();">Mark all as read</span>
                            </form>
                        </div>
                        <div style="max-height: 350px; overflow-y: auto;">
                            @forelse($notificationBookings as $nb)
                                <a href="{{ route('admin.bookings.show', $nb->id) }}" class="notification-item">
                                    <div class="title">
                                        {{ $nb->approval_status === 'Pending' ? 'New Booking Request' : 'Approved by Principal' }}
                                    </div>
                                    <div class="desc">
                                        {{ $nb->name }} booked {{ $nb->room_name }}
                                        @if($nb->approval_status === 'Principal Approved' || $nb->approval_status === 'Approved by Principal')
                                            - <span style="color: var(--success); font-weight: 600;">Requires Final Action</span>
                                        @endif
                                    </div>
                                    <div class="time">{{ $nb->updated_at->diffForHumans() }}</div>
                                </a>
                            @empty
                                <div style="padding: 2rem; text-align: center; color: #94a3b8; font-size: 0.85rem;">
                                    <i class="ph ph-bell-slash" style="font-size: 2rem; display: block; margin-bottom: 0.5rem; opacity: 0.3;"></i>
                                    No new notifications
                                </div>
                            @endforelse
                        </div>
                        <a href="{{ route('admin.bookings') }}" style="display: block; padding: 0.75rem; text-align: center; font-size: 0.8rem; font-weight: 600; color: var(--primary-color); border-top: 1px solid var(--border); background: #f8fafc; text-decoration: none;">View All Bookings</a>
                    </div>
                </div>
                <div class="admin-profile-wrap">
                    <button class="admin-profile-btn" id="adminProfileBtn" aria-label="Account menu">
                        <i class="ph-fill ph-user"></i>
                    </button>
                    <div class="admin-profile-menu" id="adminProfileMenu">
                        <form class="admin-logout-form" action="{{ route('admin.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="admin-logout-btn"><i class="ph-bold ph-sign-out"></i> Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-body">
            <!-- Row 1: Summary Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-label">Total Rev</span>
                        <div class="stat-icon icon-green"><i class="ph ph-currency-inr"></i></div>
                    </div>
                    <div class="stat-value">₹{{ number_format($totalRevenue) }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-label">Today's Rev</span>
                        <div class="stat-icon icon-orange"><i class="ph ph-coins"></i></div>
                    </div>
                    <div class="stat-value">₹{{ number_format($todayRevenue) }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-label">Completed</span>
                        <div class="stat-icon icon-blue"><i class="ph ph-check-circle"></i></div>
                    </div>
                    <div class="stat-value">{{ $completedBookings }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-label">Pending Approval</span>
                        <div class="stat-icon icon-purple"><i class="ph ph-hand-pointing"></i></div>
                    </div>
                    <div class="stat-value">{{ $pendingApprovals }}</div>
                </div>
                <div class="stat-card" style="border-left: 4px solid #3b82f6;">
                    <div class="stat-header">
                        <span class="stat-label">Approved by Principal</span>
                        <div class="stat-icon icon-blue"><i class="ph ph-check-square"></i></div>
                    </div>
                    <div class="stat-value">{{ $principalApprovals }}</div>
                    <span style="font-size: 0.6rem; color: #64748b;">Requires final Admin action</span>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-label">Pending Payment</span>
                        <div class="stat-icon icon-orange"><i class="ph ph-clock"></i></div>
                    </div>
                    <div class="stat-value">{{ $pendingPayments }}</div>
                </div>
            </div>

            <!-- Row 2: Revenue Chart + Quick/Calendar -->
            <div class="row-grid">
                <div class="dashboard-section">
                    <div class="section-header">
                        <h3><i class="ph-bold ph-chart-line-up" style="color: var(--primary-color);"></i> Revenue Growth</h3>
                        <div class="chart-controls">
                            <button class="chart-toggle active" onclick="switchChart('7day', this)">7D</button>
                            <button class="chart-toggle" onclick="switchChart('monthly', this)">6M</button>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <div class="right-box">
                    <div class="dashboard-section" style="padding: 1rem;">
                        <div class="section-header" style="margin-bottom: 0.75rem;">
                            <h3><i class="ph-bold ph-lightning" style="color: var(--warning);"></i> Quick Actions</h3>
                        </div>
                        <div class="quick-actions">
                            <a href="{{ route('home') }}" class="action-btn">
                                <i class="ph ph-plus-circle" style="color: var(--success);"></i>
                                New
                            </a>
                            <a href="{{ route('admin.bookings') }}" class="action-btn">
                                <i class="ph ph-file-search" style="color: var(--info);"></i>
                                Reports
                            </a>
                            <a href="{{ route('admin.bookings.export') }}" class="action-btn">
                                <i class="ph ph-export" style="color: var(--primary-color);"></i>
                                CSV
                            </a>
                            <a href="#" class="action-btn">
                                <i class="ph ph-users" style="color: #a855f7;"></i>
                                Admins
                            </a>
                        </div>
                    </div>

                    <div class="dashboard-section" style="padding: 1.25rem 1rem;">
                        <div class="section-header" style="margin-bottom: 1rem;">
                            <h3 style="margin:0;"><i class="ph-bold ph-calendar-blank" style="color: var(--primary-color);"></i> Calendar</h3>
                            <div class="cal-month-year">{{ \Carbon\Carbon::now()->format('F Y') }}</div>
                        </div>
                        <div class="mini-calendar">
                            <div class="cal-day-name">S</div><div class="cal-day-name">M</div><div class="cal-day-name">T</div>
                            <div class="cal-day-name">W</div><div class="cal-day-name">T</div><div class="cal-day-name">F</div><div class="cal-day-name">S</div>
                            @php
                                $startOfMonth = \Carbon\Carbon::now()->startOfMonth();
                                $daysInMonth = \Carbon\Carbon::now()->daysInMonth;
                                $dayOfWeek = $startOfMonth->dayOfWeek;
                                $today = \Carbon\Carbon::now()->day;
                            @endphp
                            @for($i = 0; $i < $dayOfWeek; $i++) <div></div> @endfor
                            @for($d = 1; $d <= $daysInMonth; $d++)
                                <div class="cal-day {{ $d == $today ? 'active' : '' }}">
                                    {{ $d }}
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row 3: Upcoming + Usage/Insights -->
            <div class="row-grid">
                <div class="dashboard-section">
                    <div class="section-header">
                        <h3><i class="ph-bold ph-calendar-check" style="color: var(--info);"></i> Upcoming Reservations</h3>
                        <a href="{{ route('admin.bookings') }}" style="font-size: 0.75rem; color: var(--primary-color); text-decoration: none; font-weight: 600;">View Full</a>
                    </div>
                    <div style="overflow-x: auto; padding: 0 1px;">
                        <table class="mini-table">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Workspace</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($upcomingBookings as $booking)
                                <tr>
                                    <td style="font-weight: 600;">{{ Str::limit($booking->name, 15) }}</td>
                                    <td>{{ $booking->room_name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M') }}, {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}</td>
                                    <td><span class="status-pill pill-paid">Paid</span></td>
                                </tr>
                                @empty
                                <tr><td colspan="4" style="text-align:center; padding: 1.5rem;">No upcoming bookings</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="right-box">
                    <div class="dashboard-section" style="padding: 1rem;">
                        <div class="section-header">
                            <h3><i class="ph-bold ph-chart-pie" style="color: var(--success);"></i> Space Usage</h3>
                        </div>
                        @foreach($workspaceData as $workspace)
                        <div class="usage-item">
                            <div class="usage-info">
                                <span style="font-weight: 600; font-size: 0.75rem;">{{ $workspace->room_name }}</span>
                                <span style="font-size: 0.7rem; color: #64748b;">{{ $workspace->usage_percentage }}%</span>
                            </div>
                            <div class="usage-bar-bg">
                                <div class="usage-bar-fill" style="width: {{ $workspace->usage_percentage }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="dashboard-section" style="padding: 1rem; background: #fff; border-left: 4px solid var(--primary-color);">
                        <div class="section-header" style="margin-bottom: 0.5rem;">
                            <h3 style="font-size: 0.85rem;"><i class="ph ph-info" style="color: var(--primary-color);"></i> Insights</h3>
                        </div>
                        <ul style="padding-left: 1rem; font-size: 0.75rem; color: #475569; line-height: 1.4; margin: 0;">
                            @foreach($insights as $insight)
                                <li style="margin-bottom: 0.35rem;">{{ $insight }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Row 4: Recent Bookings Table (Full Width) -->
            <div class="dashboard-section" style="margin-bottom: 1rem;">
                <div class="section-header">
                    <h3><i class="ph-bold ph-clock-counter-clockwise" style="color: var(--primary-color);"></i> Recent Bookings</h3>
                    <div style="display: flex; gap: 0.5rem;">
                        <a href="{{ route('admin.bookings.export') }}" class="btn btn-outline" style="padding:0.35rem 0.75rem; font-size:0.7rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.3rem;">
                            <i class="ph ph-download"></i> CSV
                        </a>
                        <a href="{{ route('admin.bookings') }}" style="font-size: 0.8rem; color: var(--primary-color); text-decoration: none; font-weight: 600;">View All</a>
                    </div>
                </div>
                <div style="overflow-x: auto; padding: 0 1px;">
                    <table class="mini-table">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Workspace</th>
                                <th>Time Slot</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentBookings as $booking)
                            <tr>
                                <td style="font-weight: 600;">{{ $booking->name }}</td>
                                <td>{{ $booking->room_name }}</td>
                                <td style="color: #64748b;">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M') }} | {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}</td>
                                <td style="font-weight: 700;">₹{{ number_format($booking->total_price, 2) }}</td>
                                <td>
                                    <span class="status-pill pill-{{ strtolower($booking->payment_status) }}">
                                        {{ $booking->payment_status }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>


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
        document.addEventListener('click', () => {
            if (adminProfileMenu) adminProfileMenu.classList.remove('open');
        });

        // Notification Toggle
        const notifToggle = document.getElementById('notifToggle');
        const notifDropdown = document.getElementById('notifDropdown');

        if (notifToggle) {
            notifToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                notifDropdown.classList.toggle('active');
            });
        }

        document.addEventListener('click', (event) => {
            if (notifDropdown) notifDropdown.classList.remove('active');
        });

        let revenueChart;
        const dailyData = {
            labels: {!! json_encode($dailyRevenue->pluck('date')->map(function($date) { return \Carbon\Carbon::parse($date)->format('d M'); })) !!},
            values: {!! json_encode($dailyRevenue->pluck('revenue')) !!}
        };
        const monthlyData = {
            labels: {!! json_encode($monthlyRevenue->pluck('month')->map(function($m) { return \Carbon\Carbon::parse($m)->format('M Y'); })) !!},
            values: {!! json_encode($monthlyRevenue->pluck('revenue')) !!}
        };

        document.addEventListener('DOMContentLoaded', function() {
            initChart(dailyData.labels, dailyData.values);
        });

        function initChart(labels, data) {
            const ctx = document.getElementById('revenueChart').getContext('2d');
            revenueChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Revenue (₹)',
                        data: data,
                        borderColor: window.primaryColor,
                        backgroundColor: `rgba(${window.primaryColorRGB}, 0.05)`,
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: window.primaryColor,
                        pointRadius: 3,
                        pointHoverRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            grid: { color: '#f1f5f9', drawBorder: false }, 
                            ticks: { 
                                font: { size: 10 },
                                callback: v => '?' + v 
                            } 
                        },
                        x: { 
                            grid: { display: false },
                            ticks: { font: { size: 10 } }
                        }
                    }
                }
            });
        }

        // Sidebar Toggle
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('.sidebar');
        
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                sidebar.classList.toggle('open');
            });
        }

        document.addEventListener('click', (event) => {
            if (window.innerWidth <= 1024 && sidebar && sidebar.classList.contains('open')) {
                const isClickInsideSidebar = sidebar.contains(event.target);
                const isClickOnToggle = sidebarToggle && sidebarToggle.contains(event.target);
                
                if (!isClickInsideSidebar && !isClickOnToggle) {
                    sidebar.classList.remove('open');
                }
            }
        });

        function switchChart(type, btn) {
            document.querySelectorAll('.chart-toggle').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            if (type === '7day') {
                revenueChart.data.labels = dailyData.labels;
                revenueChart.data.datasets[0].data = dailyData.values;
            } else {
                revenueChart.data.labels = monthlyData.labels;
                revenueChart.data.datasets[0].data = monthlyData.values;
            }
            revenueChart.update();
        }
        // -- Layout Fix: force admin-main to never exceed viewport minus sidebar --
        (function fixAdminLayout() {
            const SIDEBAR_W = 240;
            const adminMain = document.querySelector('.admin-main');
            if (!adminMain) return;

            function applyWidth() {
                const vw = window.innerWidth;
                if (vw > 1024) {
                    adminMain.style.setProperty('width', (vw - SIDEBAR_W) + 'px', 'important');
                    adminMain.style.setProperty('max-width', (vw - SIDEBAR_W) + 'px', 'important');
                    adminMain.style.setProperty('margin-left', SIDEBAR_W + 'px', 'important');
                    adminMain.style.setProperty('overflow-x', 'hidden', 'important');
                } else {
                    adminMain.style.setProperty('width', '100%', 'important');
                    adminMain.style.setProperty('max-width', 'none', 'important');
                    adminMain.style.setProperty('margin-left', '0', 'important');
                }
            }

            applyWidth();
            window.addEventListener('resize', applyWidth);
        })();
    </script>
</body>
</html>

