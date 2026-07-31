<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Guest Booking - Admin</title>
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
            background: rgba(var(--primary-rgb), 0.08);
            color: var(--primary-color);
        }

        .menu-item.active {
            background: rgba(var(--primary-rgb), 0.1);
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
            padding: 2.5rem; padding-bottom: 1.5rem; max-width: 1100px; width: 100%; 
            margin: 0 auto; box-sizing: border-box; flex: 1;
        }

        @media (max-width: 768px) {
            .admin-main { margin-left: 0; width: 100%; }
            .admin-body { padding: 1.25rem; }
        }

        /* Form Card */
        .form-card {
            background: white;
            border-radius: 16px;
            border: 1px solid var(--border);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            padding: 2.5rem;
            margin-bottom: 2rem;
        }

        .form-title-group {
            border-bottom: 1px solid var(--border);
            padding-bottom: 1.25rem;
            margin-bottom: 2rem;
        }

        .form-title-group h2 {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0 0 0.4rem 0;
        }

        .form-title-group p {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin: 0;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        @media (max-width: 640px) {
            .form-grid {
                grid-template-columns: 1fr;
                gap: 1.25rem;
            }
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-group label {
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--text-main);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper i {
            position: absolute;
            left: 1.1rem;
            color: var(--text-muted);
            font-size: 1.25rem;
            pointer-events: none;
        }

        .form-input {
            width: 100%;
            padding: 0.8rem 1rem 0.8rem 2.8rem;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            font-size: 0.95rem;
            outline: none;
            transition: all 0.2s ease;
            background: #f8fafc;
            color: var(--text-main);
        }

        .form-input:focus {
            border-color: var(--primary-color);
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(var(--primary-rgb), 0.1);
        }

        .form-textarea {
            width: 100%;
            padding: 1rem;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            font-size: 0.95rem;
            outline: none;
            transition: all 0.2s ease;
            background: #f8fafc;
            color: var(--text-main);
            resize: vertical;
            min-height: 100px;
        }

        .form-textarea:focus {
            border-color: var(--primary-color);
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(var(--primary-rgb), 0.1);
        }

        /* ── Designation Radio Cards Grid ── */
        .radio-cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
            gap: 1rem;
            margin-top: 0.25rem;
        }

        .radio-card {
            background: #f8fafc;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            padding: 1.25rem 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            user-select: none;
        }

        .radio-card:hover {
            border-color: var(--primary-color);
            background: rgba(var(--primary-rgb), 0.02);
        }

        .radio-card.selected {
            border-color: var(--primary-color);
            background: rgba(var(--primary-rgb), 0.04);
            box-shadow: 0 4px 12px rgba(var(--primary-rgb), 0.08);
        }

        .radio-card-icon {
            font-size: 1.85rem;
            color: var(--text-muted);
            margin-bottom: 0.75rem;
            transition: color 0.2s ease;
        }

        .radio-card.selected .radio-card-icon {
            color: var(--primary-color);
        }

        .radio-card-title {
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--text-main);
        }

        .radio-card-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--primary-color);
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            opacity: 0;
            transform: scale(0.8);
            transition: all 0.2s ease;
        }

        .radio-card.selected .radio-card-badge {
            opacity: 1;
            transform: scale(1);
        }

        /* ── Room Category Tabs & Visual Grids ── */
        .tabs-container {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .tab-btn {
            background: #f1f5f9;
            border: 1px solid var(--border);
            padding: 0.6rem 1.25rem;
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--text-muted);
            cursor: pointer;
            border-radius: 10px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .tab-btn:hover {
            color: var(--primary-color);
            background: rgba(var(--primary-rgb), 0.05);
            border-color: var(--primary-color);
        }

        .tab-btn.active {
            color: #ffffff;
            background: var(--primary-color);
            border-color: var(--primary-color);
            box-shadow: 0 4px 10px rgba(var(--primary-rgb), 0.25);
        }

        .rooms-panel {
            display: none;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .rooms-panel.active {
            display: grid;
        }

        .room-card-btn {
            background: #f8fafc;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            padding: 1.1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            cursor: pointer;
            transition: all 0.25s ease;
            user-select: none;
        }

        .room-card-btn:hover {
            border-color: var(--primary-color);
            background: rgba(var(--primary-rgb), 0.02);
        }

        .room-card-btn.selected {
            border-color: var(--primary-color);
            background: rgba(var(--primary-rgb), 0.04);
            box-shadow: 0 4px 10px rgba(var(--primary-rgb), 0.06);
        }

        .room-card-btn.booked {
            background: #fef2f2;
            border-color: #fee2e2;
            cursor: not-allowed;
            opacity: 0.8;
        }

        .room-card-btn.booked i {
            color: #ef4444;
        }

        .room-card-btn.booked .room-card-name {
            color: #991b1b;
        }

        .room-card-btn.booked .room-card-desc {
            color: #ef4444;
            font-weight: 600;
        }

        .room-card-btn i {
            font-size: 1.75rem;
            color: var(--text-muted);
            transition: color 0.2s ease;
        }

        .room-card-btn.selected i {
            color: var(--primary-color);
        }

        .room-card-info {
            display: flex;
            flex-direction: column;
            text-align: left;
        }

        .room-card-name {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text-main);
        }

        .room-card-desc {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 0.15rem;
        }

        /* Custom Alert Modal Styles */
        .custom-alert-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            transition: opacity 0.25s ease;
        }

        .custom-alert-overlay.active {
            display: flex;
            opacity: 1;
        }

        .custom-alert-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 2.25rem 2rem;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            text-align: center;
            transform: scale(0.9);
            transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
            border: 1px solid var(--border);
        }

        .custom-alert-overlay.active .custom-alert-card {
            transform: scale(1);
        }

        .custom-alert-icon {
            width: 64px;
            height: 64px;
            background: #fef2f2;
            color: #ef4444;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 1.25rem auto;
            box-shadow: 0 4px 10px rgba(239, 68, 68, 0.1);
        }

        .custom-alert-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0 0 0.5rem 0;
        }

        .custom-alert-message {
            font-size: 0.92rem;
            color: var(--text-muted);
            line-height: 1.5;
            margin: 0 0 1.75rem 0;
        }

        .custom-alert-btn {
            width: 100%;
            padding: 0.75rem 1.5rem;
            background: var(--primary-color);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .custom-alert-btn:hover {
            filter: brightness(90%);
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        /* Buttons */
        .btn-group {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 2rem;
            border-top: 1px solid var(--border);
            padding-top: 1.5rem;
        }

        .btn-submit {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--primary-color);
            color: #ffffff;
            padding: 0.8rem 2.2rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.95rem;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
            transition: all 0.2s ease;
        }

        .btn-submit:hover {
            background: var(--primary-color);
            filter: brightness(90%);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }

        .btn-cancel {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #ffffff;
            color: var(--text-main);
            padding: 0.8rem 2.2rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            border: 1px solid var(--border);
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-cancel:hover {
            background: #f1f5f9;
            color: #0f172a;
        }

        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s ease; z-index: 1000; }
            .sidebar.open { transform: translateX(0); }
            .admin-main { margin-left: 0 !important; width: 100% !important; }
            .top-navbar { padding: 0 1rem !important; }
            #sidebarToggle { display: flex !important; }
        }

        /* Profile Menu */
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

        @media (max-width: 640px) {
            .form-card {
                padding: 1.25rem !important;
            }
            .btn-group {
                flex-direction: column-reverse !important;
                gap: 0.75rem !important;
                align-items: stretch !important;
            }
            .btn-submit, .btn-cancel {
                width: 100% !important;
                justify-content: center !important;
                padding: 0.8rem 1.5rem !important;
            }
        }
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
            <a href="{{ route('admin.college-guest') }}" class="menu-item active">
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
                    <i class="ph-bold ph-user-gear" style="color: var(--primary-color); margin-right: 0.4rem;"></i>
                    College Guests
                </div>
            </div>
            <div style="display: flex; gap: 1rem; align-items: center;">
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

            @if (isset($errors) && $errors->any())
                <div style="background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; border: 1px solid #fecaca;">
                    <ul style="margin: 0; padding-left: 1.25rem; font-size: 0.9rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-card">
                <div class="form-title-group">
                    <h2>Book a Room for College Guest</h2>
                    <p>Create a booking directly for institutional guests, former principals, or visitors with zero charges and instant confirmation.</p>
                </div>

                <form action="{{ route('admin.college-guest.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-grid">
                        <!-- Guest Name -->
                        <div class="form-group">
                            <label for="name">Guest Name</label>
                            <div class="input-wrapper">
                                <i class="ph ph-user"></i>
                                <input type="text" id="name" name="name" class="form-input" placeholder="e.g. Dr. John Doe" value="{{ old('name') }}" required>
                            </div>
                        </div>

                        <!-- Designation (Visual Select Grid) -->
                        <div class="form-group full-width">
                            <label>Designation / Category</label>
                            <input type="hidden" id="designation" name="designation" value="{{ old('designation') }}" required>
                            <div class="radio-cards-grid">
                                <div class="radio-card" data-value="Ex-Principal">
                                    <div class="radio-card-badge"><i class="ph-bold ph-check"></i></div>
                                    <i class="ph ph-graduation-cap radio-card-icon"></i>
                                    <span class="radio-card-title">Ex-Principal</span>
                                </div>
                                <div class="radio-card" data-value="College Guest / VIP">
                                    <div class="radio-card-badge"><i class="ph-bold ph-check"></i></div>
                                    <i class="ph ph-crown radio-card-icon"></i>
                                    <span class="radio-card-title">College Guest / VIP</span>
                                </div>
                                <div class="radio-card" data-value="Board Member">
                                    <div class="radio-card-badge"><i class="ph-bold ph-check"></i></div>
                                    <i class="ph ph-briefcase radio-card-icon"></i>
                                    <span class="radio-card-title">Board Member</span>
                                </div>
                                <div class="radio-card" data-value="Guest Lecturer">
                                    <div class="radio-card-badge"><i class="ph-bold ph-check"></i></div>
                                    <i class="ph ph-book-open radio-card-icon"></i>
                                    <span class="radio-card-title">Guest Lecturer</span>
                                </div>
                                <div class="radio-card" data-value="Other College Guest">
                                    <div class="radio-card-badge"><i class="ph-bold ph-check"></i></div>
                                    <i class="ph ph-user radio-card-icon"></i>
                                    <span class="radio-card-title">Other Guest</span>
                                </div>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label for="email">Guest Email</label>
                            <div class="input-wrapper">
                                <i class="ph ph-envelope"></i>
                                <input type="email" id="email" name="email" class="form-input" placeholder="e.g. principal.guest@example.com" value="{{ old('email') }}" required>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <div class="input-wrapper">
                                <i class="ph ph-phone"></i>
                                <input type="tel" id="phone" name="phone" class="form-input" placeholder="e.g. +91 98765 43210" value="{{ old('phone') }}" required>
                            </div>
                        </div>

                        <!-- Room Selection (Visual Tabbed selector) -->
                        <div class="form-group full-width">
                            <label>Select Room / Workspace</label>
                            <input type="hidden" id="room_name" name="room_name" value="{{ old('room_name') }}" required>
                            
                            <!-- Search & Tabs -->
                            <div style="display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap; margin-bottom: 0.5rem; margin-top: 0.25rem;">
                                <div class="tabs-container">
                                    @php $tabIndex = 0; @endphp
                                    @foreach($rooms as $group => $items)
                                        <button type="button" class="tab-btn {{ $tabIndex === 0 ? 'active' : '' }}" data-tab="tab-{{ \Illuminate\Support\Str::slug($group) }}">
                                            @if(str_contains(strtolower($group), 'standard'))
                                                <i class="ph ph-bed"></i>
                                            @elseif(str_contains(strtolower($group), 'advance'))
                                                <i class="ph ph-sparkle"></i>
                                            @else
                                                <i class="ph ph-users-four"></i>
                                            @endif
                                            {{ $group }}
                                        </button>
                                        @php $tabIndex++; @endphp
                                    @endforeach
                                </div>
                                
                                <!-- Search Bar -->
                                <div class="input-wrapper" style="width: 250px; min-width: 200px;">
                                    <i class="ph ph-magnifying-glass"></i>
                                    <input type="text" id="roomSearch" class="form-input" placeholder="Search room..." style="padding: 0.5rem 1rem 0.5rem 2.6rem; font-size: 0.85rem; height: 38px;">
                                </div>
                            </div>

                            <!-- Room Panels -->
                            @php $panelIndex = 0; @endphp
                            @foreach($rooms as $group => $items)
                                <div class="rooms-panel {{ $panelIndex === 0 ? 'active' : '' }}" id="tab-{{ \Illuminate\Support\Str::slug($group) }}">
                                    @foreach($items as $value => $label)
                                        @php
                                            $isBooked = isset($bookedRooms[$value]);
                                            $desc = $isBooked ? 'Booked' : 'Available';
                                            $cleanLabel = $label;
                                            if (!$isBooked && preg_match('/\((.*?)\)/', $label, $matches)) {
                                                $desc = $matches[1];
                                                $cleanLabel = trim(str_replace($matches[0], '', $label));
                                            } elseif (preg_match('/\((.*?)\)/', $label, $matches)) {
                                                $cleanLabel = trim(str_replace($matches[0], '', $label));
                                            }

                                            // Determine capacity
                                            $capacity = 4; // Default fallback
                                            $normalizedVal = strtolower($value);
                                            if (str_contains(strtolower($group), 'standard')) {
                                                $capacity = 2;
                                            } elseif (str_contains(strtolower($group), 'advance')) {
                                                $capacity = 4;
                                            } else {
                                                if (str_contains($normalizedVal, 'conference')) {
                                                    $capacity = 60;
                                                } elseif (str_contains($normalizedVal, 'glass')) {
                                                    $capacity = 20;
                                                } elseif (str_contains($normalizedVal, 'suite')) {
                                                    $capacity = 4;
                                                }
                                            }
                                        @endphp
                                        <div class="room-card-btn {{ $isBooked ? 'booked' : '' }}" data-value="{{ $value }}" data-capacity="{{ $capacity }}">
                                            @if(str_contains(strtolower($group), 'standard'))
                                                <i class="ph ph-bed"></i>
                                            @elseif(str_contains(strtolower($group), 'advance'))
                                                <i class="ph ph-door"></i>
                                            @else
                                                <i class="ph ph-presentation"></i>
                                            @endif
                                            <div class="room-card-info">
                                                <span class="room-card-name">{{ $cleanLabel }}</span>
                                                <span class="room-card-desc" style="{{ $isBooked ? 'color: #ef4444; font-weight: 600;' : '' }}">{{ $desc }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @php $panelIndex++; @endphp
                            @endforeach
                        </div>

                        <!-- No. of Persons -->
                        <div class="form-group">
                            <label for="no_of_persons">Number of Guests</label>
                            <div class="input-wrapper">
                                <i class="ph ph-users"></i>
                                <input type="number" id="no_of_persons" name="no_of_persons" class="form-input" min="1" max="100" value="{{ old('no_of_persons', 1) }}" required>
                            </div>
                            <span id="capacity_limit_hint" style="font-size: 0.75rem; color: #64748b; font-weight: 500; margin-top: 4px; display: none;"></span>
                        </div>

                        <!-- Clock In (Date and Time) -->
                        <div class="form-group">
                            <label for="clock_in">Clock In Date & Time</label>
                            <div class="input-wrapper">
                                <i class="ph ph-calendar-check"></i>
                                <input type="datetime-local" id="clock_in" name="clock_in" class="form-input" value="{{ old('clock_in') }}" required style="padding-left: 2.8rem;">
                            </div>
                        </div>

                        <!-- Clock Out (Date and Time) -->
                        <div class="form-group">
                            <label for="clock_out">Clock Out Date & Time</label>
                            <div class="input-wrapper">
                                <i class="ph ph-calendar-x"></i>
                                <input type="datetime-local" id="clock_out" name="clock_out" class="form-input" value="{{ old('clock_out') }}" required style="padding-left: 2.8rem;">
                            </div>
                        </div>

                        <!-- Notes / Remarks -->
                        <div class="form-group full-width">
                            <label for="booking_reason">Purpose of Visit / Remarks</label>
                            <textarea id="booking_reason" name="booking_reason" class="form-textarea" placeholder="Describe the purpose of visit, booking approval authority, or special requirements...">{{ old('booking_reason') }}</textarea>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="btn-group">
                        <a href="{{ route('admin.bookings') }}" class="btn-cancel">Cancel</a>
                        <button type="submit" class="btn-submit">
                            <i class="ph ph-calendar-plus"></i> Create Booking
                        </button>
                    </div>
                </form>
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

        // Designation Selection
        const designationInput = document.getElementById('designation');
        const designationCards = document.querySelectorAll('.radio-card');
        
        designationCards.forEach(card => {
            card.addEventListener('click', () => {
                designationCards.forEach(c => c.classList.remove('selected'));
                card.classList.add('selected');
                designationInput.value = card.getAttribute('data-value');
            });
        });

        // Restore old selection if exists
        const oldDesignation = designationInput.value;
        if (oldDesignation) {
            const selectedCard = Array.from(designationCards).find(c => c.getAttribute('data-value') === oldDesignation);
            if (selectedCard) selectedCard.classList.add('selected');
        }

        // Room Selection Tabs Switching
        const tabBtns = document.querySelectorAll('.tab-btn');
        const roomPanels = document.querySelectorAll('.rooms-panel');

        function selectTab(btn) {
            tabBtns.forEach(b => b.classList.remove('active'));
            roomPanels.forEach(p => {
                p.classList.remove('active');
                p.style.display = 'none';
            });

            btn.classList.add('active');
            const targetPanel = document.getElementById(btn.getAttribute('data-tab'));
            if (targetPanel) {
                targetPanel.classList.add('active');
                targetPanel.style.display = 'grid';
            }
        }

        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Clear search input and restore card displays when switching tabs
                const roomSearch = document.getElementById('roomSearch');
                if (roomSearch) roomSearch.value = '';
                
                const roomCards = document.querySelectorAll('.room-card-btn');
                roomCards.forEach(c => c.style.display = 'flex');

                selectTab(btn);
            });
        });

        // Initialize tab panels visibility on load
        const activeTabBtn = document.querySelector('.tab-btn.active');
        if (activeTabBtn) {
            selectTab(activeTabBtn);
        }

        // Custom Alert Modal Helpers
        const customAlert = document.getElementById('customAlert');
        const customAlertMessage = document.getElementById('customAlertMessage');
        const customAlertBtn = document.getElementById('customAlertBtn');

        function showAlert(message) {
            if (customAlertMessage && customAlert) {
                customAlertMessage.textContent = message;
                customAlert.classList.add('active');
            }
        }

        function closeAlert() {
            if (customAlert) {
                customAlert.classList.remove('active');
            }
        }

        if (customAlertBtn) {
            customAlertBtn.addEventListener('click', closeAlert);
        }

        if (customAlert) {
            customAlert.addEventListener('click', (e) => {
                if (e.target === customAlert) closeAlert();
            });
        }

        // Room Card Selection
        const roomNameInput = document.getElementById('room_name');
        const roomCards = document.querySelectorAll('.room-card-btn');
        const noOfPersonsInput = document.getElementById('no_of_persons');
        const capacityHint = document.getElementById('capacity_limit_hint');

        roomCards.forEach(card => {
            card.addEventListener('click', () => {
                if (card.classList.contains('booked')) {
                    showAlert('This room is already booked for the selected date and time range.');
                    return;
                }
                roomCards.forEach(c => c.classList.remove('selected'));
                card.classList.add('selected');
                roomNameInput.value = card.getAttribute('data-value');

                // Update dynamic capacity limit
                const capacity = parseInt(card.getAttribute('data-capacity')) || 4;
                if (noOfPersonsInput) {
                    noOfPersonsInput.max = capacity;
                    if (parseInt(noOfPersonsInput.value) > capacity) {
                        noOfPersonsInput.value = capacity;
                    }
                }
                if (capacityHint) {
                    capacityHint.textContent = `Maximum capacity for this room is ${capacity} ${capacity === 1 ? 'guest' : 'guests'}.`;
                    capacityHint.style.display = 'block';
                }
            });
        });

        if (noOfPersonsInput) {
            noOfPersonsInput.addEventListener('input', () => {
                const max = parseInt(noOfPersonsInput.max) || 100;
                const val = parseInt(noOfPersonsInput.value) || 0;
                if (val > max) {
                    noOfPersonsInput.value = max;
                    showAlert(`Number of guests cannot exceed the room capacity of ${max}.`);
                }
            });
        }

        // Dynamic Availability Checker
        const clockInInput = document.getElementById('clock_in');
        const clockOutInput = document.getElementById('clock_out');

        function checkAvailability() {
            const clockIn = clockInInput.value;
            const clockOut = clockOutInput.value;

            if (!clockIn || !clockOut) return;

            // Basic validation
            if (new Date(clockOut) <= new Date(clockIn)) {
                return;
            }

            fetch('{{ route("admin.college-guest.check-availability") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    clock_in: clockIn,
                    clock_out: clockOut
                })
            })
            .then(res => res.json())
            .then(data => {
                const bookedRooms = data.booked_rooms || [];
                
                roomCards.forEach(card => {
                    const roomVal = card.getAttribute('data-value');
                    const isBooked = bookedRooms.includes(roomVal);
                    
                    if (isBooked) {
                        card.classList.add('booked');
                        card.classList.remove('selected');
                        const descEl = card.querySelector('.room-card-desc');
                        descEl.textContent = 'Booked';
                        descEl.style.color = '#ef4444';
                        descEl.style.fontWeight = '600';
                        
                        // Clear selected value if currently chosen
                        if (roomNameInput.value === roomVal) {
                            roomNameInput.value = '';
                        }
                    } else {
                        card.classList.remove('booked');
                        const descEl = card.querySelector('.room-card-desc');
                        descEl.style.color = '';
                        descEl.style.fontWeight = '';
                        
                        // Set standard text
                        let defaultDesc = 'Available';
                        const parentPanel = card.closest('.rooms-panel');
                        if (parentPanel && parentPanel.id.includes('advance')) {
                            if (roomVal === '101') defaultDesc = 'College Guest Room';
                            else defaultDesc = 'Premium Guest Room';
                        }
                        descEl.textContent = defaultDesc;
                    }
                });
            })
            .catch(err => console.error('Availability check failed:', err));
        }

        if (clockInInput && clockOutInput) {
            clockInInput.addEventListener('change', checkAvailability);
            clockOutInput.addEventListener('change', checkAvailability);
        }

        // Restore old room selection if exists
        const oldRoom = roomNameInput.value;
        if (oldRoom) {
            const selectedCard = Array.from(roomCards).find(c => c.getAttribute('data-value') === oldRoom);
            if (selectedCard) {
                selectedCard.classList.add('selected');
                // Also activate the correct tab containing the selected room
                const panel = selectedCard.closest('.rooms-panel');
                if (panel) {
                    roomPanels.forEach(p => {
                        p.classList.remove('active');
                        p.style.display = 'none';
                    });
                    panel.classList.add('active');
                    panel.style.display = 'grid';
                    tabBtns.forEach(b => {
                        if (b.getAttribute('data-tab') === panel.id) {
                            b.classList.add('active');
                        } else {
                            b.classList.remove('active');
                        }
                    });
                }
            }
        }

        // Room Search Filter
        const roomSearch = document.getElementById('roomSearch');
        if (roomSearch) {
            roomSearch.addEventListener('input', (e) => {
                const query = e.target.value.toLowerCase().trim();
                
                roomCards.forEach(card => {
                    const name = card.querySelector('.room-card-name').textContent.toLowerCase();
                    const desc = card.querySelector('.room-card-desc').textContent.toLowerCase();
                    
                    if (name.includes(query) || desc.includes(query)) {
                        card.style.display = 'flex';
                    } else {
                        card.style.display = 'none';
                    }
                });
                
                // If query is present, we temporarily show all panels containing matches
                if (query.length > 0) {
                    roomPanels.forEach(panel => {
                        const hasMatches = Array.from(panel.querySelectorAll('.room-card-btn')).some(c => c.style.display === 'flex');
                        if (hasMatches) {
                            panel.style.display = 'grid';
                        } else {
                            panel.style.display = 'none';
                        }
                    });
                } else {
                    // Restore tab view
                    const activeBtn = document.querySelector('.tab-btn.active');
                    if (activeBtn) {
                        roomPanels.forEach(panel => {
                            if (panel.id === activeBtn.getAttribute('data-tab')) {
                                panel.style.display = 'grid';
                            } else {
                                panel.style.display = 'none';
                            }
                        });
                    }
                }
            });
        }

        // Layout Fix: force admin-main to never exceed viewport minus sidebar
        (function fixAdminLayout() {
            const SIDEBAR_W = 260;
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
    <!-- Custom Alert Modal -->
    <div class="custom-alert-overlay" id="customAlert">
        <div class="custom-alert-card">
            <div class="custom-alert-icon">
                <i class="ph-bold ph-warning-circle"></i>
            </div>
            <h3 class="custom-alert-title">Room Unavailable</h3>
            <p class="custom-alert-message" id="customAlertMessage">This room is already booked for the selected date and time range.</p>
            <button type="button" class="custom-alert-btn" id="customAlertBtn">Got it</button>
        </div>
    </div>
</body>
</html>
