<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Settings - Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <style>
        :root {
            --sidebar-width: 240px;
            --bg-color: #f8fafc;
            --primary-color: {{ $settings['primary_color'] ?? '#850f0f' }};
            --secondary-color: {{ $settings['secondary_color'] ?? '#001a33' }};
            --border: #e2e8f0;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --card-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { background-color: var(--bg-color); margin: 0; }

        .sidebar {
            width: var(--sidebar-width);
            background: #ffffff;
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 100;
        }

        .sidebar-header { padding: 1.5rem; border-bottom: 1px solid var(--border); }
        .sidebar-logo img { height: 80px; width: auto; object-fit: contain; }

        .sidebar-menu { padding: 1rem 0.75rem; flex: 1; display: flex; flex-direction: column; }
        .menu-item {
            display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem;
            color: #64748b; text-decoration: none; border-radius: 8px; font-weight: 500;
            transition: all 0.2s ease; margin-bottom: 0.25rem;
        }
        .menu-item:hover { background: rgba(133, 15, 15, 0.08); color: var(--primary-color); }
        .menu-item.active {
            background: rgba(133, 15, 15, 0.1);
            color: var(--primary-color);
            font-weight: 600;
            border-left: 3px solid var(--primary-color);
            padding-left: calc(1rem - 3px);
        }

        .admin-main {
            margin-left: 240px;
            padding: 2rem 2.5rem;
            min-height: 100vh;
        }

        .top-navbar {
            display: flex; align-items: center; justify-content: space-between;
            background: white; border: 1px solid var(--border); border-radius: 12px;
            padding: 0.75rem 1.5rem; margin-bottom: 2rem; box-shadow: var(--card-shadow);
        }

        .payu-setting-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border);
            max-width: 800px;
        }

        .form-group { margin-bottom: 1.5rem; }
        .form-group label {
            display: block; font-size: 0.875rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem;
        }
        .form-group input[type="text"] {
            width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border);
            border-radius: 8px; font-size: 0.95rem; color: #1e293b; transition: all 0.2s ease;
        }
        .form-group input[type="text"]:focus {
            border-color: var(--primary-color); outline: none; box-shadow: 0 0 0 3px rgba(133, 15, 15, 0.1);
        }

        .seg-group {
            display: flex; background: #f8fafc; border: 1px solid var(--border);
            border-radius: 10px; padding: 4px; gap: 4px;
        }
        .seg-option { flex: 1; text-align: center; margin: 0; cursor: pointer; }
        .seg-option input[type="radio"] { display: none; }
        .seg-pill {
            display: block; padding: 10px 16px; border-radius: 7px;
            font-size: 0.875rem; font-weight: 600; color: #64748b;
            transition: all 0.2s ease; user-select: none;
        }
        .seg-option input[type="radio"]:checked + .seg-pill {
            background: var(--primary-color, #850f0f);
            color: #ffffff;
            font-weight: 700;
            box-shadow: 0 2px 6px rgba(133, 15, 15, 0.25);
        }

        .btn-save {
            background: var(--primary-color, #850f0f);
            color: #ffffff; border: none; padding: 0.75rem 2rem;
            border-radius: 8px; font-weight: 700; cursor: pointer;
            font-size: 0.9rem; transition: all 0.15s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: inline-flex; align-items: center; gap: 0.5rem;
        }
        .btn-save:hover { background: #6b0c0c; transform: translateY(-1px); }

        .alert-success {
            background: #dcfce7; color: #166534; padding: 1rem;
            border-radius: 10px; margin-bottom: 1.5rem; font-weight: 600;
            display: flex; align-items: center; gap: 0.5rem; border: 1px solid #bbf7d0;
        }
    </style>
    @include('partials.dynamic-styles')
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo"><img src="/assets/logo_transparent.png" alt="MCC-MRF Logo"></div>
        </div>
        <div class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}" class="menu-item {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                <i class="ph ph-squares-four"></i> Dashboard
            </a>
            <a href="{{ route('admin.bookings') }}" class="menu-item {{ Route::is('admin.bookings*') ? 'active' : '' }}">
                <i class="ph ph-calendar-check"></i> Bookings
            </a>
            <a href="{{ route('admin.college-guest') }}" class="menu-item {{ Route::is('admin.college-guest') ? 'active' : '' }}">
                <i class="ph ph-user-gear"></i> College Guests
            </a>
            <a href="{{ route('admin.reports') }}" class="menu-item {{ Route::is('admin.reports') ? 'active' : '' }}">
                <i class="ph ph-file-text"></i> Reports
            </a>
            <a href="{{ route('admin.settings') }}" class="menu-item {{ Route::is('admin.settings') ? 'active' : '' }}">
                <i class="ph ph-credit-card"></i> Payment Settings
            </a>
            <a href="{{ route('home') }}" class="menu-item" target="_blank" rel="noopener noreferrer">
                <i class="ph ph-globe"></i> Visit Website
            </a>
            <div style="margin-top: auto; padding: 1.5rem; border-top: 1px solid rgba(0,0,0,0.05);">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" style="width: 100%; display: flex; align-items: center; gap: 0.75rem; background: none; border: none; padding: 0.75rem 1rem; color: #ef4444; cursor: pointer; font-weight: 600; border-radius: 8px;">
                        <i class="ph-bold ph-sign-out" style="font-size: 1.25rem;"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <main class="admin-main">
        <div class="top-navbar">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <i class="ph-bold ph-credit-card" style="font-size: 1.35rem; color: var(--primary-color);"></i>
                <span style="font-weight: 700; font-size: 1.15rem; color: var(--text-main);">Payment Gateway Settings</span>
            </div>
            <div style="font-size: 0.85rem; color: var(--text-muted); font-weight: 500;">
                {{ now()->format('d M Y') }}
            </div>
        </div>

        @if(session('success'))
            <div class="alert-success">
                <i class="ph-bold ph-check-circle" style="font-size: 1.2rem;"></i> {{ session('success') }}
            </div>
        @endif

        <div class="payu-setting-card">
            <h2 style="font-size: 1.3rem; font-weight: 800; color: #1e293b; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="ph-bold ph-wallet" style="color: var(--primary-color);"></i> PayU Integration
            </h2>
            <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 2rem;">Configure online booking payment gateway status, mode, and credentials.</p>

            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                    <!-- PayU Status -->
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>PayU Status</label>
                        <div class="seg-group">
                            <label class="seg-option">
                                <input type="radio" name="payu_status" value="active" {{ ($settings['payu_status'] ?? 'active') == 'active' ? 'checked' : '' }}>
                                <span class="seg-pill">Active</span>
                            </label>
                            <label class="seg-option">
                                <input type="radio" name="payu_status" value="deactive" {{ ($settings['payu_status'] ?? 'active') == 'deactive' ? 'checked' : '' }}>
                                <span class="seg-pill">Deactive</span>
                            </label>
                        </div>
                    </div>

                    <!-- Test Mode -->
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Test Mode</label>
                        <div class="seg-group">
                            <label class="seg-option">
                                <input type="radio" name="payu_test_mode" value="active" {{ ($settings['payu_test_mode'] ?? 'deactive') == 'active' ? 'checked' : '' }}>
                                <span class="seg-pill">Active</span>
                            </label>
                            <label class="seg-option">
                                <input type="radio" name="payu_test_mode" value="deactive" {{ ($settings['payu_test_mode'] ?? 'deactive') == 'deactive' ? 'checked' : '' }}>
                                <span class="seg-pill">Deactive</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Merchant Key</label>
                        <input type="text" name="payu_merchant_key" value="{{ $settings['payu_merchant_key'] ?? env('PAYU_MERCHANT_KEY', '') }}" placeholder="Enter PayU Merchant Key">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Merchant Salt</label>
                        <input type="text" name="payu_merchant_salt" value="{{ $settings['payu_merchant_salt'] ?? env('PAYU_MERCHANT_SALT', '') }}" placeholder="Enter PayU Merchant Salt">
                    </div>
                </div>

                <div style="font-size: 0.85rem; color: #d97706; margin-bottom: 2rem; font-weight: 500; display: flex; align-items: center; gap: 6px;">
                    <i class="ph-bold ph-info" style="font-size: 1rem;"></i> PayU success and failure URLs are sent automatically during checkout.
                </div>

                <button type="submit" class="btn-save">
                    <i class="ph-bold ph-floppy-disk"></i> Update Configuration
                </button>
            </form>
        </div>
    </main>
</body>
</html>
