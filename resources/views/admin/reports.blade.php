<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Reports - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <style>
        :root {
            --sidebar-width: 240px;
            --bg-color: #f8fafc;
            --primary-color: #850f0f; /* Fallback */
            --border: #e2e8f0;
            --text-main: #1e293b;
            --text-muted: #64748b;
        }

        body {
            background-color: var(--bg-color);
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            font-family: 'Inter', sans-serif;
        }

        * {
            box-sizing: border-box;
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
            padding: 1rem 0.75rem;
            flex: 1;
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
            transition: all 0.2s ease;
            margin-bottom: 0.25rem;
        }

        .menu-item:hover {
            background: rgba(133, 15, 15, 0.08);
            color: var(--primary-color);
        }

        .menu-item.active {
            background: rgba(133, 15, 15, 0.1);
            color: var(--primary-color);
            font-weight: 600;
            border-left: 3px solid var(--primary-color);
            padding-left: calc(1rem - 3px);
        }

        .menu-item i {
            font-size: 1.25rem;
        }

        /* Main Content */
        .admin-main {
            margin-left: var(--sidebar-width);
            display: flex;
            flex-direction: column;
            width: calc(100% - var(--sidebar-width));
            min-width: 0;
            transition: all 0.3s ease;
        }

        .top-navbar {
            height: 72px; background: white; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between; padding: 0 2rem;
            position: sticky; top: 0; z-index: 90;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        }

        .admin-body {
            padding: 2.5rem; padding-bottom: 1.5rem; max-width: 1600px; width: 100%; margin: 0 auto; box-sizing: border-box;
        }

        /* Report Controls */
        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .filter-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            border: 1px solid var(--border);
            margin-bottom: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .filter-form {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: flex-end;
        }

        .filter-form .form-group {
            flex: 1;
            min-width: 150px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-label {
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
        }

        .form-input {
            padding: 0.6rem 1rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-family: inherit;
            color: var(--text-main);
            outline: none;
            transition: border 0.2s;
        }

        .form-input:focus {
            border-color: var(--primary-color);
        }

        .btn-download {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            text-decoration: none;
            transition: transform 0.2s;
        }

        .btn-download:hover {
            transform: translateY(-2px);
            filter: brightness(1.1);
        }

        /* Table Styles */
        .report-table-container {
            background: white;
            border-radius: 12px;
            border: 1px solid var(--border);
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        table {
            width: 100%;
            min-width: 700px;
            border-collapse: collapse;
            text-align: left;
        }

        th {
            background: #f8fafc;
            padding: 1rem;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            border-bottom: 1px solid var(--border);
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid var(--border);
            font-size: 0.875rem;
        }

        .status-pill {
            padding: 0.25rem 0.75rem;
            border-radius: 99px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .pill-pending { background: #fff7ed; color: #c2410c; }
        .pill-approved { background: #f0fdf4; color: #15803d; }
        .pill-rejected { background: #fef2f2; color: #b91c1c; }

        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s ease; }
            .sidebar.open { transform: translateX(0) !important; }
            .admin-main { margin-left: 0 !important; width: 100% !important; }
            .top-navbar { padding: 0 1rem !important; height: 60px !important; }
            .admin-body { padding: 1.25rem !important; padding-bottom: 1rem !important; }
            #sidebarToggle { display: flex !important; }
            .filter-form { flex-direction: column !important; }
            .filter-form .form-group { min-width: 100% !important; }
        }

        @media (max-width: 640px) {
            .admin-body { padding: 0.75rem !important; }
            .filter-form { flex-direction: column !important; gap: 0.75rem !important; }
            .filter-form .form-group { min-width: 100% !important; }
            .filter-card { padding: 1rem !important; margin-bottom: 1rem !important; }
            th { padding: 0.6rem 0.75rem !important; font-size: 0.65rem !important; }
            td { padding: 0.6rem 0.75rem !important; font-size: 0.78rem !important; }
            /* Stats summary cards: 1 column on mobile */
            .summary-cards-grid { grid-template-columns: 1fr !important; gap: 0.75rem !important; }
            .report-table-container { border-radius: 8px !important; }
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
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo"><img src="/assets/logo.png" alt="MCC-MRF Logo" style="height:80px; width:auto; object-fit:contain;"></div>
        </div>
        <div class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}" class="menu-item">
                <i class="ph ph-squares-four"></i> Dashboard
            </a>
            <a href="{{ route('admin.bookings') }}" class="menu-item">
                <i class="ph ph-calendar-check"></i> Bookings
            </a>
            <a href="{{ route('admin.college-guest') }}" class="menu-item">
                <i class="ph ph-user-gear"></i> College Guests
            </a>
            <a href="{{ route('admin.reports') }}" class="menu-item active">
                <i class="ph ph-file-text"></i> Reports
            </a>
            <a href="{{ route('home') }}" class="menu-item" target="_blank">
                <i class="ph ph-globe"></i> Visit Website
            </a>
            <div style="margin-top: auto; padding: 1.5rem; border-top: 1px solid rgba(0,0,0,0.05);">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" style="width: 100%; display: flex; align-items: center; gap: 0.75rem; background: none; border: none; padding: 0.75rem 1rem; color: #ef4444; cursor: pointer; font-weight: 600; border-radius: 8px;">
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
                <button id="sidebarToggle" style="display: none; background: #fff; border: 1px solid var(--border); border-radius: 8px; width: 40px; height: 40px; align-items: center; justify-content: center; color: var(--text-main); cursor: pointer; font-size: 1.25rem;">
                    <i class="ph ph-list"></i>
                </button>
                <div style="font-weight: 700; font-size: 1.1rem; color: var(--text-main);">Booking Reports</div>
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

        <div class="admin-body">
            <!-- Financial Summary Cards -->
            <div class="summary-cards-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
                <div style="background: white; padding: 1.25rem; border-radius: 12px; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <div style="color: var(--text-muted); font-size: 0.7rem; font-weight: 700; text-transform: uppercase; margin-bottom: 0.4rem;">Total Revenue</div>
                    <div style="font-size: 1.5rem; font-weight: 800; color: var(--primary-color);">?{{ number_format($totalRevenue, 2) }}</div>
                </div>
                <div style="background: white; padding: 1.25rem; border-radius: 12px; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <div style="color: var(--text-muted); font-size: 0.7rem; font-weight: 700; text-transform: uppercase; margin-bottom: 0.4rem;">Net Revenue (Excl. GST)</div>
                    <div style="font-size: 1.5rem; font-weight: 800; color: #1e293b;">?{{ number_format($netRevenue, 2) }}</div>
                </div>
                <div style="background: white; padding: 1.25rem; border-radius: 12px; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <div style="color: var(--text-muted); font-size: 0.7rem; font-weight: 700; text-transform: uppercase; margin-bottom: 0.4rem;">Total GST Collected ({{ $gstRate }}%)</div>
                    <div style="font-size: 1.5rem; font-weight: 800; color: #64748b;">?{{ number_format($totalGst, 2) }}</div>
                </div>
            </div>

            <div class="filter-card">
                <form action="{{ route('admin.reports') }}" method="GET" class="filter-form">
                    <div class="form-group">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-input" value="{{ request('start_date') }}" style="height: 48px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" class="form-input" value="{{ request('end_date') }}" style="height: 48px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn-download" style="background: var(--text-main); height: 48px;">
                            <i class="ph ph-funnel"></i> Apply Filter
                        </button>
                    </div>
                    @if(count($bookings) > 0)
                    <div class="form-group">
                        <label class="form-label">&nbsp;</label>
                        <a href="{{ route('admin.reports.download', request()->all()) }}" class="btn-download" style="height: 48px;">
                            <i class="ph ph-file-pdf"></i> Download PDF Report
                        </a>
                    </div>
                    @endif
                </form>
            </div>

            <div class="report-table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Guest Details</th>
                            <th>Room / Slot</th>
                            <th>Base Price</th>
                            <th>GST ({{ $gstRate }}%)</th>
                            <th>Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $b)
                        @php
                            $bGstFactor = 1 + ($gstRate / 100);
                            $bSubtotal = $b->total_price / $bGstFactor;
                            $bGstAmount = $b->total_price - $bSubtotal;
                        @endphp
                        <tr>
                            <td style="font-weight: 600;">BK-{{ str_pad($b->id, 6, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <div style="font-weight: 600;">{{ $b->name }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $b->email }}</div>
                            </td>
                            <td>
                                <div style="font-weight: 600;">{{ str_replace('-', ' ', ucwords($b->room_name, '- ')) }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">{{ \Carbon\Carbon::parse($b->booking_date)->format('d M Y') }}</div>
                            </td>
                            <td style="color: #64748b;">?{{ number_format($bSubtotal, 2) }}</td>
                            <td style="color: #64748b;">?{{ number_format($bGstAmount, 2) }}</td>
                            <td style="font-weight: 700; color: var(--text-main);">?{{ number_format($b->total_price, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 4rem; color: var(--text-muted);">
                                No records found for the selected period.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <script>
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('.sidebar');

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
        if (sidebarToggle) {
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
