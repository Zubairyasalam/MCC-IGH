<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Reports - SuperAdmin</title>
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
            --danger: #ef4444;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { font-family: 'Inter', sans-serif; background: var(--bg-color); display: flex; min-height: 100vh; }

        .sidebar {
            width: var(--sidebar-width); background: white; height: 100vh;
            border-right: 1px solid var(--border); position: fixed;
            display: flex; flex-direction: column; z-index: 100;
        }
        .sidebar-header {
            height: 72px; padding: 0 1.25rem; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between; box-sizing: border-box;
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
        .menu-item {
            display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem;
            color: var(--text-muted); text-decoration: none; border-radius: 8px;
            font-weight: 500; font-size: 0.9rem; transition: all 0.2s; margin-bottom: 0.25rem;
        }
        .menu-item:hover, .menu-item.active { background: rgba(133, 15, 15, 0.08); color: var(--primary-color); }
        .sidebar-footer { padding: 1rem; border-top: 1px solid var(--border); }
        .logout-btn {
            width: 100%; display: flex; align-items: center; gap: 0.75rem; background: none; border: none;
            padding: 0.75rem 1rem; color: var(--danger); cursor: pointer; font-weight: 600; border-radius: 8px; font-size: 0.9rem;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            min-height: 100vh;
            background: var(--bg-color);
            display: flex;
            flex-direction: column;
        }
        .topbar {
            height: 56px; background: white; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between; padding: 0 1.5rem;
            position: sticky; top: 0; z-index: 90;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        }
        .page-body { padding: 1.25rem 1.5rem; max-width: 1500px; width: 100%; margin: 0 auto; box-sizing: border-box; }
        .topbar-right { display: flex; align-items: center; gap: 0.85rem; }

        .card { background: white; border: 1px solid #e2e8f0; border-radius: 10px; padding: 1rem 1.25rem; box-shadow: 0 1px 2px rgba(0,0,0,0.03); margin-bottom: 1.25rem; }
        .card-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem; }
        .card-title { font-size: 0.95rem; font-weight: 700; color: #0f172a; }

        .btn {
            padding: 0.4rem 0.85rem; border-radius: 6px; font-weight: 600; font-size: 0.78rem;
            cursor: pointer; transition: all 0.15s ease; border: none; display: inline-flex; align-items: center; gap: 0.35rem;
            line-height: 1.2; text-decoration: none; font-family: 'Inter', sans-serif; height: 32px;
        }
        .btn-primary { background: var(--primary-color, #850f0f); color: #ffffff; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
        .btn-primary:hover { background: #6b0c0c; color: #ffffff; transform: translateY(-1px); }
        .btn-outline { background: #ffffff; border: 1px solid #cbd5e1; color: #475569; }
        .btn-outline:hover { background: #f8fafc; color: #0f172a; border-color: #94a3b8; }
        .btn-dark { background: #1e293b; color: #ffffff; }
        .btn-dark:hover { background: #0f172a; }
        .btn-green { background: #166534; color: #ffffff; }
        .btn-green:hover { background: #14532d; }

        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th {
            font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.05em;
            color: #64748b; font-weight: 600; padding: 10px 12px;
            background: #f8fafc; text-align: left; border-bottom: 1px solid #e2e8f0;
        }
        .data-table td { padding: 10px 12px; border-bottom: 1px solid #f1f5f9; font-size: 0.82rem; vertical-align: middle; }

        /* Profile Dropdown */
        .admin-profile-wrap { position: relative; display: inline-flex; align-items: center; }
        .admin-profile-btn {
            width: 34px; height: 34px; background: #f8fafc; border: 1px solid var(--border);
            border-radius: 8px; display: flex; align-items: center; justify-content: center;
            color: #475569; cursor: pointer; font-size: 1.1rem; transition: all 0.2s;
        }
        .admin-profile-btn:hover { background: #f1f5f9; color: var(--primary-color); }
        .admin-profile-menu {
            position: absolute; top: calc(100% + 8px); right: 0; display: none; z-index: 2000;
            background: #ffffff; border: 1px solid rgba(0,0,0,0.08); border-radius: 10px;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1); min-width: 140px; padding: 6px;
        }
        .admin-profile-menu.open { display: block; }
        .admin-logout-btn {
            display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 8px;
            background: #fff1f2; border: 1px solid #fecdd3; color: #ef4444; font-weight: 700;
            font-size: 0.8rem; border-radius: 6px; cursor: pointer; font-family: 'Inter', sans-serif;
        }

        #sidebarToggle { display: none; }
        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s ease; }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
            #sidebarToggle { display: flex !important; }
            .page-body { padding: 1rem; }
        }

        /* Modal */
        .modal {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px);
            display: none; align-items: center; justify-content: center; z-index: 1000; padding: 1.25rem;
        }
        .modal.active { display: flex; }
        .modal-content {
            background: white; padding: 1.75rem; border-radius: 16px; width: 100%; max-width: 460px;
            position: relative; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .modal-close { position: absolute; top: 1.25rem; right: 1.25rem; cursor: pointer; font-size: 1.3rem; color: #94a3b8; }
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
            <a href="{{ route('superadmin.payments') }}" class="menu-item {{ Route::is('superadmin.payments') ? 'active' : '' }}">
                <i class="ph ph-wallet"></i> Payment Details
            </a>
            <a href="{{ route('superadmin.reports') }}" class="menu-item active">
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
                <button type="submit" class="logout-btn"><i class="ph-bold ph-sign-out"></i> Logout</button>
            </form>
        </div>
    </div>

    <div class="main-content">
        <div class="topbar">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <button id="sidebarToggle" style="width: 36px; height: 36px; padding: 0; align-items: center; justify-content: center; border-radius: 8px; border: 1px solid var(--border); background: white; color: var(--text-main); cursor: pointer;">
                    <i class="ph ph-list" style="font-size: 1.25rem;"></i>
                </button>
                <div style="font-weight: 700; font-size: 0.95rem; color: #0f172a;">Booking Reports</div>
            </div>
            <div class="topbar-right">
                <div title="Current Theme Color" style="width: 12px; height: 12px; border-radius: 50%; background: var(--primary-color, #850f0f); flex-shrink: 0;"></div>
                <div style="font-size: 0.8rem; color: #64748b; font-weight: 500;">{{ now()->format('d M Y, H:i') }}</div>
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
            @if(session('success'))
                <div style="padding: 0.75rem 1rem; background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; border-radius: 8px; font-size: 0.82rem; font-weight: 600; margin-bottom: 1rem;">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Summary Cards -->
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.25rem;">
                <div style="background: white; padding: 1rem 1.2rem; border-radius: 10px; border: 1px solid #e2e8f0; box-shadow: 0 1px 2px rgba(0,0,0,0.02);">
                    <div style="color: #64748b; font-size: 0.68rem; font-weight: 600; text-transform: uppercase; margin-bottom: 2px;">Total Collected Revenue</div>
                    <div style="font-size: 1.25rem; font-weight: 700; color: #0f172a;">₹{{ number_format($totalRevenue, 2) }}</div>
                </div>

                <div style="background: white; padding: 1rem 1.2rem; border-radius: 10px; border: 1px solid #e2e8f0; box-shadow: 0 1px 2px rgba(0,0,0,0.02);">
                    <div style="color: #64748b; font-size: 0.68rem; font-weight: 600; text-transform: uppercase; margin-bottom: 2px;">Net Revenue (Excl. GST)</div>
                    <div style="font-size: 1.25rem; font-weight: 700; color: #166534;">₹{{ number_format($netRevenue, 2) }}</div>
                </div>

                <div style="background: white; padding: 1rem 1.2rem; border-radius: 10px; border: 1px solid #e2e8f0; box-shadow: 0 1px 2px rgba(0,0,0,0.02);">
                    <div style="color: #64748b; font-size: 0.68rem; font-weight: 600; text-transform: uppercase; margin-bottom: 2px;">Total GST ({{ $gstRate }}%)</div>
                    <div style="font-size: 1.25rem; font-weight: 700; color: #64748b;">₹{{ number_format($totalGst, 2) }}</div>
                </div>
            </div>

            <!-- Stay History Filters -->
            <div style="display: flex; gap: 6px; margin-bottom: 1rem; flex-wrap: wrap; align-items: center;">
                <span style="font-size: 0.75rem; font-weight: 600; color: #64748b; margin-right: 4px;">Stay History Filters:</span>
                <a href="{{ route('superadmin.reports', ['preset' => '20days']) }}" 
                   style="padding: 5px 12px; font-size: 0.75rem; font-weight: 600; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; transition: all 0.15s; {{ ($preset ?? '') === '20days' ? 'background: var(--primary-color, #850f0f); color: white;' : 'background: white; border: 1px solid #cbd5e1; color: #475569;' }}">
                   <i class="ph-bold ph-calendar-blank"></i> Last 20 Days Stayed
                </a>
                <a href="{{ route('superadmin.reports', ['preset' => '30days']) }}" 
                   style="padding: 5px 12px; font-size: 0.75rem; font-weight: 600; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; transition: all 0.15s; {{ ($preset ?? '') === '30days' ? 'background: var(--primary-color, #850f0f); color: white;' : 'background: white; border: 1px solid #cbd5e1; color: #475569;' }}">
                   <i class="ph-bold ph-calendar"></i> Last 30 Days
                </a>
                <a href="{{ route('superadmin.reports', ['preset' => 'this_month']) }}" 
                   style="padding: 5px 12px; font-size: 0.75rem; font-weight: 600; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; transition: all 0.15s; {{ ($preset ?? '') === 'this_month' ? 'background: var(--primary-color, #850f0f); color: white;' : 'background: white; border: 1px solid #cbd5e1; color: #475569;' }}">
                   <i class="ph-bold ph-calendar-check"></i> This Month
                </a>
                <a href="{{ route('superadmin.reports', ['preset' => 'all']) }}" 
                   style="padding: 5px 12px; font-size: 0.75rem; font-weight: 600; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; transition: all 0.15s; {{ ($preset ?? '') === 'all' ? 'background: var(--primary-color, #850f0f); color: white;' : 'background: white; border: 1px solid #cbd5e1; color: #475569;' }}">
                   <i class="ph-bold ph-clock-counter-clockwise"></i> All Time History
                </a>
            </div>

            <!-- Filter Controls & Actions Card -->
            <div class="card" style="padding: 0.85rem 1.1rem; background: white; border: 1px solid #e2e8f0; border-radius: 10px; box-shadow: 0 1px 2px rgba(0,0,0,0.02); margin-bottom: 1.25rem; height: auto !important; min-height: 0 !important;">
                <form action="{{ route('superadmin.reports') }}" method="GET" style="display: flex; flex-wrap: wrap; gap: 10px; align-items: flex-end; height: auto !important; min-height: 0 !important;">
                    <div style="flex: 0 0 150px; min-width: 120px; margin-bottom: 0;">
                        <label style="font-weight: 600; font-size: 0.68rem; color: #475569; margin-bottom: 3px; display: block; text-transform: uppercase;">Start Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" style="height: 32px; padding: 0 8px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.75rem; width: 100%; outline: none;">
                    </div>
                    <div style="flex: 0 0 150px; min-width: 120px; margin-bottom: 0;">
                        <label style="font-weight: 600; font-size: 0.68rem; color: #475569; margin-bottom: 3px; display: block; text-transform: uppercase;">End Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" style="height: 32px; padding: 0 8px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.75rem; width: 100%; outline: none;">
                    </div>

                    <div style="display: flex; gap: 6px; flex-wrap: wrap; align-items: center; align-self: flex-end;">
                        <button type="submit" style="height: 32px; padding: 0 12px; font-size: 0.75rem; background: #0f172a; border: none; font-weight: 600; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px; color: white; cursor: pointer; font-family: 'Inter', sans-serif;">
                            <i class="ph ph-funnel"></i> Apply Filter
                        </button>
                        
                        @if(count($bookings) > 0)
                            <a href="{{ route('superadmin.reports.download', request()->all()) }}" style="height: 32px; padding: 0 12px; font-size: 0.75rem; background: var(--primary-color, #850f0f); font-weight: 600; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; color: white; font-family: 'Inter', sans-serif;">
                                <i class="ph-bold ph-file-pdf"></i> Download PDF
                            </a>
                            <a href="{{ route('superadmin.reports.export', request()->all()) }}" style="height: 32px; padding: 0 12px; font-size: 0.75rem; background: #166534; border: none; font-weight: 600; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; color: white; font-family: 'Inter', sans-serif;">
                                <i class="ph-bold ph-file-csv"></i> Download CSV
                            </a>
                        @endif
                        
                        <button type="button" onclick="openSoftcopyModal()" style="height: 32px; padding: 0 12px; font-size: 0.75rem; background: #ffffff; border: 1px solid #cbd5e1; font-weight: 600; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px; color: #334155; cursor: pointer; font-family: 'Inter', sans-serif;">
                            <i class="ph-bold ph-upload-simple"></i> Upload Softcopy
                        </button>
                    </div>
                </form>
            </div>

            <!-- Reports Table Card -->
            <div class="card" style="padding: 0; overflow: hidden; border: 1px solid #e2e8f0;">
                <div style="overflow-x: auto; width: 100%; -webkit-overflow-scrolling: touch;">
                    <table class="data-table" style="min-width: 750px;">
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
                                        <span style="font-size: 0.62rem; font-weight: 600; color: #64748b; background: #f1f5f9; padding: 1px 5px; border-radius: 4px; display: inline-block; margin-top: 2px;">{{ $b->reference_id }}</span>
                                    @endif
                                </td>
                                <td style="padding: 9px 12px;">
                                    <div style="font-weight: 600; color: #0f172a; font-size: 0.82rem; display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                                        {{ $b->name }}
                                        <span style="font-size: 0.62rem; font-weight: 600; text-transform: uppercase; color: #475569; background: #f1f5f9; border: 1px solid #e2e8f0; padding: 1px 5px; border-radius: 4px;">
                                            {{ $b->user_type ?? 'Guest' }}
                                        </span>
                                    </div>
                                    <div style="font-size: 0.72rem; color: #64748b; margin-top: 2px;">
                                        {{ $b->email }} • {{ $b->phone ?? 'N/A' }}
                                    </div>
                                    <div style="font-size: 0.7rem; color: #334155; margin-top: 3px; display: flex; flex-wrap: wrap; gap: 4px; align-items: center;">
                                        @if($b->primary_guest_name)
                                            <span style="background: #f8fafc; border: 1px solid #cbd5e1; padding: 1px 5px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;">
                                                <i class="ph-bold ph-user"></i> <strong>Guest:</strong> {{ $b->primary_guest_name }} ({{ $b->no_of_persons ?? 1 }} Pers.)
                                            </span>
                                        @else
                                            <span style="background: #f8fafc; border: 1px solid #cbd5e1; padding: 1px 5px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;">
                                                <i class="ph-bold ph-users"></i> {{ $b->no_of_persons ?? 1 }} Person(s)
                                            </span>
                                        @endif

                                        @if($b->department)
                                            <span style="background: #f1f5f9; padding: 1px 5px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;">
                                                <i class="ph-bold ph-buildings"></i> {{ $b->department }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: #0f172a; font-size: 0.82rem; display: flex; align-items: center; gap: 4px;">
                                        <i class="ph-bold ph-bed" style="color: #850f0f;"></i> {{ $b->room_name }}
                                    </div>
                                    <div style="font-size: 0.72rem; color: #64748b; margin-top: 2px;">
                                        <i class="ph ph-clock"></i> {{ $b->booking_date ? \Carbon\Carbon::parse($b->booking_date)->format('d M Y') : 'N/A' }}
                                        @if($b->start_time && $b->end_time)
                                            ({{ \Carbon\Carbon::parse($b->start_time)->format('H:i') }} → {{ \Carbon\Carbon::parse($b->end_time)->format('H:i') }})
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight: 700; color: #0f172a; font-size: 0.88rem;">₹{{ number_format($b->total_price, 2) }}</div>
                                    <div style="font-size: 0.7rem; color: #64748b;">
                                        Base: ₹{{ number_format($bSubtotal, 2) }} | GST: ₹{{ number_format($bGstAmount, 2) }}
                                    </div>
                                </td>
                                <td>
                                    @if($b->approval_status === 'Approved' || str_contains($b->approval_status, 'Approved'))
                                        <span style="padding: 2px 8px; background: #dcfce7; color: #166534; font-size: 0.7rem; font-weight: 700; border-radius: 4px; display: inline-block;">
                                            APPROVED
                                        </span>
                                    @elseif($b->approval_status === 'Rejected')
                                        <span style="padding: 2px 8px; background: #fee2e2; color: #991b1b; font-size: 0.7rem; font-weight: 700; border-radius: 4px; display: inline-block;">
                                            REJECTED
                                        </span>
                                    @else
                                        <span style="padding: 2px 8px; background: #fef3c7; color: #92400e; font-size: 0.7rem; font-weight: 700; border-radius: 4px; display: inline-block;">
                                            {{ strtoupper($b->approval_status) }}
                                        </span>
                                    @endif

                                    <div style="font-size: 0.7rem; color: {{ $b->payment_status === 'Paid' ? '#166534' : '#b45309' }}; margin-top: 3px; font-weight: 600;">
                                        <i class="ph-bold {{ $b->payment_status === 'Paid' ? 'ph-check-circle' : 'ph-clock' }}"></i>
                                        Payment {{ $b->payment_status ?? 'Pending' }}
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 2rem; color: #64748b;">
                                    No stay history records found for the selected filter.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Softcopy Upload Modal -->
    <div id="softcopyModal" class="modal">
        <div class="modal-content">
            <i class="ph ph-x modal-close" onclick="closeSoftcopyModal()"></i>
            <h3 style="font-size: 1rem; font-weight: 700; color: #0f172a; margin-bottom: 1rem;">Upload Document / Softcopy</h3>
            <p style="font-size: 0.8rem; color: #64748b; margin-bottom: 1rem;">Select a booking to attach official softcopy documentation.</p>
            <form action="{{ route('admin.bookings.upload-document', 1) }}" id="uploadDocForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.78rem; font-weight: 600; color: #334155; margin-bottom: 4px;">Target Booking</label>
                    <select id="bookingSelect" style="width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.8rem;" onchange="updateUploadAction(this.value)">
                        @foreach($bookings as $b)
                            <option value="{{ $b->id }}">BK-{{ str_pad($b->id, 6, '0', STR_PAD_LEFT) }} - {{ $b->name }} ({{ $b->room_name }})</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom: 1.25rem;">
                    <label style="display: block; font-size: 0.78rem; font-weight: 600; color: #334155; margin-bottom: 4px;">Select Softcopy File (PDF, Image)</label>
                    <input type="file" name="admin_document" required style="width: 100%; font-size: 0.8rem;">
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; height: 36px;">
                    Upload Softcopy
                </button>
            </form>
        </div>
    </div>

    <script>
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('.sidebar');
        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                sidebar.classList.toggle('open');
            });
        }
        document.addEventListener('click', (event) => {
            if (window.innerWidth <= 1024 && sidebar && sidebar.classList.contains('open')) {
                if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
                    sidebar.classList.remove('open');
                }
            }
        });

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

        function openSoftcopyModal() {
            const select = document.getElementById('bookingSelect');
            if (select && select.value) {
                updateUploadAction(select.value);
            }
            document.getElementById('softcopyModal').classList.add('active');
        }
        function closeSoftcopyModal() {
            document.getElementById('softcopyModal').classList.remove('active');
        }
        function updateUploadAction(bookingId) {
            document.getElementById('uploadDocForm').action = "/admin/bookings/" + bookingId + "/upload-document";
        }
    </script>
</body>
</html>
