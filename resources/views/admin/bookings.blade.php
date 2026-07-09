<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
        }

        body {
            background-color: var(--bg-color);
            display: flex;
            min-height: 100vh;
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
            padding: 1.5rem 0.75rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.85rem 1rem;
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

        .sidebar-footer {
            padding: 1.5rem;
            border-top: 1px solid var(--border);
        }

        /* Main Content */
        .admin-main {
            margin-left: var(--sidebar-width);
            flex: 1;
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
            padding: 2.5rem; padding-bottom: 1.5rem; max-width: 1600px; width: 100%; 
            margin: 0 auto; box-sizing: border-box; flex: 1;
        }

        @media (max-width: 768px) {
            .admin-main { margin-left: 0; width: 100%; }
            .admin-body { padding: 1.25rem; }
        }



        .admin-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-color);
            margin: 0;
        }

        /* Content Card */
        .content-card {
            background: white;
            border-radius: 12px;
            border: 1px solid var(--border);
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            margin-bottom: 2rem;
        }

        /* Filter Section */
        .filter-section {
            padding: 1.25rem 1.5rem;
            background: #fff;
            border-bottom: 1px solid var(--border);
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: flex-end;
        }

        .filter-section .filter-group {
            flex: 1;
            min-width: 160px;
        }

        .filter-section .filter-actions {
            display: flex;
            gap: 0.5rem;
            flex-shrink: 0;
        }

        @media (max-width: 640px) {
            .filter-section {
                flex-direction: column;
            }
            .filter-section .filter-group,
            .filter-section .filter-actions { width: 100%; flex: unset; }
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .filter-group label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-light);
            text-transform: uppercase;
        }

        .filter-group input, .filter-group select {
            padding: 0.65rem 1rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 0.9rem;
            outline: none;
            transition: border-color 0.2s;
        }

        .filter-group input:focus, .filter-group select:focus {
            border-color: var(--primary-color);
        }

        /* Data Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th, .data-table td {
            padding: 0.9rem 1.1rem;
            text-align: left;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        /* Action column: fixed width, never wrap */
        .data-table th:last-child,
        .data-table td:last-child {
            white-space: nowrap;
            min-width: 120px;
            padding-right: 1.5rem;
        }

        .data-table th {
            text-align: left;
            padding: 1.25rem 1rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05rem;
            color: #64748b;
            font-weight: 700;
            border-bottom: 2px solid #f1f5f9;
            white-space: nowrap;
        }

        .data-table td {
            font-size: 0.9rem;
            color: #334155;
        }

        .data-table tbody tr:hover {
            background-color: #f8fafc;
        }

        .data-table td {
            font-size: 0.9rem;
            color: #334155;
        }

        .customer-name {
            font-weight: 600;
            color: #1e293b;
        }

        .customer-id {
            font-size: 0.75rem;
            color: #64748b;
            font-weight: normal;
        }

        .status-pill {
            padding: 0.35rem 0.75rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 700;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .pill-paid { background: #dcfce7; color: #166534; }
        .pill-pending { background: #ffedd5; color: #9a3412; }
        .pill-failed { background: #fee2e2; color: #991b1b; }
        .pill-approved { background: #dcfce7; color: #166534; }
        .pill-principal-approved { background: #d1fae5; color: #065f46; border: 1px solid #059669; }
        .pill-approved-by-principal { background: #d1fae5; color: #065f46; border: 1px solid #059669; }
        .pill-rejected { background: #fee2e2; color: #991b1b; }

        .btn-approve {
            padding: 0.6rem;
            width: 36px;
            height: 36px;
            background: #28a745;
            color: white !important;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        .btn-approve i { color: white !important; }

        .btn-reject {
            padding: 0.6rem;
            width: 36px;
            height: 36px;
            background: #dc3545;
            color: white !important;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        .btn-reject i { color: white !important; }

        .btn-approve:hover { background: #218838; }
        .btn-reject:hover { background: #c82333; }

        .btn-view {
            padding: 0.6rem;
            width: 36px;
            height: 36px;
            background: #f1f5f9;
            color: #334155;
            text-decoration: none;
            border-radius: 8px;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .btn-view:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .btn-delete {
            padding: 0.6rem;
            width: 36px;
            height: 36px;
            background: #fff1f2;
            color: #be123c;
            border: 1px solid #fecdd3;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .btn-delete:hover {
            background: #ffe4e6;
            color: #9f1239;
        }

        /* Pagination */
        .pagination-container {
            padding: 1.25rem 1.5rem;
            border-top: 1px solid var(--border);
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 0.75rem;
        }

        .pagination-info {
            font-size: 0.875rem;
            color: var(--text-light);
            white-space: nowrap;
        }

        .pagination-links {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }

        /* Pagination Styling */
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
                align-items: center !important;
                text-align: center !important;
                padding: 1rem !important;
                gap: 0.75rem !important;
            }
            .pagination-links {
                width: 100% !important;
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch !important;
                display: block !important;
                text-align: center !important;
                padding-bottom: 4px !important;
            }
            .pagination {
                display: inline-flex !important;
                flex-wrap: nowrap !important;
                gap: 3px !important;
                margin: 0 auto !important;
            }
            .page-item .page-link {
                min-width: 30px !important;
                height: 30px !important;
                padding: 0 8px !important;
                font-size: 0.75rem !important;
            }
        }

        /* Confirmation Modal */
        .confirm-modal-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
        .confirm-modal-content {
            background: white;
            padding: 2.25rem;
            border-radius: 20px;
            width: 90%;
            max-width: 380px;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.15);
            border: 1px solid #f1f5f9;
        }
        .confirm-modal-content h3 { margin: 0 0 0.75rem; color: #1e293b; font-size: 1.25rem; font-weight: 700; }
        .confirm-modal-content p { margin: 0 0 2rem; color: #64748b; font-size: 0.95rem; line-height: 1.6; }
        .confirm-modal-footer { display: flex; gap: 0.75rem; }
        .confirm-btn-cancel {
            flex: 1; padding: 0.85rem; border-radius: 12px; border: 1px solid #e2e8f0;
            background: #f8fafc; color: #64748b; font-weight: 600; cursor: pointer; transition: all 0.2s;
        }
        .confirm-btn-confirm {
            flex: 1; padding: 0.85rem; border-radius: 12px; border: none;
            background: #22c55e; color: white; font-weight: 700; cursor: pointer; transition: all 0.2s;
        }
        .confirm-btn-confirm.is-reject { background: #ef4444; }
        .confirm-btn-cancel:hover { background: #f1f5f9; color: #475569; }
        .confirm-btn-confirm:hover { filter: brightness(0.95); transform: translateY(-1px); }
        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s ease; z-index: 1000; }
            .sidebar.open { transform: translateX(0); }
            .admin-main { margin-left: 0 !important; width: 100% !important; }
            .top-navbar { padding: 0 1rem !important; }
            #sidebarToggle { display: flex !important; }
            .top-navbar div:first-child { font-size: 1rem !important; }
            .filter-section { grid-template-columns: 1fr 1fr !important; }
        }
        @media (max-width: 640px) {
            .filter-section { grid-template-columns: 1fr !important; }
            .top-navbar div:last-child a { padding: 0.5rem 0.75rem !important; font-size: 0.75rem !important; }
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
            <a href="{{ route('admin.bookings') }}" class="menu-item active">
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
        </div>
        <div class="sidebar-footer" style="margin-top: auto; border-top: 1px solid var(--border); padding: 1rem;">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="menu-item" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer; color: #ef4444; margin: 0;">
                    <i class="ph ph-sign-out"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <main class="admin-main">
        <!-- Sticky Top Navbar -->
        <div class="top-navbar">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <button id="sidebarToggle" style="display: none; background: #fff; border: 1px solid var(--border); border-radius: 8px; width: 40px; height: 40px; align-items: center; justify-content: center; color: var(--text-main); cursor: pointer; font-size: 1.25rem;">
                    <i class="ph ph-list"></i>
                </button>
                <div style="font-weight: 700; font-size: 1.15rem; color: #1e293b;">
                    <i class="ph-bold ph-calendar-check" style="color: var(--primary-color); margin-right: 0.4rem;"></i>
                    Bookings
                </div>
            </div>
            <div style="display: flex; gap: 0.5rem; align-items: center;" class="topbar-actions-wrap">
                <a href="{{ route('admin.college-guest') }}"
                   style="display: inline-flex; align-items: center; gap: 0.5rem;
                          background: var(--primary-color); color: #ffffff;
                          padding: 0.6rem 1.25rem;
                          border-radius: 10px;
                          font-weight: 600; font-size: 0.85rem;
                          text-decoration: none;
                          box-shadow: 0 2px 8px rgba(133, 15, 15,0.25);
                          transition: background 0.2s ease;">
                    <i class="ph-bold ph-user-plus" style="font-size: 1rem; color:#fff !important;"></i>
                    <span class="nav-btn-text">Book College Guest</span>
                </a>
                <a href="{{ route('admin.bookings.export', request()->only(['search','date','status','workspace'])) }}"
                   style="display: inline-flex; align-items: center; gap: 0.5rem;
                          background: #16a34a; color: #ffffff;
                          padding: 0.6rem 1.25rem;
                          border-radius: 10px;
                          font-weight: 600; font-size: 0.85rem;
                          text-decoration: none;
                          box-shadow: 0 2px 8px rgba(22,163,74,0.25);
                          transition: background 0.2s ease;">
                    <i class="ph-bold ph-file-arrow-down" style="font-size: 1rem; color:#fff !important;"></i>
                    <span class="nav-btn-text">Export CSV</span>
                </a>
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

        <!-- Page Body -->
        <div class="admin-body">

        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; border: 1px solid #c3e6cb; display: flex; align-items: center; gap: 0.5rem;">
                <i class="ph-bold ph-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; border: 1px solid #fecaca; display: flex; align-items: center; gap: 0.5rem;">
                <i class="ph-bold ph-warning-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        <div class="content-card">
            <form action="{{ route('admin.bookings') }}" method="GET" class="filter-section">
                <div class="filter-group">
                    <label>Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Customer Name or Booking ID...">
                </div>
                <div class="filter-group">
                    <label>Date</label>
                    <input type="date" name="date" value="{{ request('date') }}">
                </div>
                <div class="filter-group">
                    <label>Workspace</label>
                    <select name="workspace">
                        <option value="">All Workspaces</option>
                        @foreach($workspaces as $workspace)
                            <option value="{{ $workspace }}" {{ request('workspace') == $workspace ? 'selected' : '' }}>{{ $workspace }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="">All Status</option>
                        <option value="Paid" {{ request('status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Failed" {{ request('status') == 'Failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <div class="filter-actions">
                    <button type="submit" class="btn" style="height: 42px; padding: 0 1.1rem;"><i class="ph ph-magnifying-glass"></i></button>
                    <a href="{{ route('admin.bookings') }}" class="btn btn-outline" style="height: 42px; padding: 0 1.1rem; display: flex; align-items: center;"><i class="ph ph-arrows-clockwise"></i></a>
                </div>
            </form>

            <div style="overflow-x: auto; padding: 0 1px;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Customer</th>
                            <th>Workspace</th>
                            <th>Date / Time Slot</th>
                            <th>Approval Status</th>
                            <th>Payment Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                        <tr>
                            <td style="font-weight: 600; font-family: 'Inter', sans-serif; font-size: 0.85rem; color: #334155; white-space: nowrap; letter-spacing: 0.3px;">BK-{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <div>
                                        <div class="customer-name">{{ $booking->name }}</div>
                                        <div class="customer-id">{{ $booking->email }}</div>
                                    </div>
                                    @if($booking->referral_attachment)
                                        <i class="ph-bold ph-paperclip" style="color: var(--primary-color);" title="Has Referral Attachment"></i>
                                    @endif
                                </div>
                            </td>
                            <td>{{ str_replace('-', ' ', ucwords($booking->room_name, '- ')) }}</td>
                            <td style="white-space: nowrap;">
                                <div style="font-weight: 600; color: #1e293b;">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</div>
                                <div style="font-size: 0.75rem; color: #64748b;">
                                    <span style="font-weight: 700; color: var(--primary-color);">IN:</span> {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}
                                </div>
                                <div style="font-size: 0.75rem; color: #64748b;">
                                    <span style="font-weight: 700; color: #ef4444;">OUT:</span> {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}
                                </div>
                            </td>
                            <td>
                                <span class="status-pill pill-{{ str_replace(' ', '-', strtolower($booking->approval_status)) }}">
                                    @if(strtolower($booking->approval_status) === 'pending')
                                        <i class="ph-fill ph-clock"></i> PENDING APPROVAL
                                    @elseif(strtolower($booking->approval_status) === 'approved' || str_contains(strtolower($booking->approval_status), 'principal'))
                                        <i class="ph-fill ph-check-circle"></i> {{ $booking->approval_status }}
                                    @elseif(strtolower($booking->approval_status) === 'rejected')
                                        <i class="ph-fill ph-x-circle"></i> {{ $booking->approval_status }}
                                    @else
                                        <i class="ph-fill ph-info"></i> {{ $booking->approval_status }}
                                    @endif
                                </span>
                            </td>
                            <td>
                                <span class="status-pill pill-{{ strtolower($booking->payment_status) }}">
                                    @if(strtolower($booking->payment_status) === 'paid')
                                        <i class="ph-fill ph-check-circle"></i> {{ $booking->payment_status }}
                                    @elseif(strtolower($booking->payment_status) === 'pending')
                                        <i class="ph-fill ph-clock"></i> {{ $booking->payment_status }}
                                    @elseif(strtolower($booking->payment_status) === 'failed')
                                        <i class="ph-fill ph-x-circle"></i> {{ $booking->payment_status }}
                                    @else
                                        {{ $booking->payment_status }}
                                    @endif
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem; align-items: center;">
                                    @if($booking->approval_status === 'Pending' || $booking->approval_status === 'Principal Approved' || $booking->approval_status === 'Approved by Principal')
                                        <form action="{{ route('admin.bookings.approve', $booking->id) }}" method="POST" style="display: inline;" onsubmit="event.preventDefault(); showConfirmModal('approve', this);">
                                            @csrf
                                            <button type="submit" class="btn-approve" title="{{ ($booking->approval_status === 'Principal Approved' || $booking->approval_status === 'Approved by Principal') ? 'Final Approve' : 'Approve' }}">
                                                <i class="ph-bold ph-check" style="color: #ffffff !important;"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.bookings.reject', $booking->id) }}" method="POST" style="display: inline;" onsubmit="event.preventDefault(); showConfirmModal('reject', this);">
                                            @csrf
                                            <button type="submit" class="btn-reject" title="Reject">
                                                <i class="ph-bold ph-x" style="color: #ffffff !important;"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn-view" title="View Details">
                                        <i class="ph ph-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST" onsubmit="event.preventDefault(); showConfirmModal('delete', this);" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete">
                                            <i class="ph ph-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 4rem; color: #64748b;">
                                <i class="ph ph-calendar-x" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.2; display: block; margin-left: auto; margin-right: auto;"></i>
                                No bookings found matching your criteria.
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
        </div><!-- /.content-card -->
        </div><!-- /.admin-body -->
    </main>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="confirm-modal-overlay">
        <div class="confirm-modal-content">
            <h3 id="confirmTitle">Confirm Action</h3>
            <p id="confirmMessage">Are you sure you want to proceed?</p>
            <div class="confirm-modal-footer">
                <button id="cancelModalBtn" class="confirm-btn-cancel">Cancel</button>
                <button id="confirmModalBtn" class="confirm-btn-confirm">Confirm</button>
            </div>
        </div>
    </div>
    <script>
        let pendingForm = null;

        function showConfirmModal(type, form) {
            pendingForm = form;
            const modal = document.getElementById('confirmModal');
            const title = document.getElementById('confirmTitle');
            const msg = document.getElementById('confirmMessage');
            const confirmBtn = document.getElementById('confirmModalBtn');
            
            if (type === 'approve') {
                title.innerText = 'Approve Booking';
                msg.innerText = 'Are you sure you want to approve this booking?';
                confirmBtn.innerText = 'Yes, Approve';
                confirmBtn.className = 'confirm-btn-confirm';
            } else if (type === 'reject') {
                title.innerText = 'Reject Booking';
                msg.innerText = 'Are you sure you want to reject this booking?';
                confirmBtn.innerText = 'Yes, Reject';
                confirmBtn.className = 'confirm-btn-confirm is-reject';
            } else if (type === 'delete') {
                title.innerText = 'Delete Booking';
                msg.innerText = 'Are you sure you want to delete this booking permanently? This action cannot be undone.';
                confirmBtn.innerText = 'Yes, Delete';
                confirmBtn.className = 'confirm-btn-confirm is-reject';
            }
            modal.style.display = 'flex';
        }

        document.getElementById('cancelModalBtn').addEventListener('click', () => {
            document.getElementById('confirmModal').style.display = 'none';
        });

        document.getElementById('confirmModalBtn').addEventListener('click', () => {
            if (pendingForm) pendingForm.submit();
        });

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
    </script>
</body>
</html>
