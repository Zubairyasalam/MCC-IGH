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

        <div class="payu-setting-card" style="background: #ffffff; border-radius: 20px; padding: 2.5rem; border: 1px solid var(--border); box-shadow: var(--card-shadow); max-width: 900px;">
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1rem;">
                <div>
                    <h2 style="font-size: 1.3rem; font-weight: 800; color: #1e293b; margin-bottom: 0.25rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="ph-bold ph-wallet" style="color: var(--primary-color);"></i> PayU Integration
                    </h2>
                    <p style="font-size: 0.85rem; color: #64748b; margin: 0;">Configure online booking payment gateway status, environment mode, and credentials.</p>
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

            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf

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

                <!-- Fallback / Backward Compatible Hidden Inputs -->
                <input type="hidden" name="payu_merchant_key" id="payu_main_key" value="{{ $settings['payu_merchant_key'] ?? env('PAYU_MERCHANT_KEY', '') }}">
                <input type="hidden" name="payu_merchant_salt" id="payu_main_salt" value="{{ $settings['payu_merchant_salt'] ?? env('PAYU_MERCHANT_SALT', '') }}">

                <div style="font-size: 0.85rem; color: #d97706; margin-bottom: 2rem; font-weight: 500; display: flex; align-items: center; gap: 6px; background: #fff7ed; padding: 0.75rem 1rem; border-radius: 8px; border: 1px solid #ffedd5;">
                    <i class="ph-bold ph-info" style="font-size: 1rem; flex-shrink: 0;"></i> PayU success and failure URLs are sent automatically during checkout.
                </div>

                <button type="submit" class="btn-save">
                    <i class="ph-bold ph-floppy-disk"></i> Update Configuration
                </button>
            </form>
        </div>
    </main>

    <script>
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

        document.addEventListener('DOMContentLoaded', () => {
            const isTestChecked = document.querySelector('input[name="payu_test_mode"][value="active"]')?.checked;
            togglePayUEnvMode(isTestChecked ? 'test' : 'production');
        });
    </script>
</body>
</html>
