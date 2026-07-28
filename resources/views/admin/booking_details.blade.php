<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details #{{ $booking->id }} - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <style>
        :root {
            --sidebar-width: 260px;
            --bg-color: #f8fafc;
            --primary-color: #850f0f;
            --border: #eaedf0;
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

        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .admin-main { margin-left: 0 !important; width: 100% !important; padding: 0 !important; }
            .top-navbar { padding: 0 1rem !important; }
            #sidebarToggle { display: flex !important; }
            .admin-header h1 { font-size: 1.15rem !important; }
            .btn-download-pdf { display: none !important; }
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

        /* Main Content */

        .sidebar-menu {
            padding: 1.5rem 0.75rem;
            flex: 1;
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

        .menu-item:hover, .menu-item.active {
            background: rgba(133, 15, 15, 0.08);
            color: var(--primary-color);
        }

        @keyframes pulse-orange {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.4); }
            50% { transform: scale(1.01); box-shadow: 0 0 0 10px rgba(245, 158, 11, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
        }

        .pulse-status {
            animation: pulse-orange 2s infinite ease-in-out;
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
            padding: 2.5rem; padding-bottom: 1.5rem; max-width: 1600px; width: 100%; margin: 0 auto; box-sizing: border-box;
        }

        .admin-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .btn-back {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            border: 1px solid var(--border);
            color: var(--text-color);
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-back:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .admin-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-color);
            margin: 0;
        }

        /* Details Layout */
        .details-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1.5rem;
        }

        @media (max-width: 1024px) {
            .details-grid {
                grid-template-columns: 1fr;
            }
        }

        .details-card {
            background: white;
            border-radius: 12px;
            border: 1px solid var(--border);
            padding: 2rem;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 640px) {
            .details-card {
                padding: 1.25rem;
            }
        }

        .details-section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border);
            flex-wrap: wrap;
            gap: 1rem;
        }

        .details-section-header h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-color);
            margin: 0;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        @media (max-width: 480px) {
            .info-grid {
                grid-template-columns: 1fr;
                gap: 1.25rem;
            }
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .info-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .info-value {
            font-size: 1rem;
            font-weight: 500;
            color: #1e293b;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.45rem 0.9rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.82rem;
            font-family: 'Inter', sans-serif;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.45rem 0.9rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.8rem;
            letter-spacing: 0.2px;
        }

        .badge-paid { background: rgba(34, 197, 94, 0.1); color: #166534; border: 1px solid rgba(34, 197, 94, 0.2); }
        .badge-pending { background: rgba(245, 158, 11, 0.1); color: #b45309; border: 1px solid rgba(245, 158, 11, 0.2); }
        .badge-failed { background: rgba(239, 68, 68, 0.1); color: #991b1b; border: 1px solid rgba(239, 68, 68, 0.2); }
        .badge-approved { background: rgba(34, 197, 94, 0.1); color: #166534; border: 1px solid rgba(34, 197, 94, 0.2); }
        .badge-principal-approved { background: rgba(16, 185, 129, 0.1); color: #065f46; border: 1px solid rgba(16, 185, 129, 0.2); }
        .badge-approved-by-principal { background: rgba(16, 185, 129, 0.1); color: #065f46; border: 1px solid rgba(16, 185, 129, 0.2); }
        .badge-rejected { background: rgba(239, 68, 68, 0.1); color: #991b1b; border: 1px solid rgba(239, 68, 68, 0.2); }

        /* ============================
           ADMIN ACTION BUTTONS
           Using !important to prevent
           global style.css overrides
        ============================ */

        .btn-mark-paid {
            padding: 1rem 2rem !important;
            background: #16a34a !important;
            color: #ffffff !important;
            border: none !important;
            border-radius: 12px !important;
            font-size: 0.95rem !important;
            font-weight: 600 !important;
            font-family: 'Inter', sans-serif !important;
            cursor: pointer !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 10px !important;
            width: 100% !important;
            transition: background 0.2s ease, box-shadow 0.2s ease !important;
            box-shadow: 0 4px 14px rgba(22, 163, 74, 0.3) !important;
            letter-spacing: 0.2px !important;
        }

        .btn-mark-paid:hover {
            background: #15803d !important;
            box-shadow: 0 6px 20px rgba(22, 163, 74, 0.45) !important;
            color: #ffffff !important;
            transform: none !important;
            width: 100% !important;
        }

        .btn-approve {
            padding: 1rem 2rem !important;
            background: #850f0f !important;
            color: #ffffff !important;
            border: none !important;
            border-radius: 12px !important;
            font-size: 0.95rem !important;
            font-weight: 600 !important;
            font-family: 'Inter', sans-serif !important;
            cursor: pointer !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 10px !important;
            transition: background 0.2s ease, box-shadow 0.2s ease !important;
            box-shadow: 0 4px 14px rgba(133, 15, 15, 0.35) !important;
            letter-spacing: 0.2px !important;
            width: 100% !important;
        }

        .btn-approve:hover:not(:disabled) {
            background: #e66d00 !important;
            box-shadow: 0 6px 20px rgba(133, 15, 15, 0.5) !important;
            color: #ffffff !important;
        }

        .btn-approve:disabled {
            background: #94a3b8 !important;
            box-shadow: none !important;
            cursor: not-allowed !important;
            opacity: 0.55 !important;
            color: #fff !important;
        }

        .btn-reject {
            padding: 1rem 2rem !important;
            background: #e11d48 !important;
            color: #ffffff !important;
            border: none !important;
            border-radius: 12px !important;
            font-size: 0.95rem !important;
            font-weight: 600 !important;
            font-family: 'Inter', sans-serif !important;
            cursor: pointer !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 10px !important;
            transition: background 0.2s ease, box-shadow 0.2s ease !important;
            letter-spacing: 0.2px !important;
            width: 100% !important;
        }

        .btn-reject:hover {
            background: #be123c !important;
            color: #ffffff !important;
            box-shadow: 0 6px 20px rgba(190, 18, 60, 0.45) !important;
        }

        .btn-download-pdf {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.6rem 1.25rem;
            background: #ffffff;
            color: #1e293b;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
            margin-left: auto;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        .btn-download-pdf:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }

        .btn-view {
            padding: 0.85rem 1rem !important;
            background: #f1f5f9 !important;
            color: #475569 !important;
            text-decoration: none !important;
            border-radius: 12px !important;
            font-size: 0.9rem !important;
            font-weight: 700 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 0.5rem !important;
            transition: all 0.3s ease !important;
            border: 1px solid #e2e8f0 !important;
        }

        .btn-view:hover {
            background: #e2e8f0 !important;
            color: #1e293b !important;
        }

        .btn-delete {
            padding: 1rem 2rem !important;
            background: #fff1f2 !important;
            color: #be123c !important;
            border: 2px solid #fecdd3 !important;
            border-radius: 12px !important;
            font-size: 0.95rem !important;
            font-weight: 600 !important;
            font-family: 'Inter', sans-serif !important;
            cursor: pointer !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 10px !important;
            width: 100% !important;
            transition: background 0.2s ease, box-shadow 0.2s ease !important;
            letter-spacing: 0.2px !important;
        }

        .btn-delete:hover {
            background: #ffe4e6 !important;
            border-color: #fda4af !important;
            color: #9f1239 !important;
            box-shadow: 0 4px 12px rgba(190, 18, 60, 0.15) !important;
        }


        /* Payment Summary */
        .payment-summary {
            background: #f8fafc;
            border-radius: 16px;
            padding: 1.5rem;
            margin-top: 1rem;
            border: 1px solid #f1f5f9;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
        }

        .summary-row.total {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px dashed #cbd5e1;
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--primary-color);
        }

        /* Timeline */
        .timeline {
            margin-top: 2rem;
        }

        .timeline-item {
            position: relative;
            padding-left: 2rem;
            padding-bottom: 1.5rem;
            border-left: 2px solid #e2e8f0;
        }

        .timeline-item:last-child {
            border-left-color: transparent;
        }

        .timeline-point {
            position: absolute;
            left: -6px;
            top: 4px;
            width: 12px;
            height: 12px;
            background: #850f0f;
            border-radius: 50%;
            box-shadow: 0 0 0 4px rgba(133, 15, 15, 0.15);
            border: 2px solid white;
        }

        .timeline-item.active .timeline-point {
            /* This rule is now redundant as the base timeline-point is orange */
            /* background: var(--primary-color); */
        }

        .timeline-content {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .timeline-time {
            color: var(--text-light);
            font-size: 0.75rem;
            margin-top: 0.25rem;
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
            <a href="{{ route('admin.reports') }}" class="menu-item">
                <i class="ph ph-file-text"></i> Reports
            </a>
            <a href="{{ route('home') }}" class="menu-item" target="_blank" rel="noopener noreferrer">
                <i class="ph ph-globe"></i> Visit Website
            </a>
        </div>
    </div>

    <main class="admin-main">
        <div class="top-navbar">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <button id="sidebarToggle" class="menu-toggle" style="display: none; background: #fff; border: 1px solid var(--border); border-radius: 8px; width: 40px; height: 40px; align-items: center; justify-content: center; color: var(--text-main); cursor: pointer; font-size: 1.25rem;">
                    <i class="ph ph-list"></i>
                </button>
                <a href="{{ route('admin.bookings') }}" class="btn-back"><i class="ph ph-arrow-left"></i></a>
            </div>
            <div>
                <h1 id="pdf-header" style="font-size: 1.15rem; font-weight: 700; color: var(--text-main); margin: 0;">Booking Details</h1>
            </div>
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-left: auto;">
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

        @if(session('success'))
            <div style="background: #dcfce7; color: #166534; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #bbf7d0; display: flex; align-items: center; gap: 0.5rem;">
                <i class="ph-bold ph-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #fecaca; display: flex; align-items: center; gap: 0.5rem;">
                <i class="ph-bold ph-warning-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        @if(session('info'))
            <div style="background: #eff6ff; color: #1e40af; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #bfdbfe; display: flex; align-items: center; gap: 0.5rem;">
                <i class="ph-bold ph-info"></i>
                {{ session('info') }}
            </div>
        @endif

        <div class="details-grid">
            <div class="grid-left">
                <div class="details-card">
                    <div class="details-section-header">
                        <h3>Customer Information (Reference: {{ $booking->reference_id }})</h3>
                        <div style="display: flex; gap: 0.75rem;">
                            <div class="status-badge badge-{{ str_replace(' ', '-', strtolower($booking->approval_status)) }}">
                                <i class="ph-fill ph-{{ $booking->approval_status == 'Approved' || $booking->approval_status == 'Principal Approved' || $booking->approval_status == 'Approved by Principal' ? 'check-circle' : ($booking->approval_status == 'Pending' ? 'clock' : 'x-circle') }}"></i>
                                {{ ucfirst($booking->approval_status) }}
                            </div>
                            <div class="status-badge badge-{{ strtolower($booking->payment_status) }}">
                                <i class="ph-fill ph-{{ $booking->payment_status == 'Paid' ? 'check-circle' : ($booking->payment_status == 'Pending' ? 'clock' : 'x-circle') }}"></i>
                                Payment: {{ $booking->payment_status }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Full Name</span>
                            <span class="info-value">{{ $booking->name }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Nationality</span>
                            <span class="info-value">{{ $booking->nationality }}</span>
                        </div>
                        @if($booking->nationality === 'Non-Indian')
                        <div class="info-item">
                            <span class="info-label">Passport Number</span>
                            <span class="info-value">{{ $booking->passport_number ?: 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Visa Number</span>
                            <span class="info-value">{{ $booking->visa_number ?: 'N/A' }}</span>
                        </div>
                        @endif
                        <div class="info-item">
                            <span class="info-label">User Type</span>
                            <span class="info-value">{{ $booking->user_type }}</span>
                        </div>
                        @if($booking->user_type === 'Student')
                        @if($booking->residence_status)
                        <div class="info-item">
                            <span class="info-label">Residence Status</span>
                            <span class="info-value">{{ ucwords($booking->residence_status) }}</span>
                        </div>
                        @endif
                        <div class="info-item">
                            <span class="info-label">Academic Details</span>
                            <span class="info-value">{{ $booking->level }} | {{ $booking->stream }} | {{ $booking->department }}</span>
                        </div>
                        @elseif($booking->user_type === 'Staff')
                        <div class="info-item">
                            <span class="info-label">Staff Details</span>
                            <span class="info-value">{{ $booking->level }} ({{ $booking->department }})</span>
                        </div>
                        @else
                        <div class="info-item">
                            <span class="info-label">Department/Unit</span>
                            <span class="info-value">{{ $booking->department ?: 'N/A' }}</span>
                        </div>
                        @endif
                        <div class="info-item">
                            <span class="info-label">Email Address</span>
                            <span class="info-value">{{ $booking->email }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Phone Number</span>
                            <span class="info-value">{{ $booking->phone }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Primary Guest</span>
                            <span class="info-value">{{ $booking->primary_guest_name ?: 'Self' }} ({{ $booking->no_of_persons }} {{ Str::plural('Person', $booking->no_of_persons) }})</span>
                        </div>
                        @if($booking->referral_attachment)
                        <div class="info-item" style="grid-column: 1 / -1; margin-top: 1rem;">
                            <span class="info-label">Referral Attachment</span>
                            <span class="info-value">
                                <a href="{{ asset('storage/' . $booking->referral_attachment) }}" target="_blank" style="display: flex; align-items: center; gap: 0.5rem; text-decoration: none; color: #850f0f; font-weight: 700;">
                                    <i class="ph-bold ph-file-arrow-down" style="font-size: 1.25rem;"></i>
                                    View Attachment
                                </a>
                            </span>
                        </div>
                        @endif
                        @if($booking->passport_attachment)
                        <div class="info-item" style="grid-column: 1 / -1; margin-top: 1rem;">
                            <span class="info-label">Passport Scanned Copy</span>
                            <span class="info-value">
                                <a href="{{ asset('storage/' . $booking->passport_attachment) }}" target="_blank" style="display: flex; align-items: center; gap: 0.5rem; text-decoration: none; color: #850f0f; font-weight: 700;">
                                    <i class="ph-bold ph-file-arrow-down" style="font-size: 1.25rem;"></i>
                                    View Passport Copy
                                </a>
                            </span>
                        </div>
                        @endif
                        @if($booking->visa_attachment)
                        <div class="info-item" style="grid-column: 1 / -1; margin-top: 1rem;">
                            <span class="info-label">Visa Scanned Copy</span>
                            <span class="info-value">
                                <a href="{{ asset('storage/' . $booking->visa_attachment) }}" target="_blank" style="display: flex; align-items: center; gap: 0.5rem; text-decoration: none; color: #850f0f; font-weight: 700;">
                                    <i class="ph-bold ph-file-arrow-down" style="font-size: 1.25rem;"></i>
                                    View Visa Copy
                                </a>
                            </span>
                        </div>
                        @endif
                        @if($booking->passport_visa_attachment && !$booking->passport_attachment && !$booking->visa_attachment)
                        <div class="info-item" style="grid-column: 1 / -1; margin-top: 1rem;">
                            <span class="info-label">Passport & Visa Scanned Copy</span>
                            <span class="info-value">
                                <a href="{{ asset('storage/' . $booking->passport_visa_attachment) }}" target="_blank" style="display: flex; align-items: center; gap: 0.5rem; text-decoration: none; color: #850f0f; font-weight: 700;">
                                    <i class="ph-bold ph-file-arrow-down" style="font-size: 1.25rem;"></i>
                                    View Passport & Visa copy
                                </a>
                            </span>
                        </div>
                        @endif
                        @if($booking->booking_reason)
                        <div class="info-item" style="grid-column: 1 / -1; margin-top: 1rem;">
                            <span class="info-label">Purpose</span>
                            <span class="info-value" style="background: #f8fafc; border: 1px solid var(--border); padding: 1rem; border-radius: 10px; font-weight: 500; display: block; line-height: 1.6; color: #334155;">
                                {{ $booking->booking_reason }}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Grouped Rooms Section -->
                <div class="details-card">
                    <div class="details-section-header">
                        <h3>Rooms under this booking (Group: {{ $booking->reference_id }})</h3>
                        <button type="button" class="btn-download-pdf" style="margin-left: auto;" onclick="openAddRoomModal()">
                            <i class="ph-bold ph-plus"></i> Add Room
                        </button>
                    </div>

                    <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
                        <thead>
                            <tr style="border-bottom: 2px solid var(--border); text-align: left; font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">
                                <th style="padding: 10px 5px;">Room Name</th>
                                <th style="padding: 10px 5px;">Schedule</th>
                                <th style="padding: 10px 5px;">Price</th>
                                <th style="padding: 10px 5px;">Status</th>
                                <th style="padding: 10px 5px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- The current room -->
                            <tr style="border-bottom: 1px solid var(--border); font-size: 0.9rem;">
                                <td style="padding: 12px 5px; font-weight: 700;">{{ $booking->room_name }} <span style="font-size: 0.75rem; color: #64748b; font-weight: normal;">(Main)</span></td>
                                <td style="padding: 12px 5px;">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M, Y') }}<br><span style="font-size: 0.75rem; color: #64748b;">{{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}</span></td>
                                <td style="padding: 12px 5px; font-weight: 600;">₹{{ number_format($booking->total_price, 2) }}</td>
                                <td style="padding: 12px 5px;">
                                    <span class="status-badge badge-{{ str_replace(' ', '-', strtolower($booking->approval_status)) }}" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                                        {{ $booking->approval_status }}
                                    </span>
                                </td>
                                <td style="padding: 12px 5px;">-</td>
                            </tr>
                            <!-- Other rooms under same reference_id -->
                            @foreach($relatedBookings as $rel)
                            <tr style="border-bottom: 1px solid var(--border); font-size: 0.9rem;">
                                <td style="padding: 12px 5px; font-weight: 700;">
                                    <a href="{{ route('admin.bookings.show', $rel->id) }}" style="color: var(--primary-color); text-decoration: none; hover: underline;">
                                        {{ $rel->room_name }}
                                    </a>
                                </td>
                                <td style="padding: 12px 5px;">{{ \Carbon\Carbon::parse($rel->booking_date)->format('d M, Y') }}<br><span style="font-size: 0.75rem; color: #64748b;">{{ \Carbon\Carbon::parse($rel->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($rel->end_time)->format('h:i A') }}</span></td>
                                <td style="padding: 12px 5px; font-weight: 600;">₹{{ number_format($rel->total_price, 2) }}</td>
                                <td style="padding: 12px 5px;">
                                    <span class="status-badge badge-{{ str_replace(' ', '-', strtolower($rel->approval_status)) }}" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                                        {{ $rel->approval_status }}
                                    </span>
                                </td>
                                <td style="padding: 12px 5px;">
                                    @if($rel->approval_status === 'Approved' && $rel->payment_status == 'Pending')
                                        <form action="{{ route('admin.bookings.resend', $rel->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" style="background: none; border: none; color: #3b82f6; cursor: pointer; font-weight: 600; font-size: 0.75rem; text-decoration: underline; padding: 0;">Send Link</button>
                                        </form>
                                    @elseif($rel->approval_status !== 'Approved' && !in_array($rel->approval_status, ['Rejected', 'Cancelled']))
                                        <form action="{{ route('admin.bookings.approve', $rel->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" style="background: none; border: none; color: #850f0f; cursor: pointer; font-weight: 600; font-size: 0.75rem; text-decoration: underline; padding: 0;">Approve</button>
                                        </form>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="details-card">
                    <div class="details-section-header">
                        <h3>Workspace & Schedule</h3>
                    </div>
                    
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Workspace Name</span>
                            <span class="info-value">{{ $booking->room_name }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Booking Date</span>
                            <span class="info-value">{{ \Carbon\Carbon::parse($booking->booking_date)->format('F d, Y') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Check-In</span>
                            <span class="info-value">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M, Y') }} | {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Check-Out</span>
                            <span class="info-value">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M, Y') }} | {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Total Duration</span>
                            <span class="info-value">
                                @php
                                    $start = \Carbon\Carbon::parse($booking->start_time);
                                    $end = \Carbon\Carbon::parse($booking->end_time);
                                    $hours = $start->diffInHours($end);
                                @endphp
                                {{ $hours }} {{ Str::plural('Hour', $hours) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid-right">
                <div class="details-card">
                    <div class="details-section-header" style="margin-bottom: 1.5rem;">
                        <h3>Payment Details</h3>
                    </div>
                    
                    <div class="info-item" style="margin-bottom: 1.5rem;">
                        <span class="info-label">Transaction ID</span>
                        @if($booking->razorpay_payment_id)
                            <span style="
                                display: inline-flex; align-items: center; gap: 0.5rem;
                                background: #f0fdf4; color: #166534;
                                border: 1px solid #bbf7d0;
                                padding: 0.45rem 0.9rem; border-radius: 8px;
                                font-family: 'JetBrains Mono', 'Courier New', monospace;
                                font-size: 0.82rem; font-weight: 600; letter-spacing: 0.5px;
                                word-break: break-all; margin-top: 0.25rem;
                            ">
                                <i class="ph-fill ph-check-circle" style="color: #16a34a; font-size: 1rem; flex-shrink: 0;"></i>
                                {{ $booking->razorpay_payment_id }}
                            </span>
                        @else
                            <span style="
                                display: inline-flex; align-items: center; gap: 0.5rem;
                                background: #fffbeb; color: #92400e;
                                border: 1px solid #fde68a;
                                padding: 0.45rem 0.9rem; border-radius: 8px;
                                font-size: 0.82rem; font-weight: 700;
                                text-transform: uppercase; letter-spacing: 0.6px;
                                margin-top: 0.25rem;
                            ">
                                <i class="ph-fill ph-clock" style="color: #d97706; font-size: 1rem; flex-shrink: 0;"></i>
                                Not Yet Assigned
                            </span>
                        @endif
                    </div>

                    <div class="payment-summary">
                        @php
                            // $gstRate is shared globally via AppServiceProvider
                            $gstFactor = 1 + ($gstRate / 100);
                            $subtotal = $booking->total_price / $gstFactor;
                            $gstAmount = $booking->total_price - $subtotal;
                        @endphp
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span>₹{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="summary-row">
                            <span>GST ({{ $gstRate }}%)</span>
                            <span>₹{{ number_format($gstAmount, 2) }}</span>
                        </div>
                        <div class="summary-row total">
                            <span>{{ $booking->payment_status === 'Paid' ? 'Amount Paid' : 'Amount to be Paid' }}</span>
                            <span>₹{{ number_format($booking->total_price, 2) }}</span>
                        </div>
                    </div>

                    <div class="timeline">
                        <div class="timeline-item active">
                            <div class="timeline-point"></div>
                            <div class="timeline-content">Booking Created</div>
                            <div class="timeline-time">{{ $booking->created_at->format('M d, Y h:i A') }}</div>
                        </div>
                        @if($booking->payment_status == 'Paid')
                        <div class="timeline-item active">
                            <div class="timeline-point"></div>
                            <div class="timeline-content">Payment Verified</div>
                            <div class="timeline-time">{{ $booking->updated_at->format('M d, Y h:i A') }}</div>
                        </div>
                        <div class="timeline-item active">
                            <div class="timeline-point"></div>
                            <div class="timeline-content">Booking Confirmed</div>
                            <div class="timeline-time">{{ $booking->updated_at->format('M d, Y h:i A') }}</div>
                        </div>
                        @endif
                    </div>
                </div>
                
                <div style="display: flex; flex-direction: column; gap: 1.25rem; padding: 0 1.5rem 2rem 1.5rem;">
                    @if($booking->approval_status === 'Pending' || $booking->approval_status === 'Principal Approved' || $booking->approval_status === 'Approved by Principal')
                        @if($booking->approval_status === 'Pending')
                            <div class="pulse-status" style="background: rgba(245, 158, 11, 0.1); height: 75px; border-radius: 12px; border: 1px solid rgba(245, 158, 11, 0.3); display: flex; align-items: center; justify-content: center; width: 100%;">
                                <div style="margin: 0; font-size: 0.875rem; color: #b45309; font-weight: 600; font-family: 'Inter', sans-serif; display: flex; align-items: center; gap: 10px; line-height: 1;">
                                    <i class="ph-fill ph-hourglass" style="font-size: 1.1rem; position: relative; top: -1px;"></i>
                                    <span>Waiting for Principal Approval</span>
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('admin.bookings.approve', $booking->id) }}" method="POST" onsubmit="event.preventDefault(); showConfirmModal('approve', this);">
                            @csrf
                            <button type="submit" class="btn-approve" 
                                    style="width: 100%;"
                                    {{ $booking->approval_status === 'Pending' ? 'disabled' : '' }}>
                                <i class="ph-bold ph-paper-plane-tilt" style="color:#fff !important;"></i>
                                Approve & Send Payment Link
                            </button>
                        </form>
                        
                        <form action="{{ route('admin.bookings.reject', $booking->id) }}" method="POST" onsubmit="event.preventDefault(); showConfirmModal('reject', this);">
                            @csrf
                            <button type="submit" class="btn-reject" style="width: 100%;">
                                <i class="ph-bold ph-x"></i> Reject Proposal
                            </button>
                        </form>
                    @endif

                    @if($booking->approval_status === 'Approved' && $booking->payment_status == 'Pending')
                        <div style="background: rgba(59, 130, 246, 0.1); border-radius: 12px; border: 1px solid rgba(59, 130, 246, 0.2); padding: 1.25rem;">
                            <div style="font-size: 0.875rem; color: #1d4ed8; font-weight: 600; font-family: 'Inter', sans-serif; display: flex; align-items: center; gap: 10px; margin-bottom: 1rem;">
                                <i class="ph-fill ph-info" style="font-size: 1.1rem;"></i>
                                <span>Waiting for Online Payment</span>
                            </div>
                            
                            @php
                                $lastLink = \App\Models\PaymentLink::where('booking_id', $booking->id)->latest()->first();
                            @endphp

                            @if($lastLink)
                                <div style="font-size: 0.75rem; color: #64748b; margin-bottom: 1rem; padding: 0.75rem; background: white; border-radius: 8px; border: 1px solid #e2e8f0;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                                        <span>Link Status:</span>
                                        <strong style="color: {{ $lastLink->isValid() ? '#10b981' : '#ef4444' }}">
                                            {{ $lastLink->is_used ? 'Used' : ($lastLink->isExpired() ? 'Expired' : 'Active') }}
                                        </strong>
                                    </div>
                                    <div style="display: flex; justify-content: space-between;">
                                        <span>Expires:</span>
                                        <strong>{{ $lastLink->expires_at->format('M d, H:i') }}</strong>
                                    </div>
                                </div>
                            @endif

                            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                                <form action="{{ route('admin.bookings.resend', $booking->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-approve" style="background: #3b82f6 !important; box-shadow: 0 4px 14px rgba(59, 130, 246, 0.3) !important;">
                                        <i class="ph-bold ph-arrows-clockwise"></i> Resend Payment Link
                                    </button>
                                </form>

                                <form action="{{ route('admin.bookings.pay', $booking->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-mark-paid" style="background: #64748b !important; box-shadow: 0 4px 14px rgba(100, 116, 139, 0.3) !important;">
                                        <i class="ph-bold ph-hand-coins"></i> Mark as Paid (Counter)
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                    <div style="height: 1px; background: #e2e8f0; margin: 0.25rem 0;"></div>

                    <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST" onsubmit="event.preventDefault(); showConfirmModal('delete', this);">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete">
                            <i class="ph-bold ph-trash"></i> Delete Booking
                        </button>
                    </form>
                </div>
            </div>
        </div> <!-- end admin-body -->
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

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', (e) => {
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

<!-- Add Room Modal -->
<div id="addRoomModal" class="confirm-modal-overlay" style="display: none;">
    <div class="confirm-modal-content" style="max-width: 500px; text-align: left;">
        <h3 style="margin-bottom: 1.5rem; text-align: center; border-bottom: 1px solid var(--border); padding-bottom: 0.75rem;">Add Room to Booking</h3>
        
        <form action="{{ route('admin.bookings.add_room', $booking->id) }}" method="POST">
            @csrf
            
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.5rem;">Select Room *</label>
                <select name="room_name" required style="width: 100%; height: 42px; border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 0 10px; font-weight: 600; background: white;">
                    <option value="" disabled selected>Select Room</option>
                    <optgroup label="Guest Wing (Standard)">
                        <option value="standard-1">Standard Room 1</option>
                        <option value="standard-2">Standard Room 2</option>
                        <option value="standard-3">Standard Room 3</option>
                        <option value="standard-4">Standard Room 4</option>
                        <option value="standard-5">Standard Room 5</option>
                        <option value="standard-6">Standard Room 6</option>
                        <option value="standard-7">Standard Room 7</option>
                        <option value="standard-8">Standard Room 8</option>
                    </optgroup>
                    <optgroup label="Executive Wing (Advance)">
                        <option value="101">Executive Room 101</option>
                        <option value="102">Executive Room 102</option>
                        <option value="103">Executive Room 103</option>
                        <option value="104">Executive Room 104</option>
                        <option value="201">Executive Room 201</option>
                        <option value="203">Executive Room 203</option>
                        <option value="204">Executive Room 204</option>
                        <option value="205">Executive Room 205</option>
                        <option value="206">Executive Room 206</option>
                        <option value="207">Executive Room 207</option>
                    </optgroup>
                    <optgroup label="Luxury Wing">
                        <option value="suite-room">Luxury Suite Room</option>
                    </optgroup>
                    <optgroup label="Conference Wing">
                        <option value="conference-hall">Conference Hall</option>
                        <option value="conference-room">Conference Room</option>
                        <option value="glass-room">Glass Room</option>
                    </optgroup>
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.5rem;">Check-In Date/Time *</label>
                    <input type="datetime-local" name="clock_in" required style="width: 100%; height: 42px; border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 0 10px; font-weight: 600;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.5rem;">Check-Out Date/Time *</label>
                    <input type="datetime-local" name="clock_out" required style="width: 100%; height: 42px; border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 0 10px; font-weight: 600;">
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.5rem;">Number of Persons *</label>
                <input type="number" name="no_of_persons" value="1" min="1" max="60" required style="width: 100%; height: 42px; border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 0 10px; font-weight: 600;">
            </div>

            <div class="confirm-modal-footer">
                <button type="button" class="confirm-btn-cancel" onclick="closeAddRoomModal()">Cancel</button>
                <button type="submit" class="confirm-btn-confirm" style="background: var(--primary-color);">Add Room</button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddRoomModal() {
    document.getElementById("addRoomModal").style.display = "flex";
}
function closeAddRoomModal() {
    document.getElementById("addRoomModal").style.display = "none";
}
</script>
</body>
</html>

