<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Settings - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <style>
        :root {
            --sidebar-width: 260px;
            --bg-color: #f8fafc;
            --primary-color: {{ $settings['primary_color'] ?? '#850f0f' }};
            --secondary-color: {{ $settings['secondary_color'] ?? '#001a33' }};
            --border: #e2e8f0;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --success: #22c55e;
            --card-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important; letter-spacing: -0.01em; }

        body {
            background-color: var(--bg-color);
            margin: 0;
            font-family: 'Inter', sans-serif;
        }

        .sidebar {
            width: var(--sidebar-width);
            background: white;
            height: 100vh;
            border-right: 1px solid var(--border);
            position: fixed;
            display: flex;
            flex-direction: column;
            box-sizing: border-box;
            z-index: 100;
        }

        .sidebar-header {
            height: 72px;
            padding: 0 1.25rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-sizing: border-box;
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
            display: flex;
            flex-direction: column;
            padding: 1.5rem 1rem;
        }

        .sidebar-footer {
            padding: 1.25rem 1rem;
            border-top: 1px solid var(--border);
            background: #fafafa;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            display: flex;
            flex-direction: column;
            box-sizing: border-box;
            min-width: 0;
        }

        .topbar-nav {
            height: 72px;
            background: white;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 90;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
            box-sizing: border-box;
            width: 100%;
            gap: 1rem;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            min-width: 0;
            flex: 1;
            overflow: hidden;
        }

        .topbar-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e293b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-shrink: 0;
        }

        .topbar-date {
            font-size: 0.8rem;
            color: #64748b;
            font-weight: 500;
            white-space: nowrap;
        }

        @media (max-width: 640px) {
            .topbar-nav { padding: 0 1rem !important; }
            .topbar-date { display: none !important; }
        }

        #sidebarToggle { display: none; }

        .page-body-inner {
            padding: 2rem 2.5rem;
            box-sizing: border-box;
        }

        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s ease; }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0 !important; width: 100% !important; }
            .topbar-nav { padding: 0 1rem !important; }
            #sidebarToggle { display: flex !important; }
            .page-body-inner { padding: 1.25rem !important; }
            .settings-card { max-width: 100% !important; }
            .guide-box { max-width: 100% !important; }
        }

        .settings-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border);
            max-width: 800px;
        }

        .header {
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header h1 {
            font-size: 1.5rem;
            margin: 0;
            color: #1e293b;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.5rem;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 1rem;
            color: #1e293b;
            box-sizing: border-box;
        }

        .btn-save {
            background: var(--primary-color, #850f0f);
            color: #ffffff;
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            font-size: 0.85rem;
            transition: all 0.15s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .btn-save:hover {
            background: #6b0c0c;
            transform: translateY(-1px);
        }

        .guide-box {
            background: #fff7ed;
            border: 1px solid #fed7aa;
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 3rem;
            max-width: 800px;
        }

        .guide-box h2 {
            font-size: 1.125rem;
            color: #9a3412;
            margin-top: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .guide-list {
            padding-left: 1.25rem;
            color: #7c2d12;
            line-height: 1.6;
            font-size: 0.9rem;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: #64748b;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 0.25rem;
        }

        .sidebar-menu a.active {
            background: rgba(133, 15, 15, 0.08);
            color: var(--primary-color);
        }

        /* Refined Admin Profile Dropdown - Text Only Logout */
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

        /* PayU Segment Toggles - Site Theme Matched */
        .payu-card {
            background: #ffffff;
            color: #1e293b;
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--card-shadow, 0 10px 15px -3px rgba(0,0,0,0.05));
            border: 1px solid var(--border, #e2e8f0);
        }
        .payu-card h3 {
            color: #1e293b !important;
            border-bottom: 1px solid #f1f5f9 !important;
        }
        .payu-card label {
            color: #334155 !important;
        }
        .payu-card input[type="text"] {
            background: #ffffff;
            border: 1px solid var(--border, #e2e8f0);
            color: #1e293b;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            width: 100%;
            box-sizing: border-box;
            transition: all 0.2s ease;
        }
        .payu-card input[type="text"]:focus {
            border-color: var(--primary-color, #850f0f);
            outline: none;
            box-shadow: 0 0 0 3px rgba(133, 15, 15, 0.1);
        }
        .seg-group {
            display: flex;
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: 4px;
            gap: 4px;
        }
        .seg-option {
            flex: 1;
            text-align: center;
            margin: 0 !important;
            cursor: pointer;
        }
        .seg-option input[type="radio"] {
            display: none;
        }
        .seg-pill {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 9px 16px;
            border-radius: 7px;
            font-size: 0.875rem;
            font-weight: 600;
            color: #64748b;
            transition: all 0.2s ease;
            user-select: none;
            background: transparent;
        }
        .seg-option:hover .seg-pill {
            color: #0f172a;
        }
        /* Active State Pill (Green) */
        .seg-option input[value="active"]:checked + .seg-pill,
        .seg-option input[value="1"]:checked + .seg-pill {
            background: #16a34a !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            box-shadow: 0 3px 8px rgba(22, 163, 74, 0.35) !important;
        }
        /* Deactive State Pill (Red/Inactive) */
        .seg-option input[value="deactive"]:checked + .seg-pill,
        .seg-option input[value="0"]:checked + .seg-pill {
            background: #dc2626 !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            box-shadow: 0 3px 8px rgba(220, 38, 38, 0.35) !important;
        }
        /* Production Mode Pill (Blue) */
        .seg-option input.mode-prod-radio:checked + .seg-pill {
            background: #2563eb !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            box-shadow: 0 3px 8px rgba(37, 99, 235, 0.35) !important;
        }
        /* Test Sandbox Mode Pill (Amber/Orange) */
        .seg-option input.mode-test-radio:checked + .seg-pill {
            background: #d97706 !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            box-shadow: 0 3px 8px rgba(217, 119, 6, 0.35) !important;
        }
    </style>
    @include('partials.dynamic-styles')
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo"><img src="/assets/logo_transparent.png" alt="MCC-MRF Logo"></div>
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
                <button type="submit" style="width: 100%; display: flex; align-items: center; gap: 0.75rem; background: none; border: none; padding: 0.75rem 1rem; color: #ef4444; cursor: pointer; font-weight: 600; border-radius: 8px; font-size: 0.95rem;">
                    <i class="ph-bold ph-sign-out" style="font-size: 1.2rem;"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <main class="main-content">
        <div class="topbar-nav">
            <div class="topbar-left">
                <button id="sidebarToggle" style="background: #fff; border: 1px solid var(--border); border-radius: 8px; width: 38px; height: 38px; align-items: center; justify-content: center; color: var(--text-main); cursor: pointer; font-size: 1.2rem; flex-shrink: 0;">
                    <i class="ph ph-list"></i>
                </button>
                <i class="ph ph-gear-six" style="font-size: 1.35rem; color: var(--primary-color); flex-shrink: 0;"></i>
                <span class="topbar-title">System Configuration</span>
            </div>
            <div class="topbar-right">
                <div title="Current Theme Color" style="width: 12px; height: 12px; border-radius: 50%; background: var(--primary-color); box-shadow: 0 0 0 2px var(--primary-color); flex-shrink: 0;"></div>
                <span class="topbar-date">{{ now()->format('d M Y') }}</span>
                <div class="admin-profile-wrap">
                    <button class="admin-profile-btn" id="adminProfileBtn" aria-label="Account menu">
                        <i class="ph-fill ph-user"></i>
                    </button>
                    <div class="admin-profile-menu" id="adminProfileMenu">
                        <form class="admin-logout-form" action="{{ route('superadmin.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="admin-logout-btn">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-body-inner">

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="settings-card">
            <form action="{{ route('superadmin.settings.update') }}" method="POST">
                @csrf
                
                <!-- SuperAdmin Master Account & Profile -->
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.25rem; margin-bottom: 2rem;">
                    <h3 style="font-size: 1rem; font-weight: 700; color: #0f172a; margin-bottom: 0.25rem; display: flex; align-items: center; gap: 6px;">
                        <i class="ph-bold ph-shield-check" style="color: var(--primary-color);"></i> SuperAdmin Account Profile & Credentials
                    </h3>
                    <p style="font-size: 0.78rem; color: #64748b; margin-bottom: 1rem;">Manage your master SuperAdmin username, login email address, and account password.</p>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label style="font-size: 0.78rem; font-weight: 600; color: #334155;">SuperAdmin Display Name</label>
                            <input type="text" name="superadmin_name" value="{{ $superAdminUser->name ?? 'Super Admin' }}" required style="height: 38px; font-size: 0.82rem;">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label style="font-size: 0.78rem; font-weight: 600; color: #334155;">SuperAdmin Login Email</label>
                            <input type="email" name="superadmin_email" value="{{ $superAdminUser->email ?? 'apro@mcc.edu.in' }}" required style="height: 38px; font-size: 0.82rem;">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label style="font-size: 0.78rem; font-weight: 600; color: #334155;">New Password (Optional)</label>
                            <input type="password" name="superadmin_password" placeholder="Leave blank to keep current" style="height: 38px; font-size: 0.82rem;">
                        </div>
                    </div>
                </div>

                <h3 style="font-size: 1.1rem; color: #1e293b; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.75rem;">
                    <i class="ph-bold ph-envelope" style="color: var(--primary-color);"></i> Mail Service Configuration
                </h3>

                <div class="form-group">
                    <label>System Email Address (Sender)</label>
                    <input type="email" name="system_email" value="{{ $settings['sender_email'] ?? '' }}" required placeholder="e.g. user@gmail.com">
                </div>

                <div class="form-group">
                    <label>Principal Email Address(es)</label>
                    <input type="text" name="principal_email" value="{{ $settings['principal_email'] ?? '' }}" required placeholder="e.g. principal1@mcc.edu.in, principal2@mcc.edu.in">
                    <div style="font-size: 0.75rem; color: #64748b; margin-top: 5px;">
                        <i class="ph ph-info"></i> Separate multiple emails with a comma (e.g., email1@mcc.edu.in, email2@mcc.edu.in)
                    </div>
                </div>

                <!-- Hall Warden Emails (Per Hall) -->
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.25rem; margin-bottom: 1.5rem;">
                    <h4 style="font-size: 0.95rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 6px;">
                        <i class="ph-bold ph-bank" style="color: var(--primary-color);"></i> Hostel Hall Warden Email Addresses (Per Hall)
                    </h4>
                    <p style="font-size: 0.78rem; color: #64748b; margin-bottom: 1rem;">Configure specific Hall Warden emails for resident student approvals.</p>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem;">
                        @foreach($hallsList as $hallKey => $hallInfo)
                            <div class="form-group" style="margin-bottom: 0;">
                                <label style="font-size: 0.78rem; font-weight: 600; color: #334155;">{{ $hallInfo['name'] }}</label>
                                <input type="text" name="warden_emails[{{ $hallKey }}]" value="{{ $savedWardenMap[$hallKey] ?? $hallInfo['default'] }}" placeholder="{{ $hallInfo['default'] }}" style="height: 38px; font-size: 0.82rem;">
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- HOD Emails (Per Department) -->
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.25rem; margin-bottom: 1.5rem;">
                    <h4 style="font-size: 0.95rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 6px;">
                        <i class="ph-bold ph-buildings" style="color: var(--primary-color);"></i> Department HOD Email Addresses (Per Department)
                    </h4>
                    <p style="font-size: 0.78rem; color: #64748b; margin-bottom: 1rem;">Configure specific HOD emails for non-resident (dayscholar) student approvals.</p>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem; max-height: 480px; overflow-y: auto; padding-right: 6px;">
                        @foreach($deptsList as $deptKey => $deptInfo)
                            <div class="form-group" style="margin-bottom: 0;">
                                <label style="font-size: 0.78rem; font-weight: 600; color: #334155;">{{ $deptInfo['name'] }}</label>
                                <input type="text" name="hod_emails[{{ $deptKey }}]" value="{{ $savedHodMap[$deptKey] ?? $deptInfo['default'] }}" placeholder="{{ $deptInfo['default'] }}" style="height: 38px; font-size: 0.82rem;">
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                    <label>Mail Password / App Password</label>
                    <input type="password" name="mail_password" value="{{ $settings['mail_password'] ?? '' }}" required placeholder="&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;">
                    <div style="font-size: 0.75rem; color: #64748b; margin-top: 5px;">
                        <i class="ph ph-shield-check"></i> This password is encrypted for security.
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Mail Host</label>
                        <input type="text" name="mail_host" value="{{ $settings['mail_host'] ?? 'smtp.gmail.com' }}" required placeholder="e.g. smtp.gmail.com">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Mail Port</label>
                        <input type="number" name="mail_port" value="{{ $settings['mail_port'] ?? '587' }}" required placeholder="e.g. 587">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Mail Encryption</label>
                        <select name="mail_encryption" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border); border-radius: 8px; font-size: 1rem; color: #1e293b;">
                            <option value="tls" {{ ($settings['mail_encryption'] ?? 'tls') == 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ ($settings['mail_encryption'] ?? 'tls') == 'ssl' ? 'selected' : '' }}>SSL</option>
                            <option value="none" {{ ($settings['mail_encryption'] ?? 'tls') == 'none' ? 'selected' : '' }}>None</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Mail Driver / Mailer</label>
                        <select name="mail_mailer" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border); border-radius: 8px; font-size: 1rem; color: #1e293b;">
                            <option value="smtp" {{ ($settings['mail_mailer'] ?? 'smtp') == 'smtp' ? 'selected' : '' }}>SMTP (Default)</option>
                            <option value="sendmail" {{ ($settings['mail_mailer'] ?? 'smtp') == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                            <option value="log" {{ ($settings['mail_mailer'] ?? 'smtp') == 'log' ? 'selected' : '' }}>Log (Testing)</option>
                        </select>
                    </div>
                </div>

                <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 1rem; margin-bottom: 2rem; font-size: 0.85rem; color: #166534;">
                    <i class="ph-fill ph-check-circle"></i> <strong>Configuration Active:</strong> These credentials will be used for <strong>all outgoing system emails</strong> including approvals and notifications.
                </div>

                    <h3 style="font-size: 1.1rem; color: #1e293b; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.75rem;">
                        <i class="ph-bold ph-palette" style="color: var(--primary-color);"></i> Appearance & Branding
                    </h3>

                    <!-- Primary Color (Always On) -->
                    <div class="form-group" style="margin-bottom: 2rem; background: #fff; padding: 1.25rem; border: 1px solid #e2e8f0; border-radius: 12px;">
                        <label style="color: #1e293b; font-weight: 700; margin-bottom: 0.75rem; display: block;">Global Primary Color</label>
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <input type="color" name="primary_color" value="{{ $settings['primary_color'] ?? '#850f0f' }}" style="width: 50px; height: 50px; padding: 2px; cursor: pointer; border: 2px solid #e2e8f0; border-radius: 10px;">
                            <input type="text" id="colorCode" value="{{ $settings['primary_color'] ?? '#850f0f' }}" readonly style="flex: 1; background: #f8fafc; border: 1px solid #e2e8f0; font-family: monospace; color: #64748b; font-size: 0.95rem; font-weight: 600;">
                        </div>
                        <div style="font-size: 0.75rem; color: #64748b; margin-top: 10px;">
                            <i class="ph ph-info"></i> This is the main theme color used for buttons, links, and icons throughout the site.
                        </div>
                    </div>

                    <!-- Secondary Color (Optional Toggle) -->
                    <div style="padding: 1.5rem; background: #f8fafc; border: 2px dashed #e2e8f0; border-radius: 16px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
                            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 0.95rem; color: #1e293b; font-weight: 700;">
                                <input type="checkbox" name="use_secondary_color" id="useSecondaryToggle" {{ ($settings['use_secondary_color'] ?? '0') == '1' ? 'checked' : '' }} style="width: 18px !important; height: 18px !important; accent-color: var(--primary-color);">
                                Enable Secondary Complementary Theme
                            </label>
                            <span class="badge" id="themeStatusBadge" style="{{ ($settings['use_secondary_color'] ?? '0') == '1' ? 'background:#dcfce7;color:#166534;' : 'background:#f1f5f9;color:#64748b;' }}">
                                {{ ($settings['use_secondary_color'] ?? '0') == '1' ? 'Active' : 'Disabled' }}
                            </span>
                        </div>

                        <div id="secondaryColorSection" style="{{ ($settings['use_secondary_color'] ?? '0') == '1' ? '' : 'opacity: 0.5; pointer-events: none;' }}">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label style="font-size: 0.85rem; font-weight: 600; color: #475569;">Accent Secondary Color</label>
                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    <input type="color" name="secondary_color" value="{{ $settings['secondary_color'] ?? '#001a33' }}" style="width: 42px; height: 42px; padding: 2px; cursor: pointer; border: 2px solid #e2e8f0; border-radius: 8px;">
                                    <input type="text" id="secondaryColorCode" value="{{ $settings['secondary_color'] ?? '#001a33' }}" readonly style="flex: 1; background: #fff; border: 1px solid #e2e8f0; font-family: monospace; color: #64748b; font-size: 0.85rem;">
                                </div>
                                <div style="font-size: 0.75rem; color: #64748b; margin-top: 12px; font-style: italic;">
                                    <i class="ph ph-magic-wand"></i> The secondary color creates structural depth by applying it to the main footer and header borders.
                                </div>
                            </div>
                        </div>
                    </div>

                    <h3 style="font-size: 1.1rem; color: #1e293b; margin: 2rem 0 1.5rem 0; display: flex; align-items: center; gap: 0.5rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.75rem;">
                        <i class="ph-bold ph-coins" style="color: var(--primary-color);"></i> Financial & Tax Configuration
                    </h3>

                    <div class="form-group" style="margin-bottom: 2rem; background: #fff; padding: 1.25rem; border: 1px solid #e2e8f0; border-radius: 12px; max-width: 300px;">
                        <label style="color: #1e293b; font-weight: 700; margin-bottom: 0.75rem; display: block;">Default GST Rate (%)</label>
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <input type="number" name="gst_rate" value="{{ $settings['gst_rate'] ?? '5' }}" step="0.1" min="0" max="100" style="flex: 1; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 8px; font-weight: 700; font-size: 1.25rem; text-align: center;">
                            <span style="font-weight: 800; color: #64748b; font-size: 1.25rem;">%</span>
                        </div>
                        <div style="font-size: 0.75rem; color: #64748b; margin-top: 10px;">
                            <i class="ph ph-info"></i> This rate will be used to calculate GST for all room bookings.
                        </div>
                    </div>

                    <!-- PayU Configuration -->
                    <div class="payu-card" style="background: #ffffff; border-radius: 16px; padding: 2rem; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(0,0,0,0.05); margin-bottom: 2rem;">
                        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1rem;">
                            <div>
                                <h3 style="font-size: 1.15rem; font-weight: 700; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                                    <i class="ph-bold ph-credit-card" style="color: var(--primary-color, #850f0f); font-size: 1.3rem;"></i> PayU Payment Gateway Integration
                                </h3>
                                <p style="font-size: 0.8rem; color: #64748b; margin-top: 4px;">Configure online booking payment gateway status, mode, and separate production/test keys.</p>
                            </div>
                            <div id="payuOverallStatusBadge">
                                @if(($settings['payu_status'] ?? 'active') == 'active')
                                    <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 20px; font-size: 0.78rem; font-weight: 700; background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0;">
                                        <i class="ph-bold ph-check-circle"></i> GATEWAY ACTIVE
                                    </span>
                                @else
                                    <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 20px; font-size: 0.78rem; font-weight: 700; background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca;">
                                        <i class="ph-bold ph-x-circle"></i> GATEWAY DEACTIVATED
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Status & Mode Controls -->
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                            <!-- PayU Status Toggle -->
                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.25rem;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                                    <label style="font-size: 0.85rem; font-weight: 700; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 6px;">
                                        <i class="ph-bold ph-power" style="color: #64748b;"></i> PayU Gateway Status
                                    </label>
                                </div>
                                <div class="seg-group">
                                    <label class="seg-option">
                                        <input type="radio" name="payu_status" value="active" {{ ($settings['payu_status'] ?? 'active') == 'active' ? 'checked' : '' }} onchange="updatePayUStatusUI(this.value)">
                                        <span class="seg-pill"><i class="ph-bold ph-check"></i> Active</span>
                                    </label>
                                    <label class="seg-option">
                                        <input type="radio" name="payu_status" value="deactive" {{ ($settings['payu_status'] ?? 'active') == 'deactive' ? 'checked' : '' }} onchange="updatePayUStatusUI(this.value)">
                                        <span class="seg-pill"><i class="ph-bold ph-x"></i> Deactive</span>
                                    </label>
                                </div>
                                <div style="font-size: 0.75rem; color: #64748b; margin-top: 8px;">
                                    When deactivated, online payment checkout is disabled.
                                </div>
                            </div>

                            <!-- Environment Mode Toggle (Production vs Test) -->
                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.25rem;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                                    <label style="font-size: 0.85rem; font-weight: 700; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 6px;">
                                        <i class="ph-bold ph-sliders" style="color: #64748b;"></i> Environment Mode
                                    </label>
                                </div>
                                <div class="seg-group">
                                    <label class="seg-option">
                                        <input type="radio" name="payu_test_mode" value="deactive" class="mode-prod-radio" {{ ($settings['payu_test_mode'] ?? 'deactive') == 'deactive' ? 'checked' : '' }} onchange="togglePayUEnvMode('production')">
                                        <span class="seg-pill"><i class="ph-bold ph-shield-check"></i> Production Mode</span>
                                    </label>
                                    <label class="seg-option">
                                        <input type="radio" name="payu_test_mode" value="active" class="mode-test-radio" {{ ($settings['payu_test_mode'] ?? 'deactive') == 'active' ? 'checked' : '' }} onchange="togglePayUEnvMode('test')">
                                        <span class="seg-pill"><i class="ph-bold ph-flask"></i> Test Sandbox</span>
                                    </label>
                                </div>
                                <div style="font-size: 0.75rem; color: #64748b; margin-top: 8px;">
                                    Select Production Mode for real payments or Test Sandbox for testing.
                                </div>
                            </div>
                        </div>

                        <!-- Separate Credentials Sections -->
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                            <!-- Production Credentials Section -->
                            <div id="prodCredentialsCard" style="background: #f0f7ff; border: 2px solid #bfdbfe; border-radius: 14px; padding: 1.5rem; transition: all 0.3s ease;">
                                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                                    <h4 style="font-size: 0.92rem; font-weight: 800; color: #1e40af; margin: 0; display: flex; align-items: center; gap: 6px;">
                                        <i class="ph-bold ph-shield-check" style="font-size: 1.1rem;"></i> Production (Live) Credentials
                                    </h4>
                                    <span style="font-size: 0.7rem; font-weight: 700; background: #2563eb; color: #ffffff; padding: 2px 8px; border-radius: 4px; text-transform: uppercase;">
                                        LIVE GATEWAY
                                    </span>
                                </div>

                                <div class="form-group" style="margin-bottom: 1rem;">
                                    <label style="font-size: 0.8rem; font-weight: 700; color: #1e3a8a; margin-bottom: 0.4rem; display: block;">Production Merchant Key</label>
                                    <input type="text" name="payu_prod_merchant_key" id="payu_prod_key" value="{{ $settings['payu_prod_merchant_key'] ?? ($settings['payu_merchant_key'] ?? env('PAYU_MERCHANT_KEY', '')) }}" placeholder="e.g. uAV4rQ" style="background: #ffffff; border-color: #93c5fd;">
                                </div>

                                <div class="form-group" style="margin-bottom: 0;">
                                    <label style="font-size: 0.8rem; font-weight: 700; color: #1e3a8a; margin-bottom: 0.4rem; display: block;">Production Merchant Salt</label>
                                    <input type="text" name="payu_prod_merchant_salt" id="payu_prod_salt" value="{{ $settings['payu_prod_merchant_salt'] ?? ($settings['payu_merchant_salt'] ?? env('PAYU_MERCHANT_SALT', '')) }}" placeholder="Enter PayU Live Salt Key" style="background: #ffffff; border-color: #93c5fd;">
                                </div>
                            </div>

                            <!-- Test / Sandbox Credentials Section -->
                            <div id="testCredentialsCard" style="background: #fffbeb; border: 2px solid #fde68a; border-radius: 14px; padding: 1.5rem; transition: all 0.3s ease;">
                                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                                    <h4 style="font-size: 0.92rem; font-weight: 800; color: #92400e; margin: 0; display: flex; align-items: center; gap: 6px;">
                                        <i class="ph-bold ph-flask" style="font-size: 1.1rem;"></i> Test (Sandbox) Credentials
                                    </h4>
                                    <span style="font-size: 0.7rem; font-weight: 700; background: #d97706; color: #ffffff; padding: 2px 8px; border-radius: 4px; text-transform: uppercase;">
                                        SANDBOX MODE
                                    </span>
                                </div>

                                <div class="form-group" style="margin-bottom: 1rem;">
                                    <label style="font-size: 0.8rem; font-weight: 700; color: #78350f; margin-bottom: 0.4rem; display: block;">Test Merchant Key</label>
                                    <input type="text" name="payu_test_merchant_key" id="payu_test_key" value="{{ $settings['payu_test_merchant_key'] ?? ($settings['payu_merchant_key'] ?? env('PAYU_MERCHANT_KEY', '')) }}" placeholder="Enter Test PayU Merchant Key" style="background: #ffffff; border-color: #fcd34d;">
                                </div>

                                <div class="form-group" style="margin-bottom: 0;">
                                    <label style="font-size: 0.8rem; font-weight: 700; color: #78350f; margin-bottom: 0.4rem; display: block;">Test Merchant Salt</label>
                                    <input type="text" name="payu_test_merchant_salt" id="payu_test_salt" value="{{ $settings['payu_test_merchant_salt'] ?? ($settings['payu_merchant_salt'] ?? env('PAYU_MERCHANT_SALT', '')) }}" placeholder="Enter Test PayU Merchant Salt" style="background: #ffffff; border-color: #fcd34d;">
                                </div>
                            </div>
                        </div>

                        <!-- Fallback / Backward Compatible Hidden/Synced Inputs -->
                        <input type="hidden" name="payu_merchant_key" id="payu_main_key" value="{{ $settings['payu_merchant_key'] ?? env('PAYU_MERCHANT_KEY', '') }}">
                        <input type="hidden" name="payu_merchant_salt" id="payu_main_salt" value="{{ $settings['payu_merchant_salt'] ?? env('PAYU_MERCHANT_SALT', '') }}">

                        <div style="font-size: 0.82rem; color: #d97706; margin-top: 1rem; font-weight: 500; display: flex; align-items: center; gap: 6px; background: #fff7ed; padding: 0.75rem 1rem; border-radius: 8px; border: 1px solid #ffedd5;">
                            <i class="ph-bold ph-info" style="font-size: 1.1rem; flex-shrink: 0;"></i> 
                            <span>PayU success and failure callback URLs are handled automatically by the system.</span>
                        </div>
                    </div>

                    <h3 style="font-size: 1.1rem; color: #1e293b; margin: 2rem 0 1.5rem 0; display: flex; align-items: center; gap: 0.5rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.75rem;">
                        <i class="ph-bold ph-whatsapp-logo" style="color: #25D366;"></i> WhatsApp Notification Integration
                    </h3>

                    <div style="padding: 1.5rem; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 16px; margin-bottom: 2rem;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
                            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 0.95rem; color: #166534; font-weight: 700;">
                                <input type="checkbox" name="whatsapp_enabled" id="whatsappToggle" value="1" {{ ($settings['whatsapp_enabled'] ?? '0') == '1' ? 'checked' : '' }} style="width: 18px !important; height: 18px !important; accent-color: #25D366;">
                                Enable WhatsApp Notifications for Principal
                            </label>
                            <span class="badge" id="whatsappStatusBadge" style="{{ ($settings['whatsapp_enabled'] ?? '0') == '1' ? 'background:#25D366;color:white;' : 'background:#f1f5f9;color:#64748b;' }}">
                                {{ ($settings['whatsapp_enabled'] ?? '0') == '1' ? 'Active' : 'Disabled' }}
                            </span>
                        </div>

                        <div id="whatsappSettingsSection" style="{{ ($settings['whatsapp_enabled'] ?? '0') == '1' ? '' : 'opacity: 0.5; pointer-events: none;' }}">
                            <div class="form-group">
                                <label>Principal's WhatsApp Phone Number (with Country Code prefix)</label>
                                <input type="text" name="principal_phone" value="{{ $settings['principal_phone'] ?? '' }}" placeholder="e.g. +919876543210">
                                <div style="font-size: 0.75rem; color: #166534; margin-top: 5px;">
                                    Include the country code prefix (e.g., +91 for India).
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label>WhatsApp Service Provider</label>
                                    <select name="whatsapp_provider" id="whatsappProviderSelect" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border); border-radius: 8px; font-size: 1rem; color: #1e293b;">
                                        <option value="ultramsg" {{ ($settings['whatsapp_provider'] ?? 'ultramsg') == 'ultramsg' ? 'selected' : '' }}>Ultramsg (Recommended)</option>
                                        <option value="meta" {{ ($settings['whatsapp_provider'] ?? 'ultramsg') == 'meta' ? 'selected' : '' }}>Meta WhatsApp Cloud API (100% Free - Official)</option>
                                        <option value="twilio" {{ ($settings['whatsapp_provider'] ?? 'ultramsg') == 'twilio' ? 'selected' : '' }}>Twilio WhatsApp API</option>
                                        <option value="callmebot" {{ ($settings['whatsapp_provider'] ?? 'ultramsg') == 'callmebot' ? 'selected' : '' }}>CallMeBot (100% Free - Single Recipient)</option>
                                        <option value="log" {{ ($settings['whatsapp_provider'] ?? 'ultramsg') == 'log' ? 'selected' : '' }}>Log / Simulated Mode (Testing)</option>
                                    </select>
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label id="whatsappSenderLabel">Sender/From Phone Number (or Twilio Sandbox No.)</label>
                                    <input type="text" name="whatsapp_sender" id="whatsappSenderInput" value="{{ $settings['whatsapp_sender'] ?? '' }}" placeholder="e.g. +14155238886">
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label id="whatsappCredentialLabel1">Instance ID / Account SID</label>
                                    <input type="text" name="whatsapp_sid" id="whatsappSidInput" value="{{ $settings['whatsapp_sid'] ?? '' }}" placeholder="e.g. instance12345 or ACxxxxxxxx">
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label id="whatsappCredentialLabel2">API / Auth Token</label>
                                    <input type="password" name="whatsapp_token" value="{{ $settings['whatsapp_token'] ?? '' }}" placeholder="&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;">
                                </div>
                            </div>
                        </div>
                    </div>

                <button type="submit" class="btn-save">Save Configuration</button>
            </form>
        </div>

        <div class="guide-box">
            <h2><i class="ph-bold ph-lightbulb"></i> How to generate a Gmail App Password?</h2>
            <ol class="guide-list">
                <li>Go to your <a href="https://myaccount.google.com/" target="_blank">Google Account</a>.</li>
                <li>Ensure <strong>2-Step Verification</strong> is ON in the Security tab.</li>
                <li>Go directly to <a href="https://myaccount.google.com/apppasswords" target="_blank" style="font-weight: 700;">App Passwords</a>.</li>
                <li>Select "Mail" and "Other (Custom name: MCC IGH)".</li>
                <li>Copy the <strong>16-character code</strong> and paste it above.</li>
                <li><em>Note: Do not include spaces when pasting; the system will handle it.</em></li>
            </ol>
        </div>
        </div><!-- /.page-body-inner -->
    </main>
    <script>
        // Sidebar Toggle
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
                const isClickInsideSidebar = sidebar.contains(event.target);
                const isClickOnToggle = sidebarToggle && sidebarToggle.contains(event.target);
                if (!isClickInsideSidebar && !isClickOnToggle) {
                    sidebar.classList.remove('open');
                }
            }
        });

        // Color Sync
        const colorInput = document.querySelector('input[name="primary_color"]');
        const colorCode = document.getElementById('colorCode');
        const secondaryInput = document.querySelector('input[name="secondary_color"]');
        const secondaryCode = document.getElementById('secondaryColorCode');
        
        if (colorInput && colorCode) {
            colorInput.addEventListener('input', (e) => {
                colorCode.value = e.target.value.toUpperCase();
            });
        }
        if (secondaryInput && secondaryCode) {
            secondaryInput.addEventListener('input', (e) => {
                secondaryCode.value = e.target.value.toUpperCase();
            });
        }

        const useSecondaryToggle = document.getElementById('useSecondaryToggle');
        const secondarySection = document.getElementById('secondaryColorSection');
        const statusBadge = document.getElementById('themeStatusBadge');
        
        if (useSecondaryToggle && secondarySection && statusBadge) {
            useSecondaryToggle.addEventListener('change', (e) => {
                if (e.target.checked) {
                    secondarySection.style.opacity = '1';
                    secondarySection.style.pointerEvents = 'auto';
                    statusBadge.innerText = 'Active';
                    statusBadge.style.background = '#dcfce7';
                    statusBadge.style.color = '#166534';
                } else {
                    secondarySection.style.opacity = '0.5';
                    secondarySection.style.pointerEvents = 'none';
                    statusBadge.innerText = 'Disabled';
                    statusBadge.style.background = '#f1f5f9';
                    statusBadge.style.color = '#64748b';
                }
            });
        }

        // WhatsApp Toggle
        const whatsappToggle = document.getElementById('whatsappToggle');
        const whatsappSettingsSection = document.getElementById('whatsappSettingsSection');
        const whatsappStatusBadge = document.getElementById('whatsappStatusBadge');
        
        if (whatsappToggle && whatsappSettingsSection && whatsappStatusBadge) {
            whatsappToggle.addEventListener('change', (e) => {
                if (e.target.checked) {
                    whatsappSettingsSection.style.opacity = '1';
                    whatsappSettingsSection.style.pointerEvents = 'auto';
                    whatsappStatusBadge.innerText = 'Active';
                    whatsappStatusBadge.style.background = '#25D366';
                    whatsappStatusBadge.style.color = 'white';
                } else {
                    whatsappSettingsSection.style.opacity = '0.5';
                    whatsappSettingsSection.style.pointerEvents = 'none';
                    whatsappStatusBadge.innerText = 'Disabled';
                    whatsappStatusBadge.style.background = '#f1f5f9';
                    whatsappStatusBadge.style.color = '#64748b';
                }
            });
        }

        const providerSelect = document.getElementById('whatsappProviderSelect');
        const label1 = document.getElementById('whatsappCredentialLabel1');
        const label2 = document.getElementById('whatsappCredentialLabel2');
        const senderLabel = document.getElementById('whatsappSenderLabel');
        if (providerSelect && label1 && label2) {
            const updateLabels = () => {
                const senderInput = document.getElementById('whatsappSenderInput');
                const sidInput = document.getElementById('whatsappSidInput');

                if (providerSelect.value === 'twilio') {
                    label1.innerText = 'Account SID (Twilio)';
                    label2.innerText = 'Auth Token (Twilio)';
                    if (senderLabel) senderLabel.innerText = 'Sender/From Phone Number (or Twilio Sandbox No.)';
                    if (senderInput) {
                        senderInput.placeholder = 'e.g. +14155238886';
                        senderInput.disabled = false;
                    }
                    if (sidInput) sidInput.disabled = false;
                } else if (providerSelect.value === 'ultramsg') {
                    label1.innerText = 'Instance ID (Ultramsg)';
                    label2.innerText = 'API Token (Ultramsg)';
                    if (senderLabel) senderLabel.innerText = 'Sender/From Phone Number (Optional)';
                    if (senderInput) {
                        senderInput.placeholder = 'e.g. +14155238886';
                        senderInput.disabled = false;
                    }
                    if (sidInput) sidInput.disabled = false;
                } else if (providerSelect.value === 'meta') {
                    label1.innerText = 'Phone Number ID (Meta)';
                    label2.innerText = 'Permanent Access Token (Meta)';
                    if (senderLabel) senderLabel.innerText = 'Template Name (default: booking_notification)';
                    if (senderInput) {
                        senderInput.placeholder = 'e.g. booking_notification or text';
                        senderInput.disabled = false;
                    }
                    if (sidInput) sidInput.disabled = false;
                } else if (providerSelect.value === 'callmebot') {
                    label1.innerText = 'Not Required (CallMeBot)';
                    label2.innerText = 'API Key (CallMeBot)';
                    if (senderLabel) senderLabel.innerText = 'Not Required (CallMeBot)';
                    if (sidInput) {
                        sidInput.disabled = true;
                        sidInput.value = '';
                    }
                    if (senderInput) {
                        senderInput.disabled = true;
                        senderInput.value = '';
                    }
                } else {
                    label1.innerText = 'Instance ID / Account SID';
                    label2.innerText = 'API / Auth Token';
                    if (senderLabel) senderLabel.innerText = 'Sender/From Phone Number';
                    if (sidInput) sidInput.disabled = false;
                    if (senderInput) senderInput.disabled = false;
                }
            };
            providerSelect.addEventListener('change', updateLabels);
            updateLabels();
        }

        // PayU Environment Mode & Status UI Sync
        function updatePayUStatusUI(status) {
            const badge = document.getElementById('payuOverallStatusBadge');
            if (!badge) return;
            if (status === 'active') {
                badge.innerHTML = `<span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 20px; font-size: 0.78rem; font-weight: 700; background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0;"><i class="ph-bold ph-check-circle"></i> GATEWAY ACTIVE</span>`;
            } else {
                badge.innerHTML = `<span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 20px; font-size: 0.78rem; font-weight: 700; background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca;"><i class="ph-bold ph-x-circle"></i> GATEWAY DEACTIVATED</span>`;
            }
        }

        function togglePayUEnvMode(mode) {
            const prodCard = document.getElementById('prodCredentialsCard');
            const testCard = document.getElementById('testCredentialsCard');
            const mainKey = document.getElementById('payu_main_key');
            const mainSalt = document.getElementById('payu_main_salt');

            const prodKeyInput = document.getElementById('payu_prod_key');
            const prodSaltInput = document.getElementById('payu_prod_salt');
            const testKeyInput = document.getElementById('payu_test_key');
            const testSaltInput = document.getElementById('payu_test_salt');

            if (mode === 'test') {
                if (testCard) {
                    testCard.style.opacity = '1';
                    testCard.style.transform = 'scale(1.02)';
                    testCard.style.boxShadow = '0 6px 20px rgba(217, 119, 6, 0.15)';
                }
                if (prodCard) {
                    prodCard.style.opacity = '0.7';
                    prodCard.style.transform = 'scale(1)';
                    prodCard.style.boxShadow = 'none';
                }
                if (mainKey && testKeyInput) mainKey.value = testKeyInput.value;
                if (mainSalt && testSaltInput) mainSalt.value = testSaltInput.value;
            } else {
                if (prodCard) {
                    prodCard.style.opacity = '1';
                    prodCard.style.transform = 'scale(1.02)';
                    prodCard.style.boxShadow = '0 6px 20px rgba(37, 99, 235, 0.15)';
                }
                if (testCard) {
                    testCard.style.opacity = '0.7';
                    testCard.style.transform = 'scale(1)';
                    testCard.style.boxShadow = 'none';
                }
                if (mainKey && prodKeyInput) mainKey.value = prodKeyInput.value;
                if (mainSalt && prodSaltInput) mainSalt.value = prodSaltInput.value;
            }
        }

        // Initialize PayU UI state on load
        document.addEventListener('DOMContentLoaded', () => {
            const isTestChecked = document.querySelector('input[name="payu_test_mode"][value="active"]')?.checked;
            togglePayUEnvMode(isTestChecked ? 'test' : 'production');
        });
    </script>
</body>
</html>
