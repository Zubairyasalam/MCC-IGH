<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webhook Logs - SuperAdmin</title>
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
            --success: #22c55e;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
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
        .page-body { padding: 2.5rem; max-width: 1400px; width: 100%; margin: 0 auto; }

        .card { background: white; border: 1px solid var(--border); border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); margin-bottom: 2rem; }
        .card-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; }
        .card-title { font-size: 1.1rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem; }

        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th { padding: 1rem; text-align: left; border-bottom: 1px solid var(--border); font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; background: #f8fafc; }
        .data-table td { padding: 1rem; border-bottom: 1px solid var(--border); font-size: 0.85rem; vertical-align: middle; }
        .data-table tr:hover { background: #fafafa; }

        .status-pill { padding: 0.25rem 0.75rem; border-radius: 999px; font-size: 0.75rem; font-weight: 700; }
        .status-success { background: #dcfce7; color: #15803d; }
        .status-failed { background: #fee2e2; color: #b91c1c; }
        .status-warning { background: #fef9c3; color: #a16207; }

        .btn { padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.2s; border: none; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 0.4rem; }
        .btn-outline { background: transparent; border: 1px solid var(--border); color: var(--text-main); }
        .btn-outline:hover { background: #f1f5f9; }
        .btn-primary { background: var(--primary-color); color: white; }

        .filters { display: flex; gap: 1rem; margin-bottom: 1.5rem; background: white; padding: 1.25rem; border-radius: 12px; border: 1px solid var(--border); align-items: flex-end; }
        .filter-group { display: flex; flex-direction: column; gap: 0.4rem; }
        .filter-label { font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; }
        .filter-input { padding: 0.5rem 0.75rem; border: 1px solid var(--border); border-radius: 6px; font-size: 0.85rem; }

        .payload-preview { max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-family: monospace; font-size: 0.75rem; color: var(--text-muted); }

        .pagination { display: flex; justify-content: center; margin-top: 2rem; gap: 0.5rem; }
        .pagination a, .pagination span { padding: 0.5rem 1rem; border: 1px solid var(--border); border-radius: 8px; background: white; text-decoration: none; color: var(--text-main); font-size: 0.9rem; }
        .pagination .active { background: var(--primary-color); color: white; border-color: var(--primary-color); }

        /* Modal for JSON View */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(4px); }
        .modal.open { display: flex; }
        .modal-content { background: white; border-radius: 16px; width: 100%; max-width: 800px; padding: 2rem; position: relative; max-height: 90vh; overflow-y: auto; }
        .json-block { background: #1e293b; color: #e2e8f0; padding: 1.5rem; border-radius: 10px; font-family: 'JetBrains Mono', monospace; font-size: 0.85rem; overflow-x: auto; white-space: pre-wrap; margin-top: 1rem; }
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
            .topbar-title { font-size: 1rem; }
            .filters { flex-direction: column; align-items: stretch; gap: 1rem; }
            .filter-group { width: 100%; }
            .filter-input { width: 100%; }
        }

        @media (max-width: 640px) {
            .topbar-title { display: none; }
            .modal-content { padding: 1.25rem; width: 95%; margin: 10px; }
            .json-block { font-size: 0.75rem; padding: 1rem; }
            .btn span { display: none; }
            .btn { min-width: 40px; height: 40px; padding: 0; justify-content: center; }
            .btn i { margin: 0; font-size: 1.1rem; }
            .data-table th, .data-table td { padding: 0.75rem 0.5rem; font-size: 0.75rem; }
        }
    </style>
    @include('partials.dynamic-styles')
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <img src="/assets/logo.png" alt="MCC-MRF Innovation Park" style="height:80px; width:auto; object-fit:contain;">
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
                <div class="topbar-title">Webhook Delivery Logs</div>
            </div>
            <div>
                <a href="{{ route('superadmin.webhooks') }}" class="btn btn-outline">
                    <i class="ph ph-gear"></i> <span>Endpoint Settings</span>
                </a>
            </div>
        </div>

        <div class="page-body">
            @if(session('success'))
                <div style="background: #f0fdf4; color: #166534; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; border: 1px solid #bbf7d0;">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('superadmin.webhooks.logs') }}" method="GET" class="filters">
                <div class="filter-group">
                    <label class="filter-label">Event</label>
                    <select name="event" class="filter-input">
                        <option value="">All Events</option>
                        <option value="booking.created" {{ request('event') == 'booking.created' ? 'selected' : '' }}>Booking Created</option>
                        <option value="booking.confirmed" {{ request('event') == 'booking.confirmed' ? 'selected' : '' }}>Booking Confirmed</option>
                        <option value="booking.cancelled" {{ request('event') == 'booking.cancelled' ? 'selected' : '' }}>Booking Cancelled</option>
                        <option value="payment.initiated" {{ request('event') == 'payment.initiated' ? 'selected' : '' }}>Payment Initiated</option>
                        <option value="payment.successful" {{ request('event') == 'payment.successful' ? 'selected' : '' }}>Payment Successful</option>
                        <option value="payment.failed" {{ request('event') == 'payment.failed' ? 'selected' : '' }}>Payment Failed</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Status</label>
                    <select name="status" class="filter-input">
                        <option value="">All Statuses</option>
                        <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success (2xx)</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1.5rem;">Filter</button>
                <a href="{{ route('superadmin.webhooks.logs') }}" class="btn btn-outline">Reset</a>
            </form>

            <div class="card" style="padding: 0;">
                <div style="overflow-x: auto;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Timestamp</th>
                                <th>Endpoint</th>
                                <th>Event</th>
                                <th>Status</th>
                                <th>Tries</th>
                                <th>Payload</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr>
                                    <td style="white-space: nowrap;">
                                        <div style="font-weight: 600;">{{ $log->created_at->format('H:i:s') }}</div>
                                        <div style="font-size: 0.7rem; color: var(--text-muted);">{{ $log->created_at->format('d M Y') }}</div>
                                    </td>
                                    <td>
                                        <div style="font-weight: 600; font-size: 0.8rem;">{{ parse_url($log->endpoint->url, PHP_URL_HOST) }}</div>
                                        <div style="font-size: 0.7rem; color: var(--text-muted);">{{ $log->endpoint->url }}</div>
                                    </td>
                                    <td>
                                        <span class="event-tag" style="background: #eff6ff; color: #1e40af; font-size: 0.65rem; padding: 0.2rem 0.5rem; border-radius: 4px; font-weight: 700;">{{ strtoupper(str_replace('.', ' ', $log->event)) }}</span>
                                    </td>
                                    <td>
                                        @if($log->response_status >= 200 && $log->response_status < 300)
                                            <span class="status-pill status-success">{{ $log->response_status }} OK</span>
                                        @elseif($log->response_status)
                                            <span class="status-pill status-failed">{{ $log->response_status }} Error</span>
                                        @else
                                            <span class="status-pill status-failed">Failed</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="font-weight: 600;">{{ $log->retry_count + 1 }}</div>
                                    </td>
                                    <td>
                                        <div class="payload-preview">{{ json_encode($log->payload) }}</div>
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 0.5rem;">
                                            <button class="btn btn-outline" onclick="viewLog({{ json_encode($log) }})" title="View Details">
                                                <i class="ph ph-eye"></i>
                                            </button>
                                            @if(!($log->response_status >= 200 && $log->response_status < 300))
                                                <form action="{{ route('superadmin.webhooks.retry', $log->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline" style="color: var(--primary-color)" title="Manual Retry">
                                                        <i class="ph ph-arrows-clockwise"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 3rem; color: var(--text-muted);">No logs found for the selected criteria.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="pagination">
                {{ $logs->links() }}
            </div>
        </div>
    </div>

    <!-- View Log Modal -->
    <div id="viewLogModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeModal()">&times;</span>
            <h2 id="modalTitle" style="margin-bottom: 1.5rem;">Webhook Delivery Details</h2>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div>
                    <label class="filter-label">Endpoint</label>
                    <div style="font-weight: 600;" id="modalEndpoint"></div>
                </div>
                <div>
                    <label class="filter-label">Event</label>
                    <div style="font-weight: 600;" id="modalEvent"></div>
                </div>
                <div>
                    <label class="filter-label">Timestamp</label>
                    <div id="modalTime"></div>
                </div>
                <div>
                    <label class="filter-label">Response Status</label>
                    <div id="modalStatus"></div>
                </div>
            </div>

            <div>
                <label class="filter-label">Request Payload</label>
                <div class="json-block" id="modalPayload"></div>
            </div>

            <div style="margin-top: 1.5rem;">
                <label class="filter-label">Response Body</label>
                <div class="json-block" id="modalResponseBody"></div>
            </div>

            <div style="margin-top: 1.5rem;" id="errorSection">
                <label class="filter-label" style="color: var(--danger)">Error Message</label>
                <div style="padding: 1rem; background: #fee2e2; border-radius: 8px; color: #b91c1c; font-size: 0.85rem; margin-top: 0.5rem;" id="modalError"></div>
            </div>

            <div style="display: flex; justify-content: flex-end; margin-top: 2rem;">
                <button class="btn btn-primary" onclick="closeModal()">Close</button>
            </div>
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

        function viewLog(log) {
            document.getElementById('modalEndpoint').innerText = log.endpoint.url;
            document.getElementById('modalEvent').innerText = log.event.toUpperCase();
            document.getElementById('modalTime').innerText = new Date(log.created_at).toLocaleString();
            document.getElementById('modalStatus').innerText = log.response_status || 'N/A';
            document.getElementById('modalPayload').innerText = JSON.stringify(log.payload, null, 2);
            document.getElementById('modalResponseBody').innerText = log.response_body || '(Empty Response)';
            
            if (log.error) {
                document.getElementById('errorSection').style.display = 'block';
                document.getElementById('modalError').innerText = log.error;
            } else {
                document.getElementById('errorSection').style.display = 'none';
            }

            document.getElementById('viewLogModal').classList.add('open');
        }

        function closeModal() {
            document.getElementById('viewLogModal').classList.remove('open');
        }

        // Close on escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeModal();
        });
    </script>
</body>
</html>
