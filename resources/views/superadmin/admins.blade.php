<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Admins - SuperAdmin</title>
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
            height: 72px;
            padding: 0 1.25rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-sizing: border-box;
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
        .page-body { padding: 1.25rem 1.5rem; max-width: 1400px; width: 100%; margin: 0 auto; box-sizing: border-box; }
        .topbar-right { display: flex; align-items: center; gap: 0.85rem; }

        .card { background: white; border: 1px solid #e2e8f0; border-radius: 10px; padding: 1rem 1.25rem; box-shadow: 0 1px 2px rgba(0,0,0,0.03); }
        .card-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
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
        .btn-danger { background: #fff1f2; border: 1px solid #fecdd3; color: #e11d48; }
        .btn-danger:hover { background: #e11d48; color: #ffffff; border-color: #e11d48; }

        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th {
            font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.05em;
            color: var(--text-muted); font-weight: 700; padding: 0.75rem 1rem;
            background: #f8fafc; text-align: left; border-bottom: 1px solid var(--border);
        }
        .data-table td { padding: 1rem; border-bottom: 1px solid var(--border); font-size: 0.9rem; }
        
        /* Modal */
        .modal {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(15, 23, 42, 0.4); 
            backdrop-filter: blur(4px);
            display: none; align-items: center; justify-content: center; z-index: 1000;
            padding: 1.25rem;
        }
        .modal.active { display: flex; }
        .modal-content { 
            background: white; 
            padding: 2.5rem; 
            border-radius: 24px; 
            width: 100%; 
            max-width: 480px; 
            position: relative; 
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .modal-close { position: absolute; top: 1.25rem; right: 1.25rem; cursor: pointer; font-size: 1.5rem; color: #94a3b8; transition: color 0.2s; }
        .modal-close:hover { color: var(--text-main); }
        
        @media (max-width: 640px) {
            .modal { padding: 1rem; }
            .modal-content { padding: 1.75rem; border-radius: 20px; }
            .modal-content h2 { font-size: 1.25rem !important; }
        }
        
        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-main); margin-bottom: 0.5rem; }
        .form-control {
            width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border); border-radius: 8px;
            font-family: inherit; font-size: 0.95rem; outline: none; transition: border-color 0.2s;
        }
        .form-control:focus { border-color: var(--primary); }

        .alert { padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem; font-weight: 500; }
        .alert-success { background: #f0fdf4; color: #14532d; border: 1px solid #bbf7d0; }

        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s ease; }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
            #sidebarToggle { display: flex !important; }
            .page-body { padding: 1rem; }
            .card { padding: 1rem; overflow: hidden; }
            .card-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
            .btn-primary { width: 100%; justify-content: center; }
            .topbar { padding: 0 1rem; height: 68px; }
            .topbar-right { display: none; }
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

        #sidebarToggle { display: none; }

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
        .confirm-modal-overlay.active { display: flex; }
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
            background: #ef4444; color: white; font-weight: 700; cursor: pointer; transition: all 0.2s;
        }
        .confirm-btn-cancel:hover { background: #f1f5f9; color: #475569; }
        .confirm-btn-confirm:hover { filter: brightness(0.95); transform: translateY(-1px); }
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
            <a href="{{ route('superadmin.dashboard') }}" class="menu-item">
                <i class="ph ph-squares-four"></i> Overview
            </a>
            <a href="{{ route('superadmin.admins') }}" class="menu-item active">
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
                <button type="submit" class="logout-btn"><i class="ph-bold ph-sign-out"></i> Logout</button>
            </form>
        </div>
    </div>

    <div class="main-content">
        <div class="topbar">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <button id="sidebarToggle" style="width: 44px; height: 44px; padding: 0; align-items: center; justify-content: center; border-radius: 12px; border: 1px solid var(--border); background: white; color: var(--text-main); cursor: pointer; box-shadow: none;">
                    <i class="ph ph-list" style="font-size: 1.5rem; font-weight: 800;"></i>
                </button>
                <div style="font-weight: 700; font-size: 1.15rem; color: var(--text-main);">Manage Admin Accounts</div>
            </div>
            <div class="topbar-right">
                <div title="Current Theme Color" style="
                    width: 14px; height: 14px;
                    border-radius: 50%;
                    background: var(--primary-color, var(--primary));
                    border: 2px solid rgba(255,255,255,0.4);
                    box-shadow: 0 0 0 2px var(--primary-color, var(--primary));
                    flex-shrink: 0;
                "></div>
                <div style="font-size: 0.82rem; color: var(--muted); font-weight: 500;">{{ now()->format('d M Y, H:i') }}</div>
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
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card" style="background: white; border: 1px solid #e2e8f0; border-radius: 10px; padding: 1rem 1.2rem; box-shadow: 0 1px 2px rgba(0,0,0,0.02);">
                <div class="card-header" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 0.75rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.65rem;">
                    <div>
                        <h2 class="card-title" style="font-size: 0.95rem; font-weight: 700; color: #0f172a; margin: 0;">Admin Users</h2>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                        <!-- Compact Search Bar -->
                        <div style="position: relative; display: inline-flex; align-items: center;">
                            <i class="ph-bold ph-magnifying-glass" style="position: absolute; left: 9px; color: #94a3b8; font-size: 0.8rem;"></i>
                            <input type="text" id="adminSearchInput" placeholder="Search username or email" style="padding: 0 10px 0 28px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.75rem; width: 190px; height: 32px; outline: none; transition: border-color 0.2s;" onkeyup="filterAdminTable()">
                        </div>

                        <button class="btn btn-primary" onclick="openModal('addAdminModal')" style="height: 32px; padding: 0 12px; font-size: 0.75rem; font-weight: 600; border-radius: 6px; background: var(--primary-color, #850f0f); color: white; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                            <i class="ph-bold ph-plus" style="font-size: 0.75rem;"></i> Add New Admin
                        </button>
                    </div>
                </div>

                <div style="overflow-x: auto; width: 100%; -webkit-overflow-scrolling: touch;">
                    <table class="data-table" id="adminTable" style="width: 100%; border-collapse: collapse; min-width: 700px;">
                        <thead>
                            <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                                <th style="padding: 10px 12px; width: 36px; text-align: center;"><input type="checkbox" id="selectAllCheckboxes" onclick="toggleSelectAll(this)" style="cursor: pointer; width: 14px; height: 14px;"></th>
                                <th style="padding: 10px 12px; font-size: 0.68rem; font-weight: 600; text-transform: uppercase; color: #64748b; letter-spacing: 0.05em;">Username</th>
                                <th style="padding: 10px 12px; font-size: 0.68rem; font-weight: 600; text-transform: uppercase; color: #64748b; letter-spacing: 0.05em;">Email ID</th>
                                <th style="padding: 10px 12px; font-size: 0.68rem; font-weight: 600; text-transform: uppercase; color: #64748b; letter-spacing: 0.05em;">Email Status</th>
                                <th style="padding: 10px 12px; font-size: 0.68rem; font-weight: 600; text-transform: uppercase; color: #64748b; letter-spacing: 0.05em;">Phone</th>
                                <th style="padding: 10px 12px; font-size: 0.68rem; font-weight: 600; text-transform: uppercase; color: #64748b; letter-spacing: 0.05em;">Account Status</th>
                                <th style="padding: 10px 12px; font-size: 0.68rem; font-weight: 600; text-transform: uppercase; color: #64748b; letter-spacing: 0.05em;">Created At</th>
                                <th style="padding: 10px 12px; font-size: 0.68rem; font-weight: 600; text-transform: uppercase; color: #64748b; letter-spacing: 0.05em; text-align: right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($admins as $admin)
                            <tr class="admin-row" style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s;">
                                <td style="padding: 10px 12px; text-align: center;"><input type="checkbox" class="row-checkbox" style="cursor: pointer; width: 14px; height: 14px;"></td>
                                <td style="padding: 10px 12px; font-weight: 600; color: #0f172a; font-size: 0.82rem;">
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div style="width: 28px; height: 28px; border-radius: 50%; background: #f1f5f9; border: 1px solid #cbd5e1; color: #475569; font-weight: 700; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; flex-shrink: 0;">
                                            {{ strtoupper(substr($admin->name, 0, 1)) }}
                                        </div>
                                        <span>{{ $admin->name }}</span>
                                        @if($admin->role === 'superadmin')
                                            <span style="padding: 1px 5px; background: #faf5ff; border: 1px solid #e9d5ff; color: #7e22ce; font-size: 0.62rem; font-weight: 700; border-radius: 4px;">SUPERADMIN</span>
                                        @endif
                                    </div>
                                </td>
                                <td style="padding: 10px 12px; color: #334155; font-size: 0.82rem;">{{ $admin->email }}</td>
                                <td style="padding: 10px 12px;">
                                    <span style="padding: 3px 8px; background: #22c55e; color: white; font-size: 0.7rem; font-weight: 700; border-radius: 5px; display: inline-flex; align-items: center; gap: 3px;">
                                        V <i class="ph-bold ph-caret-down" style="font-size: 0.6rem;"></i>
                                    </span>
                                </td>
                                <td style="padding: 10px 12px; color: #64748b; font-size: 0.82rem;">-</td>
                                <td style="padding: 10px 12px;">
                                    <span style="padding: 3px 10px; background: #22c55e; color: white; font-size: 0.7rem; font-weight: 700; border-radius: 5px; display: inline-flex; align-items: center; gap: 3px;">
                                        Active <i class="ph-bold ph-caret-down" style="font-size: 0.6rem;"></i>
                                    </span>
                                </td>
                                <td style="padding: 10px 12px; color: #64748b; font-size: 0.78rem;">{{ $admin->created_at->format('d M Y') }}</td>
                                <td style="padding: 10px 12px; text-align: right;">
                                    <div style="display: inline-flex; gap: 6px; justify-content: flex-end;">
                                        <button type="button" onclick="editAdmin({{ $admin->id }}, '{{ addslashes($admin->name) }}', '{{ addslashes($admin->email) }}')" style="padding: 5px 9px; background: #ffffff; border: 1px solid #cbd5e1; color: #475569; border-radius: 6px; cursor: pointer; transition: all 0.15s;" title="Edit Admin">
                                            <i class="ph-bold ph-pencil-simple" style="font-size: 0.85rem;"></i>
                                        </button>
                                        @if($admin->role !== 'superadmin')
                                            <form action="{{ route('superadmin.admins.delete', $admin->id) }}" method="POST" style="display: inline;" onsubmit="event.preventDefault(); showConfirmDelete(this);">
                                                @csrf @method('DELETE')
                                                <button type="submit" style="padding: 5px 9px; background: #fff1f2; border: 1px solid #fecdd3; color: #e11d48; border-radius: 6px; cursor: pointer; transition: all 0.15s;" title="Delete Admin">
                                                    <i class="ph-bold ph-trash" style="font-size: 0.85rem;"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Admin Modal -->
    <div id="addAdminModal" class="modal">
        <div class="modal-content">
            <i class="ph ph-x modal-close" onclick="closeModal('addAdminModal')"></i>
            <h2 style="margin-bottom: 1.5rem;">Add New Admin</h2>
            <form action="{{ route('superadmin.admins.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Admin Name" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="admin@example.com" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Min 6 characters" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 1rem; padding: 0.8rem;">
                    Create Admin Account
                </button>
            </form>
        </div>
    </div>

    <!-- Edit Admin Modal -->
    <div id="editAdminModal" class="modal">
        <div class="modal-content">
            <i class="ph ph-x modal-close" onclick="closeModal('editAdminModal')"></i>
            <h2 style="margin-bottom: 1.5rem;">Edit Admin Credentials</h2>
            <form id="editForm" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" id="edit_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" id="edit_email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">New Password (Leave blank to keep current)</label>
                    <input type="password" name="password" class="form-control" placeholder="Update password if needed">
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 1rem; padding: 0.8rem;">
                    Save Changes
                </button>
            </form>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmDeleteModal" class="confirm-modal-overlay">
        <div class="confirm-modal-content">
            <h3>Delete Admin Account</h3>
            <p>Are you sure you want to remove this admin? This action cannot be undone.</p>
            <div class="confirm-modal-footer">
                <button type="button" class="confirm-btn-cancel" onclick="closeConfirmModal()">Cancel</button>
                <button type="button" class="confirm-btn-confirm" onclick="executeDelete()">Yes, Delete</button>
            </div>
        </div>
    </div>
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

        function filterAdminTable() {
            const input = document.getElementById('adminSearchInput').value.toLowerCase();
            const rows = document.querySelectorAll('#adminTable tbody tr.admin-row');
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(input) ? '' : 'none';
            });
        }

        function toggleSelectAll(masterCheckbox) {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            checkboxes.forEach(cb => cb.checked = masterCheckbox.checked);
        }

        function openModal(id) { document.getElementById(id).classList.add('active'); }
        function closeModal(id) { document.getElementById(id).classList.remove('active'); }

        function editAdmin(id, name, email) {
            document.getElementById('editForm').action = "/superadmin/admins/" + id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_email').value = email;
            openModal('editAdminModal');
        }

        // Profile Dropdown Toggle
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

        // Delete Confirmation Logic
        let formToDelete = null;
        function showConfirmDelete(form) {
            formToDelete = form;
            document.getElementById('confirmDeleteModal').classList.add('active');
        }
        function closeConfirmModal() {
            document.getElementById('confirmDeleteModal').classList.remove('active');
            formToDelete = null;
        }
        function executeDelete() {
            if (formToDelete) formToDelete.submit();
        }
    </script>
</body>
</html>
