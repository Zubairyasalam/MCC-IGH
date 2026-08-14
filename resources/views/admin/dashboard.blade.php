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
            height: 56px; background: white; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between; padding: 0 1.5rem;
            position: sticky; top: 0; z-index: 90;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        }

        .nav-right { display: flex; align-items: center; gap: 0.85rem; }

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
            padding: 1.25rem 1.5rem; 
            max-width: 1400px;
            width: 100%;
            margin: 0 auto;
            box-sizing: border-box;
            overflow-x: hidden;
        }

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

        /* Smart Availability & Color Coded Calendar */
        .cal-day { 
            font-size: 0.78rem; 
            height: 34px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: 8px; 
            color: #475569; 
            transition: all 0.2s ease;
            position: relative;
            cursor: pointer;
            user-select: none;
            border: 1px solid transparent;
            font-weight: 600;
        }

        .cal-day.status-available { 
            background: #f8fafc; 
            color: #334155; 
        }

        .cal-day.status-available:hover {
            background: #f0fdf4;
            color: #166534;
            border-color: #bbf7d0;
        }

        .cal-day.status-partial {
            background: #fffbe6;
            color: #b45309;
            border: 1.5px solid #fde68a;
            font-weight: 700;
        }

        .cal-day.status-partial:hover {
            background: #fef3c7;
            transform: translateY(-1px);
        }

        .cal-day.status-heavy {
            background: #fef2f2;
            color: #991b1b;
            border: 1.5px solid #fca5a5;
            font-weight: 800;
        }

        .cal-day.status-heavy:hover {
            background: #fee2e2;
            transform: translateY(-1px);
        }

        .cal-day.is-today { 
            outline: 2px solid var(--primary-color, #850f0f); 
            outline-offset: -2px; 
            box-shadow: 0 2px 8px rgba(133, 15, 15, 0.2);
            font-weight: 800;
        }

        .cal-booking-badge {
            position: absolute;
            top: 2px;
            right: 2px;
            color: white;
            font-size: 0.55rem;
            font-weight: 800;
            width: 15px;
            height: 15px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
            pointer-events: none;
        }

        .cal-booking-badge.badge-partial {
            background: #d97706;
        }

        .cal-booking-badge.badge-heavy {
            background: #dc2626;
        }

        .cal-available-dot {
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: #22c55e;
            position: absolute;
            bottom: 3px;
        }

        /* Full Width Detailed Master Calendar Styling */
        .detailed-calendar-section {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid var(--border, #e2e8f0);
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        }

        .detailed-calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 8px;
            width: 100%;
        }

        .cal-day-header {
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            text-align: center;
            padding: 8px 0;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .cal-cell {
            min-height: 98px;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 8px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }

        .cal-cell.empty-cell {
            background: #f8fafc;
            border-color: #f1f5f9;
            cursor: default;
            min-height: 98px;
            opacity: 0.4;
        }

        .cal-cell.cell-available {
            background: #ffffff;
            border-color: #e2e8f0;
        }

        .cal-cell.cell-available:hover {
            border-color: #22c55e;
            background: #f0fdf4;
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.12);
            transform: translateY(-2px);
        }

        .cal-cell.cell-partial {
            background: #fffdf5;
            border-color: #fde68a;
        }

        .cal-cell.cell-partial:hover {
            border-color: #d97706;
            background: #fffbe6;
            box-shadow: 0 4px 12px rgba(217, 119, 6, 0.12);
            transform: translateY(-2px);
        }

        .cal-cell.cell-heavy {
            background: #fff5f5;
            border-color: #fca5a5;
        }

        .cal-cell.cell-heavy:hover {
            border-color: #dc2626;
            background: #fef2f2;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.12);
            transform: translateY(-2px);
        }

        .cal-cell.cell-today {
            outline: 2.5px solid var(--primary-color, #850f0f);
            outline-offset: -2.5px;
            box-shadow: 0 4px 15px rgba(133, 15, 15, 0.2);
        }

        .cell-top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
        }

        .cell-date-num {
            font-size: 0.95rem;
            font-weight: 800;
            color: #0f172a;
        }

        .cell-count-badge {
            font-size: 0.65rem;
            font-weight: 800;
            padding: 2px 7px;
            border-radius: 20px;
            color: white;
            line-height: 1;
        }

        .cell-count-badge.badge-amber {
            background: #d97706;
        }

        .cell-count-badge.badge-red {
            background: #dc2626;
        }

        .cell-free-tag {
            font-size: 0.65rem;
            font-weight: 700;
            color: #166534;
            background: #dcfce7;
            padding: 2px 6px;
            border-radius: 6px;
        }

        .cell-bookings-preview {
            display: flex;
            flex-direction: column;
            gap: 4px;
            margin-top: 4px;
        }

        .cell-no-bookings {
            font-size: 0.68rem;
            color: #94a3b8;
            font-weight: 600;
            padding-top: 4px;
        }

        .booking-chip {
            font-size: 0.68rem;
            font-weight: 700;
            padding: 3px 6px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .booking-chip.chip-paid {
            background: #e0f2fe;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }

        .booking-chip.chip-pending {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .chip-room {
            font-weight: 800;
        }

        .chip-name {
            font-weight: 500;
            opacity: 0.85;
        }

        .chip-more {
            font-size: 0.65rem;
            font-weight: 800;
            color: #64748b;
            padding-top: 2px;
        }

        @media (max-width: 1024px) {
            .detailed-calendar-grid {
                grid-template-columns: repeat(7, minmax(110px, 1fr));
                overflow-x: auto;
            }
        }

        #calendarDayModal.active {
            display: flex !important;
            opacity: 1 !important;
        }

        #calendarDayModal.active > div {
            transform: scale(1) !important;
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
            <div class="sidebar-logo"><img src="/assets/logo_transparent.png" alt="MCC-MRF Logo" style="height:80px; width:auto; object-fit:contain;"></div>
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
            <!-- Welcome Header -->
            <div style="margin-bottom: 1.25rem;">
                <h2 style="font-size: 1.35rem; font-weight: 800; color: #0f172a; margin: 0 0 4px 0;">
                    Welcome back, {{ Auth::user()->name ?? 'MCC-MRF Admin' }}!
                </h2>
                <span style="font-size: 0.82rem; color: #64748b; font-weight: 500;">
                    Live room reservations, revenue stats, and facility occupancy overview.
                </span>
            </div>

            <!-- Row 1: Real Room Booking Summary Stat Cards Grid -->
            <div class="stats-grid">
                <!-- Card 1: Lifetime Revenue -->
                <div class="stat-card-colored card-bg-blue">
                    <div class="card-icon-badge">
                        <i class="ph-bold ph-currency-inr"></i>
                    </div>
                    <div class="card-content">
                        <span class="card-label">Lifetime Earnings</span>
                        <div class="card-value">₹{{ number_format($totalRevenue) }}</div>
                    </div>
                </div>

                <!-- Card 2: Today's Revenue -->
                <div class="stat-card-colored card-bg-navy">
                    <div class="card-icon-badge">
                        <i class="ph-bold ph-coins"></i>
                    </div>
                    <div class="card-content">
                        <span class="card-label">Today's Revenue</span>
                        <div class="card-value">₹{{ number_format($todayRevenue) }}</div>
                    </div>
                </div>

                <!-- Card 3: Total Paid Bookings -->
                <div class="stat-card-colored card-bg-orange">
                    <div class="card-icon-badge">
                        <i class="ph-bold ph-calendar-check"></i>
                    </div>
                    <div class="card-content">
                        <span class="card-label">Total Bookings</span>
                        <div class="card-value">{{ $totalBookings }}</div>
                    </div>
                </div>

                <!-- Card 4: Today's Bookings -->
                <div class="stat-card-colored card-bg-sky">
                    <div class="card-icon-badge">
                        <i class="ph-bold ph-calendar"></i>
                    </div>
                    <div class="card-content">
                        <span class="card-label">Today's Bookings</span>
                        <div class="card-value">{{ $todayBookings }}</div>
                    </div>
                </div>

                <!-- Card 5: Approved Bookings -->
                <div class="stat-card-colored card-bg-green">
                    <div class="card-icon-badge">
                        <i class="ph-bold ph-check-circle"></i>
                    </div>
                    <div class="card-content">
                        <span class="card-label">Completed Bookings</span>
                        <div class="card-value">{{ $completedBookings }}</div>
                    </div>
                </div>

                <!-- Card 6: Pending Approvals -->
                <div class="stat-card-colored card-bg-purple">
                    <div class="card-icon-badge">
                        <i class="ph-bold ph-hand-pointing"></i>
                    </div>
                    <div class="card-content">
                        <span class="card-label">Pending Approval</span>
                        <div class="card-value">{{ $pendingApprovals }}</div>
                    </div>
                </div>

                <!-- Card 7: Principal Approved -->
                <div class="stat-card-colored card-bg-pink">
                    <div class="card-icon-badge">
                        <i class="ph-bold ph-check-square"></i>
                    </div>
                    <div class="card-content">
                        <span class="card-label">Principal Approved</span>
                        <div class="card-value">{{ $principalApprovals }}</div>
                        <span class="card-subtext">Requires final action</span>
                    </div>
                </div>

                <!-- Card 8: Pending Payments -->
                <div class="stat-card-colored card-bg-red">
                    <div class="card-icon-badge">
                        <i class="ph-bold ph-clock"></i>
                    </div>
                    <div class="card-content">
                        <span class="card-label">Pending Payments</span>
                        <div class="card-value">{{ $pendingPayments }}</div>
                    </div>
                </div>
            </div>

            <!-- Live Room & Space Availability Center -->
            <div class="dashboard-section" style="margin-bottom: 1.5rem; padding: 1.5rem; background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
                <div class="section-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 12px;">
                    <div>
                        <h3 style="margin:0; font-size: 1.15rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                            <i class="ph-bold ph-bed" style="color: var(--primary-color);"></i>
                            Live Room & Space Availability Center
                        </h3>
                        <span style="font-size: 0.78rem; color: #64748b; margin-top: 2px; display: block;">Real-time room availability, reservations & occupancy status for today ({{ \Carbon\Carbon::today()->format('d M Y') }})</span>
                    </div>

                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="padding: 6px 14px; background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; font-size: 0.75rem; font-weight: 800; border-radius: 8px; display: inline-flex; align-items: center; gap: 6px;">
                            <i class="ph-bold ph-check-circle" style="color: #22c55e;"></i> {{ $totalAvailableRooms }} Available
                        </span>
                        <span style="padding: 6px 14px; background: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; font-size: 0.75rem; font-weight: 800; border-radius: 8px; display: inline-flex; align-items: center; gap: 6px;">
                            <i class="ph-bold ph-x-circle" style="color: #ef4444;"></i> {{ $totalReservedRooms }} Reserved
                        </span>
                    </div>
                </div>

                @foreach($roomAvailabilityStatus as $categoryName => $rooms)
                <div style="margin-bottom: 1.25rem;">
                    <div style="font-size: 0.82rem; font-weight: 800; color: #334155; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.6rem; display: flex; align-items: center; gap: 6px;">
                        @if(str_contains(strtolower($categoryName), 'standard'))
                            <i class="ph-bold ph-door" style="color: var(--primary-color);"></i> {{ $categoryName }} ({{ count($rooms) }} Rooms)
                        @elseif(str_contains(strtolower($categoryName), 'advance') || str_contains(strtolower($categoryName), 'executive'))
                            <i class="ph-bold ph-star" style="color: #d97706;"></i> {{ $categoryName }} ({{ count($rooms) }} Rooms)
                        @else
                            <i class="ph-bold ph-buildings" style="color: #2563eb;"></i> {{ $categoryName }} ({{ count($rooms) }} Facilities)
                        @endif
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(185px, 1fr)); gap: 10px;">
                        @foreach($rooms as $r)
                            @if($r['is_available'])
                                <div style="background: #f0fdf4; border: 1.5px solid #bbf7d0; padding: 10px 12px; border-radius: 10px; transition: all 0.2s ease;">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <span style="font-weight: 800; font-size: 0.82rem; color: #166534;">{{ $r['name'] }}</span>
                                        <span style="padding: 2px 6px; background: #22c55e; color: white; font-size: 0.62rem; font-weight: 800; border-radius: 4px;">FREE</span>
                                    </div>
                                    <div style="font-size: 0.7rem; color: #15803d; margin-top: 4px; display: flex; align-items: center; gap: 4px;">
                                        <i class="ph-bold ph-check-circle"></i> Available
                                    </div>
                                </div>
                            @else
                                <div style="background: #fef2f2; border: 1.5px solid #fca5a5; padding: 10px 12px; border-radius: 10px; transition: all 0.2s ease;">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <span style="font-weight: 800; font-size: 0.82rem; color: #991b1b;">{{ $r['name'] }}</span>
                                        <span style="padding: 2px 6px; background: #ef4444; color: white; font-size: 0.62rem; font-weight: 800; border-radius: 4px;">RESERVED</span>
                                    </div>
                                    <div style="font-size: 0.72rem; font-weight: 800; color: #7f1d1d; margin-top: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $r['guest_name'] }}">
                                        <i class="ph-bold ph-user"></i> {{ $r['guest_name'] }}
                                    </div>
                                    <div style="font-size: 0.68rem; color: #991b1b; margin-top: 2px; display: flex; align-items: center; justify-content: space-between;">
                                        <span><i class="ph-bold ph-clock"></i> {{ $r['time'] }}</span>
                                        <a href="{{ route('admin.bookings.show', $r['booking_id']) }}" style="font-weight: 700; color: var(--primary-color, #850f0f); text-decoration: underline;">View</a>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Full Width Detailed Master Calendar -->
            <div id="detailedCalendarSectionContainer" class="dashboard-section detailed-calendar-section" style="margin-bottom: 1.5rem; padding: 1.5rem; transition: opacity 0.2s ease;">
                @include('admin.partials.calendar-section')
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
                                callback: v => '₹' + v.toLocaleString() 
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

    <!-- Admin Calendar Day Details Modal -->
    <div id="calendarDayModal" class="modal-overlay" onclick="if(event.target === this) closeCalendarDayModal()" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(4px); display: none; align-items: center; justify-content: center; z-index: 10000; opacity: 0; transition: opacity 0.25s ease;">
        <div style="background: #ffffff; width: 92%; max-width: 580px; border-radius: 20px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.3); overflow: hidden; transform: scale(0.95); transition: transform 0.25s ease; border: 1px solid var(--border);">
            <div style="padding: 1.25rem 1.5rem; background: #ffffff; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                    <i class="ph-bold ph-calendar-check" style="color: var(--primary-color);"></i>
                    <span id="calModalDateTitle">Bookings Details</span>
                </h3>
                <button type="button" onclick="closeCalendarDayModal()" style="background: rgba(100, 116, 139, 0.08); border: none; font-size: 1.2rem; cursor: pointer; color: #64748b; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="ph-bold ph-x"></i>
                </button>
            </div>
            <div id="calModalBody" style="padding: 1.5rem; max-height: 65vh; overflow-y: auto;">
                <!-- Dynamic day bookings list -->
            </div>
            <div style="padding: 1rem 1.5rem; background: #f8fafc; border-top: 1px solid #f1f5f9; text-align: right;">
                <button type="button" onclick="closeCalendarDayModal()" style="padding: 8px 20px; font-size: 0.85rem; font-weight: 700; border-radius: 10px; border: 1.5px solid #cbd5e1; background: #ffffff; color: #334155; cursor: pointer;">
                    Close
                </button>
            </div>
        </div>
    </div>

    <script>
        let calendarData = @json($calendarBookings ?? []);

        function fetchCalendarAjax(url) {
            const container = document.getElementById('detailedCalendarSectionContainer');
            if (container) {
                container.style.opacity = '0.4';
                container.style.pointerEvents = 'none';
            }

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.html) {
                    if (container) {
                        container.innerHTML = data.html;
                        container.style.opacity = '1';
                        container.style.pointerEvents = 'auto';
                    }
                    if (data.calendarBookings) {
                        calendarData = data.calendarBookings;
                    }
                    window.history.pushState(null, '', url);
                } else {
                    window.location.href = url;
                }
            })
            .catch(err => {
                console.error('AJAX calendar error:', err);
                window.location.href = url;
            });
        }

        document.addEventListener('click', function(e) {
            const link = e.target.closest('a.ajax-cal-link');
            if (link) {
                e.preventDefault();
                fetchCalendarAjax(link.href);
            }
        });

        document.addEventListener('change', function(e) {
            if (e.target.matches('.ajax-cal-select')) {
                const form = e.target.closest('form#ajaxCalendarForm');
                if (form) {
                    form.submit();
                }
            }
        });

        document.addEventListener('submit', function(e) {
            if (e.target.matches('form#ajaxCalendarForm')) {
                // Allow standard GET form submission to ensure live deployment compatibility
                return true;
            }
        });

        window.openCalendarDayModal = function (dayNum, dateFormattedStr, dateIso) {
            const modal = document.getElementById('calendarDayModal');
            const titleEl = document.getElementById('calModalDateTitle');
            const bodyEl = document.getElementById('calModalBody');
            if (!modal || !titleEl || !bodyEl) return;

            titleEl.textContent = `Room Reservations & Occupancy on ${dateFormattedStr}`;
            const dayBookings = calendarData[dayNum] || [];
            const TOTAL_ROOMS = 26;
            const bookedCount = dayBookings.length;
            const availableCount = Math.max(0, TOTAL_ROOMS - bookedCount);

            // Compute category breakdown for this date
            let stdCount = 0;
            let advCount = 0;
            let confCount = 0;
            dayBookings.forEach((b) => {
                const rNameLower = (b.room_name || '').toLowerCase();
                if (rNameLower.includes('standard')) {
                    stdCount++;
                } else if (rNameLower.includes('advance') || rNameLower.includes('executive') || rNameLower.includes('deluxe') || rNameLower.includes('suite')) {
                    advCount++;
                } else {
                    confCount++;
                }
            });

            let statusBannerHtml = `
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 1.1rem; margin-bottom: 1.25rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem; flex-wrap: wrap; gap: 8px;">
                        <div>
                            <span style="font-size: 0.72rem; font-weight: 800; text-transform: uppercase; color: #64748b; letter-spacing: 0.05em; display: block;">Daily Occupancy Summary</span>
                            <span style="font-size: 1.05rem; font-weight: 800; color: #0f172a;">
                                ${bookedCount} Reserved • <span style="color: #166534;">${availableCount} Available</span>
                            </span>
                        </div>
                        <a href="{{ route('admin.college-guest') }}?date=${dateIso || ''}" style="padding: 7px 14px; font-size: 0.8rem; font-weight: 700; background: var(--primary-color, #850f0f); color: white; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: gap: 4px;">
                            <i class="ph-bold ph-plus"></i> New Reservation
                        </a>
                    </div>
                    
                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                        <span style="padding: 4px 10px; font-size: 0.72rem; font-weight: 800; border-radius: 6px; background: #fff5f5; border: 1px solid #fecdd3; color: #850f0f; display: inline-flex; align-items: center; gap: 4px;">
                            <i class="ph-bold ph-bed"></i> Standard: ${stdCount} / 20 Reserved
                        </span>
                        <span style="padding: 4px 10px; font-size: 0.72rem; font-weight: 800; border-radius: 6px; background: #fffbeb; border: 1px solid #fef3c7; color: #b45309; display: inline-flex; align-items: center; gap: 4px;">
                            <i class="ph-bold ph-star"></i> Advance Exec: ${advCount} / 4 Reserved
                        </span>
                        <span style="padding: 4px 10px; font-size: 0.72rem; font-weight: 800; border-radius: 6px; background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; display: inline-flex; align-items: center; gap: 4px;">
                            <i class="ph-bold ph-buildings"></i> Conference: ${confCount} / 3 Reserved
                        </span>
                    </div>
                </div>
            `;

            if (dayBookings.length === 0) {
                bodyEl.innerHTML = statusBannerHtml + `
                    <div style="text-align: center; padding: 2rem 1rem; color: #64748b; background: #f0fdf4; border-radius: 12px; border: 1px dashed #bbf7d0;">
                        <i class="ph-bold ph-check-circle" style="font-size: 2.5rem; color: #22c55e; margin-bottom: 0.5rem; display: block;"></i>
                        <p style="font-weight: 700; margin-bottom: 0.25rem; font-size: 1rem; color: #166534;">All 26 Rooms Completely Available</p>
                        <p style="font-size: 0.82rem; color: #15803d; margin-bottom: 1rem;">No reservations scheduled for ${dateFormattedStr}. You can reserve any Standard Room, Deluxe Room, or Hall.</p>
                        <a href="{{ route('admin.college-guest') }}?date=${dateIso || ''}" style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 18px; font-size: 0.85rem; font-weight: 700; border-radius: 8px; background: var(--primary-color, #850f0f); color: white; text-decoration: none;">
                            <i class="ph-bold ph-plus-circle"></i> Reserve Room for this Date
                        </a>
                    </div>
                `;
            } else {
                let html = statusBannerHtml + '<h4 style="margin: 0 0 0.85rem 0; font-size: 0.85rem; font-weight: 800; text-transform: uppercase; color: #64748b; letter-spacing: 0.05em;">Booked Rooms & Guest Details</h4><div style="display: flex; flex-direction: column; gap: 14px;">';
                dayBookings.forEach((b) => {
                    const statusPillClass = b.payment_status === 'Paid' ? 'pill-paid' : (b.payment_status === 'Pending' ? 'pill-pending' : 'pill-failed');
                    html += `
                        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 1.1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.04); transition: all 0.2s;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.75rem; border-bottom: 1px dashed #f1f5f9; padding-bottom: 0.6rem; gap: 10px;">
                                <div>
                                    <span style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: var(--primary-color, #850f0f); background: rgba(133, 15, 15, 0.08); padding: 2px 8px; border-radius: 6px;">
                                        Booking #${b.id}
                                    </span>
                                    <h4 style="margin: 0.35rem 0 0 0; font-size: 1.05rem; font-weight: 800; color: #0f172a;">${b.name}</h4>
                                    <span style="font-size: 0.8rem; color: #64748b;">${b.user_type} • ${b.email}</span>
                                </div>
                                <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 4px;">
                                    <span class="status-pill ${statusPillClass}" style="white-space: nowrap;">${b.approval_status}</span>
                                    <span style="font-size: 0.72rem; font-weight: 700; color: ${b.payment_status === 'Paid' ? '#166534' : '#b45309'};">Payment: ${b.payment_status}</span>
                                </div>
                            </div>
                            
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 10px; margin-bottom: 0.85rem; font-size: 0.82rem; color: #334155;">
                                <div>
                                    <strong style="color: #64748b; font-size: 0.72rem; text-transform: uppercase; display: block;">Room / Workspace</strong>
                                    <span style="font-weight: 700; color: #0f172a;"><i class="ph-bold ph-bed" style="color: var(--primary-color);"></i> ${b.room_name}</span>
                                </div>
                                <div>
                                    <strong style="color: #64748b; font-size: 0.72rem; text-transform: uppercase; display: block;">Guests & Tariff</strong>
                                    <span style="font-weight: 700; color: #0f172a;"><i class="ph-bold ph-users"></i> ${b.no_of_persons} Guests • ₹${b.total_price}</span>
                                </div>
                                <div style="grid-column: 1 / -1;">
                                    <strong style="color: #64748b; font-size: 0.72rem; text-transform: uppercase; display: block;">Stay Duration (Clock In &rarr; Clock Out)</strong>
                                    <span style="font-weight: 600; color: #475569;"><i class="ph-bold ph-clock" style="color: var(--primary-color);"></i> ${b.clock_in} &rarr; ${b.clock_out}</span>
                                </div>
                            </div>

                            <div style="display: flex; justify-content: flex-end; padding-top: 0.5rem; border-top: 1px solid #f8fafc;">
                                <a href="${b.details_url}" style="font-size: 0.82rem; font-weight: 800; color: var(--primary-color, #850f0f); text-decoration: none; display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: rgba(133, 15, 15, 0.06); border-radius: 8px;">
                                    View Full Details <i class="ph-bold ph-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    `;
                });
                html += '</div>';
                bodyEl.innerHTML = html;
            }

            modal.classList.add('active');
            modal.style.display = 'flex';
            setTimeout(() => { modal.style.opacity = '1'; }, 10);
        };

        window.closeCalendarDayModal = function () {
            const modal = document.getElementById('calendarDayModal');
            if (modal) {
                modal.classList.remove('active');
                modal.style.opacity = '0';
                setTimeout(() => { modal.style.display = 'none'; }, 200);
            }
        };
    </script>
</body>
</html>

