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
            <div class="sidebar-logo"><img src="/assets/logo_transparent.png" alt="MCC-MRF Logo" style="height:80px; width:auto; object-fit:contain;"></div>
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
            <a href="{{ route('admin.settings') }}" class="menu-item">
                <i class="ph ph-credit-card"></i> Payment Settings
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
            @if(session('success'))
                <div style="background: #f0fdf4; border: 1.5px solid #bbf7d0; color: #15803d; padding: 12px 16px; border-radius: 10px; margin-bottom: 1.25rem; font-weight: 700; font-size: 0.85rem; display: flex; align-items: center; gap: 8px;">
                    <i class="ph-bold ph-check-circle" style="font-size: 1.1rem;"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div style="background: #fef2f2; border: 1.5px solid #fca5a5; color: #991b1b; padding: 12px 16px; border-radius: 10px; margin-bottom: 1.25rem; font-weight: 700; font-size: 0.85rem; display: flex; align-items: center; gap: 8px;">
                    <i class="ph-bold ph-warning-circle" style="font-size: 1.1rem;"></i> {{ session('error') }}
                </div>
            @endif

            <!-- Financial Summary Cards -->
            <div class="summary-cards-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
                <div style="background: white; padding: 1.25rem; border-radius: 12px; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <div style="color: var(--text-muted); font-size: 0.7rem; font-weight: 700; text-transform: uppercase; margin-bottom: 0.4rem;">Total Revenue</div>
                    <div style="font-size: 1.5rem; font-weight: 800; color: var(--primary-color);">₹{{ number_format($totalRevenue, 2) }}</div>
                </div>
                <div style="background: white; padding: 1.25rem; border-radius: 12px; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <div style="color: var(--text-muted); font-size: 0.7rem; font-weight: 700; text-transform: uppercase; margin-bottom: 0.4rem;">Net Revenue (Excl. GST)</div>
                    <div style="font-size: 1.5rem; font-weight: 800; color: #1e293b;">₹{{ number_format($netRevenue, 2) }}</div>
                </div>
                <div style="background: white; padding: 1.25rem; border-radius: 12px; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <div style="color: var(--text-muted); font-size: 0.7rem; font-weight: 700; text-transform: uppercase; margin-bottom: 0.4rem;">Total GST Collected ({{ $gstRate }}%)</div>
                    <div style="font-size: 1.5rem; font-weight: 800; color: #64748b;">₹{{ number_format($totalGst, 2) }}</div>
                </div>
            </div>

            <!-- Quick Stay History Presets -->
            <div style="display: flex; gap: 6px; margin-bottom: 1rem; flex-wrap: wrap; align-items: center;">
                <span style="font-size: 0.75rem; font-weight: 600; color: #64748b; margin-right: 4px;">Stay History Filters:</span>
                <a href="{{ route('admin.reports', ['preset' => '20days']) }}" 
                   style="padding: 5px 12px; font-size: 0.75rem; font-weight: 600; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; transition: all 0.15s; {{ ($preset ?? '') === '20days' ? 'background: var(--primary-color, #850f0f); color: white;' : 'background: white; border: 1px solid #cbd5e1; color: #475569;' }}">
                   <i class="ph-bold ph-calendar-blank"></i> Last 20 Days Stayed
                </a>
                <a href="{{ route('admin.reports', ['preset' => '30days']) }}" 
                   style="padding: 5px 12px; font-size: 0.75rem; font-weight: 600; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; transition: all 0.15s; {{ ($preset ?? '') === '30days' ? 'background: var(--primary-color, #850f0f); color: white;' : 'background: white; border: 1px solid #cbd5e1; color: #475569;' }}">
                   <i class="ph-bold ph-calendar"></i> Last 30 Days
                </a>
                <a href="{{ route('admin.reports', ['preset' => 'this_month']) }}" 
                   style="padding: 5px 12px; font-size: 0.75rem; font-weight: 600; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; transition: all 0.15s; {{ ($preset ?? '') === 'this_month' ? 'background: var(--primary-color, #850f0f); color: white;' : 'background: white; border: 1px solid #cbd5e1; color: #475569;' }}">
                   <i class="ph-bold ph-calendar-check"></i> This Month
                </a>
                <a href="{{ route('admin.reports', ['preset' => 'all']) }}" 
                   style="padding: 5px 12px; font-size: 0.75rem; font-weight: 600; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; transition: all 0.15s; {{ ($preset ?? '') === 'all' ? 'background: var(--primary-color, #850f0f); color: white;' : 'background: white; border: 1px solid #cbd5e1; color: #475569;' }}">
                   <i class="ph-bold ph-clock-counter-clockwise"></i> All Time History
                </a>
            </div>

            <div class="filter-card" style="padding: 0.85rem 1.1rem; background: white; border: 1px solid #e2e8f0; border-radius: 10px; box-shadow: 0 1px 2px rgba(0,0,0,0.02); margin-bottom: 1.25rem; height: auto !important; min-height: 0 !important;">
                <form action="{{ route('admin.reports') }}" method="GET" class="filter-form" style="display: flex; flex-wrap: wrap; gap: 10px; align-items: flex-end; height: auto !important; min-height: 0 !important;">
                    <div style="flex: 0 0 150px; min-width: 120px; margin-bottom: 0;">
                        <label class="form-label" style="font-weight: 600; font-size: 0.68rem; color: #475569; margin-bottom: 3px; display: block; text-transform: uppercase;">Start Date</label>
                        <input type="date" name="start_date" class="form-input" value="{{ request('start_date') }}" style="height: 32px; padding: 0 8px; font-size: 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px; width: 100%;">
                    </div>
                    <div style="flex: 0 0 150px; min-width: 120px; margin-bottom: 0;">
                        <label class="form-label" style="font-weight: 600; font-size: 0.68rem; color: #475569; margin-bottom: 3px; display: block; text-transform: uppercase;">End Date</label>
                        <input type="date" name="end_date" class="form-input" value="{{ request('end_date') }}" style="height: 32px; padding: 0 8px; font-size: 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px; width: 100%;">
                    </div>

                    <div style="display: flex; gap: 6px; flex-wrap: wrap; align-items: center; align-self: flex-end;">
                        <button type="submit" style="height: 32px; padding: 0 12px; font-size: 0.75rem; background: #0f172a; border: none; font-weight: 600; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px; color: white; cursor: pointer; font-family: 'Inter', sans-serif;">
                            <i class="ph ph-funnel"></i> Apply Filter
                        </button>
                        
                        @if(count($bookings) > 0)
                            <a href="{{ route('admin.reports.download', request()->all()) }}" style="height: 32px; padding: 0 12px; font-size: 0.75rem; background: var(--primary-color, #850f0f); font-weight: 600; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; color: white; font-family: 'Inter', sans-serif;">
                                <i class="ph-bold ph-file-pdf"></i> Download PDF
                            </a>
                            <a href="{{ route('admin.reports.export', request()->all()) }}" style="height: 32px; padding: 0 12px; font-size: 0.75rem; background: #166534; border: none; font-weight: 600; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; color: white; font-family: 'Inter', sans-serif;">
                                <i class="ph-bold ph-file-csv"></i> Download CSV
                            </a>
                        @endif
                        
                        <button type="button" onclick="openHistoryImportModal()" style="height: 32px; padding: 0 12px; font-size: 0.75rem; background: #0284c7; border: none; font-weight: 600; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px; color: white; cursor: pointer; font-family: 'Inter', sans-serif;">
                            <i class="ph-bold ph-database"></i> Import History Data
                        </button>
                    </div>
                </form>
            </div>

            <div class="report-table-container" style="border: 1px solid #e2e8f0; border-radius: 10px; overflow: hidden; background: white;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8fafc;">
                            <th style="padding: 9px 12px; font-size: 0.68rem; font-weight: 600; color: #64748b;">BOOKING ID</th>
                            <th style="padding: 9px 12px; font-size: 0.68rem; font-weight: 600; color: #64748b;">GUEST & STAY HISTORY DETAILS</th>
                            <th style="padding: 9px 12px; font-size: 0.68rem; font-weight: 600; color: #64748b;">ROOM / CATEGORY</th>
                            <th style="padding: 9px 12px; font-size: 0.68rem; font-weight: 600; color: #64748b;">TARIFF (BASE + GST)</th>
                            <th style="padding: 9px 12px; font-size: 0.68rem; font-weight: 600; color: #64748b;">APPROVAL & STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $b)
                        @php
                            $bGstFactor = 1 + ($gstRate / 100);
                            $bSubtotal = $b->total_price / $bGstFactor;
                            $bGstAmount = $b->total_price - $bSubtotal;
                        @endphp
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 9px 12px;">
                                <div style="font-weight: 600; color: #0f172a; font-size: 0.82rem;">BK-{{ str_pad($b->id, 6, '0', STR_PAD_LEFT) }}</div>
                                @if($b->reference_id)
                                    <span style="font-size: 0.65rem; font-weight: 700; color: #64748b; background: #f1f5f9; padding: 2px 6px; border-radius: 4px; display: inline-block; margin-top: 2px;">{{ $b->reference_id }}</span>
                                @endif
                            </td>
                            <td>
                                <!-- Booked By & User Category -->
                                <div style="font-weight: 800; color: #0f172a; font-size: 0.9rem; display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                                    {{ $b->name }}
                                    <span style="font-size: 0.65rem; font-weight: 800; text-transform: uppercase; color: #475569; background: #e2e8f0; padding: 2px 6px; border-radius: 4px;">
                                        {{ $b->user_type ?? 'Guest' }}
                                    </span>
                                </div>
                                
                                <div style="font-size: 0.75rem; color: #64748b; margin-top: 2px;">
                                    {{ $b->email }} • {{ $b->phone ?? 'N/A' }}
                                </div>

                                <!-- Guest & Department Details -->
                                <div style="font-size: 0.72rem; color: #334155; margin-top: 4px; display: flex; flex-wrap: wrap; gap: 6px; align-items: center;">
                                    @if($b->primary_guest_name)
                                        <span style="background: #f8fafc; border: 1px solid #cbd5e1; padding: 1px 6px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;" title="Primary Guest Name">
                                            <i class="ph-bold ph-user"></i> <strong>Guest:</strong> {{ $b->primary_guest_name }} ({{ $b->no_of_persons ?? 1 }} Pers.)
                                        </span>
                                    @else
                                        <span style="background: #f8fafc; border: 1px solid #cbd5e1; padding: 1px 6px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;">
                                            <i class="ph-bold ph-users"></i> {{ $b->no_of_persons ?? 1 }} Person(s)
                                        </span>
                                    @endif

                                    @if($b->department)
                                        <span style="background: #f1f5f9; padding: 1px 6px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;" title="Department">
                                            <i class="ph-bold ph-buildings"></i> {{ $b->department }}
                                        </span>
                                    @endif

                                    @if($b->hall_name)
                                        <span style="background: #f1f5f9; padding: 1px 6px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;" title="Hostel Hall">
                                            <i class="ph-bold ph-bank"></i> {{ $b->hall_name }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Attachments -->
                                @if($b->admin_document || $b->referral_attachment)
                                    <div style="margin-top: 4px; display: flex; gap: 6px; align-items: center;">
                                        @if($b->admin_document)
                                            <a href="{{ asset('storage/' . $b->admin_document) }}" target="_blank" style="padding: 2px 8px; background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; border-radius: 4px; font-size: 0.68rem; font-weight: 800; text-decoration: none; display: inline-flex; align-items: center; gap: 3px;" title="View Stored Softcopy">
                                                <i class="ph-bold ph-file-pdf"></i> Softcopy Attached
                                            </a>
                                        @elseif($b->referral_attachment)
                                            <a href="{{ asset('storage/' . $b->referral_attachment) }}" target="_blank" style="padding: 2px 8px; background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; border-radius: 4px; font-size: 0.68rem; font-weight: 800; text-decoration: none; display: inline-flex; align-items: center; gap: 3px;" title="View Referral File">
                                                <i class="ph-bold ph-file-text"></i> Referral File
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div style="font-weight: 700; color: #0f172a;"><i class="ph-bold ph-bed" style="color: var(--primary-color);"></i> {{ str_replace('-', ' ', ucwords($b->room_name, '- ')) }}</div>
                                <div style="font-size: 0.75rem; color: #475569; margin-top: 2px;">
                                    <i class="ph-bold ph-clock"></i> {{ \Carbon\Carbon::parse($b->booking_date)->format('d M Y') }}
                                    @if($b->start_time && $b->end_time)
                                        ({{ \Carbon\Carbon::parse($b->start_time)->format('H:i') }} &rarr; {{ \Carbon\Carbon::parse($b->end_time)->format('H:i') }})
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 800; color: #0f172a;">
                                    ₹{{ number_format($b->total_price, 2) }}
                                    @if(($b->discount_amount ?? 0) > 0)
                                        <span style="font-size: 0.7rem; color: #94a3b8; text-decoration: line-through; font-weight: 500; margin-left: 4px;">₹{{ number_format($b->original_price, 2) }}</span>
                                    @endif
                                </div>
                                <div style="font-size: 0.7rem; color: #64748b;">Base: ₹{{ number_format($bSubtotal, 2) }} | GST: ₹{{ number_format($bGstAmount, 2) }}</div>
                                @if(($b->discount_amount ?? 0) > 0)
                                    <div style="font-size: 0.68rem; color: #166534; font-weight: 700; margin-top: 2px;" title="{{ $b->discount_reason }}">
                                        <i class="ph-bold ph-tag"></i> Offer: -₹{{ number_format($b->discount_amount, 2) }} ({{ Str::limit($b->discount_reason ?: 'Special Offer', 20) }})
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 4px;">
                                    <span class="status-pill {{ str_contains($b->approval_status, 'Approved') || $b->approval_status === 'Approved' ? 'pill-approved' : ($b->approval_status === 'Rejected' ? 'pill-rejected' : 'pill-pending') }}">
                                        {{ $b->approval_status }}
                                    </span>

                                    <!-- Audit Chain Details -->
                                    <div style="font-size: 0.7rem; color: #475569; display: flex; flex-direction: column; gap: 2px; margin-top: 2px;">
                                        @if(str_contains($b->approval_status, 'Approved by Principal') || str_contains($b->approval_status, 'Principal Approved') || $b->approval_status === 'Approved')
                                            <span style="color: #15803d; font-weight: 700;"><i class="ph-bold ph-check-circle"></i> Principal Approved</span>
                                        @elseif($b->approval_status === 'Pending Principal Approval')
                                            <span style="color: #c2410c; font-weight: 700;"><i class="ph-bold ph-clock"></i> Awaiting Principal</span>
                                        @elseif(str_contains($b->approval_status, 'HOD'))
                                            <span style="color: #b45309; font-weight: 700;"><i class="ph-bold ph-clock"></i> HOD Review</span>
                                        @elseif(str_contains($b->approval_status, 'Warden'))
                                            <span style="color: #b45309; font-weight: 700;"><i class="ph-bold ph-clock"></i> Warden Review</span>
                                        @endif

                                        <span style="font-weight: 700; color: {{ $b->payment_status === 'Paid' ? '#166534' : '#b45309' }};">
                                            <i class="ph-bold ph-credit-card"></i> {{ $b->payment_status === 'Paid' ? 'Payment Paid' : 'Payment Pending' }}
                                        </span>

                                        @if($b->approval_remarks || $b->principal_remarks)
                                            <span style="color: #0369a1; font-weight: 600; font-size: 0.68rem; background: #f0f9ff; padding: 2px 6px; border-radius: 4px; border: 1px solid #bae6fd; margin-top: 2px;" title="{{ $b->approval_remarks ?? $b->principal_remarks }}">
                                                <i class="ph-bold ph-note"></i> {{ Str::limit($b->approval_remarks ?? $b->principal_remarks, 25) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 4rem; color: var(--text-muted);">
                                No stay records found for the selected period.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- History Data Import Modal -->
    <div id="historyImportModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 99999; justify-content: center; align-items: center; padding: 1rem; opacity: 0; transition: opacity 0.2s ease;">
        <div style="background: #ffffff; width: 100%; max-width: 600px; border-radius: 14px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1); overflow: hidden; border: 1px solid #e2e8f0;">
            <div style="padding: 1.25rem 1.5rem; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <h3 style="margin: 0; font-size: 1.05rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                        <i class="ph-bold ph-file-csv" style="color: #0284c7;"></i> Import History Data / Softcopy
                    </h3>
                </div>
                <button type="button" onclick="closeHistoryImportModal()" style="background: none; border: none; font-size: 1.2rem; color: #64748b; cursor: pointer; padding: 4px; border-radius: 6px;">
                    <i class="ph-bold ph-x"></i>
                </button>
            </div>
            <form action="{{ route('admin.reports.import') }}" method="POST" enctype="multipart/form-data" style="padding: 1.5rem;">
                @csrf
                <p style="font-size: 0.82rem; color: #475569; margin-top: 0; margin-bottom: 1rem; line-height: 1.4;">
                    Upload historical booking softcopies or CSV files to import legacy records into the system.
                </p>
                
                <div style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 8px; padding: 0.85rem; margin-bottom: 1.25rem;">
                    <label style="font-size: 0.72rem; font-weight: 700; color: #0369a1; display: block; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Supported CSV Column Headings Format:</label>
                    <code style="display: block; font-size: 0.72rem; background: #ffffff; padding: 8px; border-radius: 6px; border: 1px solid #e0f2fe; color: #0c4a6e; font-family: monospace; word-break: break-all; white-space: pre-wrap;">Reference ID, Guest Name, Email, Phone, User Type, Room Name, Booking Date, Start Time, End Time, Total Price, Payment Status, Approval Status, Department, Stream, Booking Reason, Approval Remarks</code>
                    <p style="margin: 6px 0 0 0; font-size: 0.73rem; color: #0284c7; font-weight: 600;">
                        <i class="ph-bold ph-info"></i> All fields are optional! If your CSV only has a few columns or empty cells, the importer automatically fills in standard default values (auto-generated Reference ID, Paid status, Approved status, etc.).
                    </p>
                </div>

                <div class="form-group" style="margin-bottom: 1.25rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; font-size: 0.85rem; color: #334155;">Select CSV / Softcopy File (.csv)</label>
                    <input type="file" name="csv_file" class="form-input" required accept=".csv,.txt" style="width: 100%; padding: 10px; border: 1.5px solid #cbd5e1; border-radius: 10px; font-size: 0.85rem;">
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 1.5rem;">
                    <button type="button" onclick="closeHistoryImportModal()" style="padding: 8px 18px; font-size: 0.85rem; font-weight: 700; border-radius: 10px; border: 1.5px solid #cbd5e1; background: #ffffff; color: #334155; cursor: pointer;">
                        Cancel
                    </button>
                    <button type="submit" style="padding: 8px 20px; font-size: 0.85rem; font-weight: 700; border-radius: 10px; border: none; background: #0284c7; color: #ffffff; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="ph-bold ph-upload-simple"></i> Import History Records
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        window.openHistoryImportModal = function () {
            const modal = document.getElementById('historyImportModal');
            if (modal) {
                modal.style.display = 'flex';
                setTimeout(() => { modal.style.opacity = '1'; }, 10);
            }
        };

        window.closeHistoryImportModal = function () {
            const modal = document.getElementById('historyImportModal');
            if (modal) {
                modal.style.opacity = '0';
                setTimeout(() => { modal.style.display = 'none'; }, 200);
            }
        };

        window.openDocUploadModal = function (bookingId, guestName) {
            const modal = document.getElementById('docUploadModal');
            const form = document.getElementById('docUploadForm');
            const subtitle = document.getElementById('docUploadModalSubtitle');
            const selectWrapper = document.getElementById('bookingSelectWrapper');
            const bookingSelect = document.getElementById('bookingSelect');

            if (!modal || !form) return;

            if (bookingId) {
                if (selectWrapper) selectWrapper.style.display = 'none';
                if (bookingSelect) bookingSelect.removeAttribute('required');
                form.action = `/admin/bookings/${bookingId}/upload-document`;
                if (subtitle) {
                    subtitle.innerHTML = `Attach official ID proof, referral letter, or booking softcopy document for <strong>${guestName}</strong> (Booking #BK-${String(bookingId).padStart(6, '0')}).`;
                }
            } else {
                if (selectWrapper) selectWrapper.style.display = 'block';
                if (bookingSelect) {
                    bookingSelect.setAttribute('required', 'required');
                    bookingSelect.value = '';
                }
                form.action = '';
                if (subtitle) {
                    subtitle.innerHTML = `Select a guest booking record below to upload and attach an official softcopy document.`;
                }
            }

            modal.style.display = 'flex';
            setTimeout(() => { modal.style.opacity = '1'; }, 10);
        };

        window.updateFormActionFromSelect = function (selectedId) {
            const form = document.getElementById('docUploadForm');
            if (form && selectedId) {
                form.action = `/admin/bookings/${selectedId}/upload-document`;
            }
        };

        window.closeDocUploadModal = function () {
            const modal = document.getElementById('docUploadModal');
            if (modal) {
                modal.style.opacity = '0';
                setTimeout(() => { modal.style.display = 'none'; }, 200);
            }
        };

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

