<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webhook Settings - SuperAdmin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <style>
        :root {
            --sidebar-width: 260px;
            --bg-color: #f8fafc;
            --border: #e2e8f0;
            --text-main: #1e293b;
            --text-muted: #64748b;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { background: var(--bg-color); color: var(--text-main); }

        .sidebar { width: var(--sidebar-width); background: white; height: 100vh; border-right: 1px solid var(--border); position: fixed; display: flex; flex-direction: column; z-index: 100; }
        .sidebar-header { padding: 1.5rem; border-bottom: 1px solid var(--border); }
        .sidebar-logo { display: flex; align-items: center; padding: 0.25rem 0; }
        .sidebar-logo img { height: 80px; width: auto; object-fit: contain; }
        .sidebar-menu { flex: 1; padding: 1rem 0.75rem; display: flex; flex-direction: column; }
        .menu-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; color: var(--text-muted); text-decoration: none; border-radius: 8px; font-weight: 500; font-size: 0.9rem; transition: all 0.2s; margin-bottom: 0.25rem; }
        .menu-item:hover, .menu-item.active { background: rgba(133, 15, 15, 0.08); color: var(--primary-color); }
        .sidebar-footer { padding: 1rem; border-top: 1px solid var(--border); }
        .logout-btn { width: 100%; display: flex; align-items: center; gap: 0.75rem; background: none; border: none; padding: 0.75rem 1rem; color: var(--danger); cursor: pointer; font-weight: 600; border-radius: 8px; font-size: 0.9rem; transition: background 0.2s; }
        .logout-btn:hover { background: #fef2f2; }

        .main-content { margin-left: var(--sidebar-width); width: calc(100% - var(--sidebar-width)); display: flex; flex-direction: column; min-height: 100vh; }
        .topbar { height: 72px; background: white; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; padding: 0 2rem; position: sticky; top: 0; z-index: 90; }
        .topbar-title { font-weight: 700; font-size: 1.1rem; }
        .page-body { padding: 2.5rem; max-width: 1200px; width: 100%; margin: 0 auto; }

        .card { background: white; border: 1px solid var(--border); border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); margin-bottom: 2rem; }
        .card-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; }
        .card-title { font-size: 1.1rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem; }

        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-muted); margin-bottom: 0.5rem; }
        .form-input { width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border); border-radius: 10px; font-size: 0.9rem; transition: border-color 0.2s; }
        .form-input:focus { outline: none; border-color: var(--primary-color); }

        .btn { padding: 0.75rem 1.5rem; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.2s; border: none; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 0.5rem; }
        .btn-primary { background: var(--primary-color); color: white; }
        .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }
        .btn-outline { background: transparent; border: 1px solid var(--border); color: var(--text-main); }
        .btn-outline:hover { background: #f8fafc; }
        .btn-danger { background: #fee2e2; color: var(--danger); }
        .btn-danger:hover { background: var(--danger); color: white; }

        .webhook-list { display: grid; gap: 1rem; }
        .webhook-item { border: 1px solid var(--border); border-radius: 12px; padding: 1.25rem; display: flex; align-items: center; justify-content: space-between; transition: all 0.2s; }
        .webhook-item:hover { border-color: var(--primary-color); box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
        .webhook-info { display: flex; flex-direction: column; gap: 0.25rem; }
        .webhook-url { font-weight: 700; font-size: 0.95rem; color: var(--text-main); }
        .webhook-meta { font-size: 0.75rem; color: var(--text-muted); display: flex; align-items: center; gap: 1rem; }
        .webhook-events { display: flex; gap: 0.5rem; flex-wrap: wrap; margin-top: 0.5rem; }
        .event-tag { font-size: 0.65rem; background: #f1f5f9; color: #475569; padding: 0.2rem 0.5rem; border-radius: 4px; font-weight: 600; text-transform: uppercase; }
        .btn-text { display: inline; }
        @media (max-width: 480px) {
            .btn-text { display: none; }
            .btn { min-width: 40px; height: 40px; padding: 0; justify-content: center; }
            .btn i { margin: 0; font-size: 1.2rem; }
        }

        .checkbox-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 0.75rem; background: #f8fafc; padding: 1rem; border-radius: 10px; border: 1px solid var(--border); }
        .checkbox-item { display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: var(--text-main); cursor: pointer; }
        .checkbox-item input { width: 16px; height: 16px; accent-color: var(--primary-color); }

        .status-badge { padding: 0.2rem 0.6rem; border-radius: 999px; font-size: 0.7rem; font-weight: 700; }
        .status-active { background: #dcfce7; color: #15803d; }
        .status-inactive { background: #f1f5f9; color: #64748b; }

        .alert { padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; font-size: 0.9rem; font-weight: 500; }
        .alert-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }

        /* Modal */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(4px); }
        .modal.open { display: flex; }
        .modal-content { background: white; border-radius: 16px; width: 100%; max-width: 600px; padding: 2rem; position: relative; animation: modalIn 0.3s ease-out; }
        @keyframes modalIn { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .modal-close { position: absolute; top: 1.5rem; right: 1.5rem; cursor: pointer; color: var(--text-muted); font-size: 1.5rem; }
        
        .menu-toggle { display: none; background: white; border: 1px solid var(--border); border-radius: 8px; width: 40px; height: 40px; align-items: center; justify-content: center; color: var(--text-main); cursor: pointer; font-size: 1.25rem; transition: all 0.2s; }
        .menu-toggle:hover { background: #f8fafc; color: var(--primary-color); border-color: var(--primary-color); }

        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s ease; }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; width: 100%; }
            .topbar { padding: 0 1rem; height: 64px; }
            .menu-toggle { display: flex; }
            .page-body { padding: 1rem; }
            .card { padding: 1.25rem; }
            .topbar-title { font-size: 1rem; }
            .btn { padding: 0.6rem 1rem; font-size: 0.8rem; }
        }

        @media (max-width: 640px) {
            .webhook-item { flex-direction: column; align-items: flex-start; gap: 1rem; }
            .webhook-item > div:last-child { width: 100%; justify-content: flex-end; }
            .webhook-meta { flex-direction: column; align-items: flex-start; gap: 0.5rem; }
            .checkbox-grid { grid-template-columns: 1fr; }
            .modal-content { padding: 1.5rem; width: 95%; margin: 10px; }
            .topbar-title { display: none; } /* Hide title to save space on very small screens */
        }
    </style>
    @include('partials.dynamic-styles')
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <img src="/assets/logo_transparent.png" alt="MCC-MRF Innovation Park" style="height:80px; width:auto; object-fit:contain;">
            </div>
            <div style="font-size: 0.7rem; color: #94a3b8; margin-top: 4px; font-weight: 600;">SUPERADMIN PANEL</div>
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
            <a href="{{ route('superadmin.webhooks') }}" class="menu-item {{ Route::is('superadmin.webhooks') ? 'active' : '' }}">
                <i class="ph-bold ph-plugs-connected"></i> Webhooks
            </a>
            <a href="{{ route('superadmin.webhooks.logs') }}" class="menu-item {{ Route::is('superadmin.webhooks.logs') ? 'active' : '' }}">
                <i class="ph-bold ph-article"></i> Webhook Logs
            </a>
            <a href="{{ route('superadmin.settings') }}" class="menu-item {{ Route::is('superadmin.settings') ? 'active' : '' }}">
                <i class="ph ph-gear"></i> System Settings
            </a>
        </nav>
        <div class="sidebar-footer">
            <form action="{{ route('superadmin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="ph-bold ph-sign-out"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <div class="main-content">
        <div class="topbar">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <button id="sidebarToggle" class="menu-toggle">
                    <i class="ph ph-list"></i>
                </button>
                <div class="topbar-title">Webhook Management</div>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <a href="{{ route('superadmin.webhooks.logs') }}" class="btn btn-outline" style="padding: 0.5rem; width: 40px; height: 40px; justify-content: center;">
                    <i class="ph ph-article"></i>
                </a>
                <button class="btn btn-primary" onclick="openModal('addWebhookModal')">
                    <i class="ph ph-plus"></i> <span class="btn-text">Add Endpoint</span>
                </button>
            </div>
        </div>

        <div class="page-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="ph ph-plug-connected" style="color: var(--primary-color)"></i>
                        Registered Webhooks
                    </div>
                </div>

                <div class="webhook-list">
                    @forelse($endpoints as $endpoint)
                        <div class="webhook-item">
                            <div class="webhook-info">
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <span class="webhook-url">{{ $endpoint->url }}</span>
                                    <span class="status-badge {{ $endpoint->is_active ? 'status-active' : 'status-inactive' }}">
                                        {{ $endpoint->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                                <div class="webhook-meta">
                                    <span><i class="ph ph-key"></i> Secret: <code>{{ Str::limit($endpoint->secret, 12) }}...</code></span>
                                    <span><i class="ph ph-activity"></i> {{ $endpoint->logs_count }} Deliveries</span>
                                    <span><i class="ph ph-clock"></i> Added {{ $endpoint->created_at->format('d M Y') }}</span>
                                </div>
                                <div class="webhook-events">
                                    @foreach($endpoint->events as $event)
                                        <span class="event-tag">{{ str_replace('.', ' ', $event) }}</span>
                                    @endforeach
                                    @if(empty($endpoint->events))
                                        <span class="event-tag" style="background: #fef9c3; color: #a16207;">ALL EVENTS</span>
                                    @endif
                                </div>
                            </div>
                            <div style="display: flex; gap: 0.5rem;">
                                <button class="btn btn-outline" onclick="editWebhook({{ json_encode($endpoint) }})" style="padding: 0.5rem; width: 40px; height: 40px; justify-content: center;">
                                    <i class="ph ph-pencil-simple"></i>
                                </button>
                                <form action="{{ route('superadmin.webhooks.delete', $endpoint->id) }}" method="POST" onsubmit="return confirm('Delete this webhook endpoint?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 0.5rem; width: 40px; height: 40px; justify-content: center;">
                                        <i class="ph ph-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 3rem; color: var(--text-muted);">
                            <i class="ph ph-plug" style="font-size: 3rem; opacity: 0.2; margin-bottom: 1rem;"></i>
                            <p>No webhook endpoints registered yet.</p>
                            <button class="btn btn-primary" onclick="openModal('addWebhookModal')" style="margin-top: 1rem;">Register First Endpoint</button>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Add Webhook Modal -->
    <div id="addWebhookModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeModal('addWebhookModal')">&times;</span>
            <div class="card-title" style="margin-bottom: 1.5rem;">Register New Webhook</div>
            <form action="{{ route('superadmin.webhooks.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Endpoint URL</label>
                    <input type="url" name="url" class="form-input" placeholder="https://your-app.com/webhook" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Events to Subscribe</label>
                    <div class="checkbox-grid">
                        @foreach($availableEvents as $event)
                            <label class="checkbox-item">
                                <input type="checkbox" name="events[]" value="{{ $event }}" checked>
                                {{ ucwords(str_replace(['.', '_'], ' ', $event)) }}
                            </label>
                        @endforeach
                    </div>
                    <p style="font-size: 0.7rem; color: var(--text-muted); margin-top: 0.5rem;">Select which events should trigger this webhook.</p>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 2rem;">
                    <button type="button" class="btn btn-outline" onclick="closeModal('addWebhookModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Endpoint</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Webhook Modal -->
    <div id="editWebhookModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeModal('editWebhookModal')">&times;</span>
            <div class="card-title" style="margin-bottom: 1.5rem;">Edit Webhook Endpoint</div>
            <form id="editForm" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Endpoint URL</label>
                    <input type="url" name="url" id="edit_url" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Events to Subscribe</label>
                    <div class="checkbox-grid">
                        @foreach($availableEvents as $event)
                            <label class="checkbox-item">
                                <input type="checkbox" name="events[]" value="{{ $event }}" class="edit_event" data-event="{{ $event }}">
                                {{ ucwords(str_replace(['.', '_'], ' ', $event)) }}
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="form-group">
                    <label class="checkbox-item">
                        <input type="checkbox" name="is_active" id="edit_active" value="1">
                        Active / Enabled
                    </label>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 2rem;">
                    <button type="button" class="btn btn-outline" onclick="closeModal('editWebhookModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Endpoint</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Sidebar Toggle
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('.sidebar');
        
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                sidebar.classList.toggle('open');
            });
        }

        document.addEventListener('click', (e) => {
            if (sidebar.classList.contains('open') && !sidebar.contains(e.target) && e.target !== sidebarToggle) {
                sidebar.classList.remove('open');
            }
        });

        function openModal(id) {
            document.getElementById(id).classList.add('open');
        }
        function closeModal(id) {
            document.getElementById(id).classList.remove('open');
        }

        function editWebhook(endpoint) {
            const form = document.getElementById('editForm');
            form.action = `/superadmin/webhooks/${endpoint.id}`;
            document.getElementById('edit_url').value = endpoint.url;
            document.getElementById('edit_active').checked = endpoint.is_active;
            
            // Reset and set checkboxes
            const checkboxes = document.querySelectorAll('.edit_event');
            checkboxes.forEach(cb => {
                cb.checked = endpoint.events.includes(cb.getAttribute('data-event'));
            });

            openModal('editWebhookModal');
        }

        // Close on escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal').forEach(m => m.classList.remove('open'));
            }
        });
    </script>
</body>
</html>
