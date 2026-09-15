<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin & Payment Settings - Admin Panel</title>
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
            display: flex; background: #f1f5f9; border: 1px solid #cbd5e1;
            border-radius: 10px; padding: 4px; gap: 4px;
        }
        .seg-option { flex: 1; text-align: center; margin: 0; cursor: pointer; }
        .seg-option input[type="radio"] { display: none; }
        .seg-pill {
            display: flex; align-items: center; justify-content: center; gap: 6px;
            padding: 9px 16px; border-radius: 7px;
            font-size: 0.875rem; font-weight: 600; color: #64748b;
            transition: all 0.2s ease; user-select: none; background: transparent;
        }
        .seg-option:hover .seg-pill { color: #0f172a; }
        /* Active State Pill (Green) */
        .seg-option input[value="active"]:checked + .seg-pill,
        .seg-option input[value="1"]:checked + .seg-pill {
            background: #16a34a !important; color: #ffffff !important;
            font-weight: 700 !important; box-shadow: 0 3px 8px rgba(22, 163, 74, 0.35) !important;
        }
        /* Deactive State Pill (Red/Inactive) */
        .seg-option input[value="deactive"]:checked + .seg-pill,
        .seg-option input[value="0"]:checked + .seg-pill {
            background: #dc2626 !important; color: #ffffff !important;
            font-weight: 700 !important; box-shadow: 0 3px 8px rgba(220, 38, 38, 0.35) !important;
        }
        /* Production Mode Pill (Blue) */
        .seg-option input.mode-prod-radio:checked + .seg-pill {
            background: #2563eb !important; color: #ffffff !important;
            font-weight: 700 !important; box-shadow: 0 3px 8px rgba(37, 99, 235, 0.35) !important;
        }
        /* Test Sandbox Mode Pill (Amber/Orange) */
        .seg-option input.mode-test-radio:checked + .seg-pill {
            background: #d97706 !important; color: #ffffff !important;
            font-weight: 700 !important; box-shadow: 0 3px 8px rgba(217, 119, 6, 0.35) !important;
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
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo"><img src="/assets/logo_transparent.png" alt="MCC-MRF Logo" style="height:80px; width:auto; object-fit:contain;"></div>
        </div>

        <div class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}" class="menu-item {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                <i class="ph ph-squares-four"></i> Dashboard
            </a>
            <a href="{{ route('admin.bookings') }}" class="menu-item {{ Route::is('admin.bookings*') ? 'active' : '' }}">
                <i class="ph ph-calendar-check"></i> Bookings
            </a>
            <a href="{{ route('admin.room-block') }}" class="menu-item {{ Route::is('admin.room-block*') ? 'active' : '' }}">
                <i class="ph ph-prohibit"></i> Room Block
            </a>
            <a href="{{ route('admin.college-guest') }}" class="menu-item {{ Route::is('admin.college-guest') ? 'active' : '' }}">
                <i class="ph ph-user-gear"></i> College Guests
            </a>
            <a href="{{ route('admin.reports') }}" class="menu-item {{ Route::is('admin.reports') ? 'active' : '' }}">
                <i class="ph ph-file-text"></i> Reports
            </a>
            <a href="{{ route('admin.settings') }}" class="menu-item {{ Route::is('admin.settings') ? 'active' : '' }}">
                <i class="ph ph-gear"></i> Admin Settings
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
                <button id="sidebarToggle" style="display: none; background: #fff; border: 1px solid var(--border); border-radius: 8px; width: 40px; height: 40px; align-items: center; justify-content: center; color: var(--text-main); cursor: pointer; font-size: 1.25rem;">
                    <i class="ph ph-list"></i>
                </button>
                <i class="ph-bold ph-gear" style="font-size: 1.35rem; color: var(--primary-color);"></i>
                <span style="font-weight: 700; font-size: 1.15rem; color: var(--text-main);">Admin Settings</span>
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

        <!-- Admin Reset Password Card -->
        <div style="background: #ffffff; border-radius: 20px; padding: 2.5rem; border: 1px solid var(--border); box-shadow: var(--card-shadow); max-width: 900px; margin-bottom: 2rem;">
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1rem;">
                <div>
                    <h2 style="font-size: 1.3rem; font-weight: 800; color: #1e293b; margin-bottom: 0.25rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="ph-bold ph-lock-key" style="color: var(--primary-color);"></i> Reset Admin Password
                    </h2>
                    <p style="font-size: 0.85rem; color: #64748b; margin: 0;">Change and update your admin account login password securely.</p>
                </div>
                <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 20px; font-size: 0.78rem; font-weight: 700; background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe;">
                    <i class="ph-bold ph-user-circle"></i> Logged in as: {{ Auth::user()->name ?? 'Admin' }} ({{ Auth::user()->email ?? '' }})
                </span>
            </div>

            @if(session('password_success'))
                <div class="alert-success" style="margin-bottom: 1.5rem;">
                    <i class="ph-bold ph-check-circle" style="font-size: 1.2rem;"></i> {{ session('password_success') }}
                </div>
            @endif

            @if(session('password_error'))
                <div style="background: #fef2f2; color: #991b1b; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; border: 1px solid #fecaca;">
                    <i class="ph-bold ph-warning-circle" style="font-size: 1.2rem;"></i> {{ session('password_error') }}
                </div>
            @endif

            @if($errors->has('current_password') || $errors->has('new_password'))
                <div style="background: #fef2f2; color: #991b1b; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; font-size: 0.88rem; border: 1px solid #fecaca;">
                    @foreach($errors->all() as $err)
                        <div style="display: flex; align-items: center; gap: 6px;"><i class="ph-bold ph-warning"></i> {{ $err }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('admin.settings.password') }}" method="POST">
                @csrf
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                    <!-- Current Password -->
                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-size: 0.85rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem; display: block;">Current Password</label>
                        <div style="position: relative;">
                            <input type="password" name="current_password" id="current_password" required placeholder="Enter current password" style="width: 100%; padding: 0.75rem 2.5rem 0.75rem 1rem; border: 1px solid var(--border); border-radius: 8px; font-size: 0.95rem;">
                            <i class="ph-bold ph-eye" onclick="togglePassVisibility('current_password', this)" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #64748b; font-size: 1.2rem;"></i>
                        </div>
                    </div>

                    <!-- New Password -->
                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-size: 0.85rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem; display: block;">New Password</label>
                        <div style="position: relative;">
                            <input type="password" name="new_password" id="new_password" required placeholder="Min. 6 characters" style="width: 100%; padding: 0.75rem 2.5rem 0.75rem 1rem; border: 1px solid var(--border); border-radius: 8px; font-size: 0.95rem;">
                            <i class="ph-bold ph-eye" onclick="togglePassVisibility('new_password', this)" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #64748b; font-size: 1.2rem;"></i>
                        </div>
                    </div>

                    <!-- Confirm New Password -->
                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-size: 0.85rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem; display: block;">Confirm New Password</label>
                        <div style="position: relative;">
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation" required placeholder="Re-enter new password" style="width: 100%; padding: 0.75rem 2.5rem 0.75rem 1rem; border: 1px solid var(--border); border-radius: 8px; font-size: 0.95rem;">
                            <i class="ph-bold ph-eye" onclick="togglePassVisibility('new_password_confirmation', this)" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #64748b; font-size: 1.2rem;"></i>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-save" style="background: #1e293b;">
                    <i class="ph-bold ph-shield-check"></i> Reset Password
                </button>
            </form>
        </div>
    </main>

    <script>
        function togglePassVisibility(inputId, icon) {
            const input = document.getElementById(inputId);
            if (!input) return;
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('ph-eye');
                icon.classList.add('ph-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('ph-eye-slash');
                icon.classList.add('ph-eye');
            }
        }

        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('open');
            });
        }
    </script>
</body>
</html>
