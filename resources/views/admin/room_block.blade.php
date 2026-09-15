<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Block Management - Admin Panel</title>
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

        * { box-sizing: border-box; font-family: 'Inter', sans-serif; }

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

        .sidebar-header { padding: 1.5rem; border-bottom: 1px solid var(--border); }
        .sidebar-logo img { height: 80px; width: auto; object-fit: contain; }

        .sidebar-menu { padding: 1.5rem 0.75rem; flex: 1; display: flex; flex-direction: column; }
        .menu-item {
            display: flex; align-items: center; gap: 0.75rem; padding: 0.85rem 1rem;
            color: var(--text-muted); text-decoration: none; border-radius: 8px; font-weight: 500;
            transition: all 0.2s ease; margin-bottom: 0.25rem;
        }
        .menu-item:hover { background: rgba(133, 15, 15, 0.08); color: var(--primary-color); }
        .menu-item.active {
            background: rgba(133, 15, 15, 0.1); color: var(--primary-color);
            font-weight: 600; border-left: 3px solid var(--primary-color); padding-left: calc(1rem - 3px);
        }

        .admin-main {
            margin-left: var(--sidebar-width);
            flex: 1; display: flex; flex-direction: column;
            width: calc(100% - var(--sidebar-width)); min-width: 0;
            transition: all 0.3s ease;
        }

        .top-navbar {
            height: 72px; background: white; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between; padding: 0 2rem;
        }

        .admin-body { padding: 2rem; max-width: 1400px; margin: 0 auto; width: 100%; box-sizing: border-box; }

        .form-card {
            background: #ffffff; border-radius: 16px; border: 1px solid var(--border);
            padding: 2rem; box-shadow: 0 4px 12px rgba(0,0,0,0.03); margin-bottom: 2rem;
        }

        .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; }
        .form-group.full-width { grid-column: span 2; }
        .form-group label { display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.5rem; }

        .input-wrapper { position: relative; display: flex; align-items: center; }
        .input-wrapper i { position: absolute; left: 1rem; color: #64748b; font-size: 1.1rem; pointer-events: none; }
        .input-wrapper input, .input-wrapper select {
            width: 100%; padding: 0.75rem 1rem 0.75rem 2.8rem; border: 1.5px solid var(--border);
            border-radius: 10px; font-size: 0.95rem; font-weight: 600; color: #0f172a; outline: none; transition: all 0.2s;
        }
        .input-wrapper input:focus, .input-wrapper select:focus {
            border-color: #dc2626; box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }

        .btn-submit {
            display: inline-flex; align-items: center; gap: 0.5rem; background: #dc2626;
            color: #ffffff; padding: 0.8rem 2.2rem; border-radius: 10px; font-weight: 700;
            font-size: 0.95rem; border: none; cursor: pointer; transition: all 0.2s ease;
        }
        .btn-submit:hover { background: #b91c1c; transform: translateY(-1px); }

        .status-pill {
            padding: 0.35rem 0.75rem; border-radius: 999px; font-size: 0.75rem; font-weight: 700;
            display: inline-flex; align-items: center; gap: 0.35rem; text-transform: uppercase;
        }
        .pill-blocked { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .admin-main { margin-left: 0 !important; width: 100% !important; }
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
                <div style="font-weight: 700; font-size: 1.15rem; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                    <i class="ph-bold ph-prohibit" style="color: #dc2626;"></i>
                    Room Block Management
                </div>
            </div>
            <div style="font-size: 0.85rem; color: var(--text-muted); font-weight: 500;">
                {{ now()->format('d M Y') }}
            </div>
        </div>

        <div class="admin-body">
            @if(session('success'))
                <div style="background: #dcfce7; color: #166534; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; border: 1px solid #bbf7d0;">
                    <i class="ph-bold ph-check-circle" style="font-size: 1.2rem;"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div style="background: #fef2f2; color: #991b1b; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; border: 1px solid #fecaca;">
                    <i class="ph-bold ph-warning-circle" style="font-size: 1.2rem;"></i> {{ session('error') }}
                </div>
            @endif

            <!-- Create Room Block Form Card -->
            <div class="form-card">
                <div style="margin-bottom: 1.5rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1rem;">
                    <h2 style="margin:0 0 0.25rem 0; font-size: 1.3rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                        <i class="ph-bold ph-prohibit" style="color: #dc2626;"></i> Create New Room Block
                    </h2>
                    <p style="margin:0; font-size: 0.85rem; color: #64748b;">Block room availability for maintenance, official university events, or VIP reservations.</p>
                </div>

                <form action="{{ route('admin.room-block.store') }}" method="POST">
                    @csrf
                    <div class="form-grid">
                        <!-- Select Room -->
                        <div class="form-group full-width">
                            <label for="room_name">Select Room / Facility to Block <span style="color: #dc2626;">*</span></label>
                            <div class="input-wrapper">
                                <i class="ph ph-bed"></i>
                                <select name="room_name" id="room_name" required>
                                    <option value="">-- Select Room to Block --</option>
                                    <optgroup label="Special Options">
                                        <option value="All Rooms">All Rooms (Full Facility Block)</option>
                                    </optgroup>
                                    <optgroup label="Standard Rooms (₹2,000 / ₹8,000)">
                                        @foreach(range(1, 20) as $num)
                                            <option value="Room {{ $num }}">Standard Room {{ $num }}</option>
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="Executive Rooms">
                                        <option value="Room 101">Executive Room 101</option>
                                        <option value="Room 201">Executive Room 201</option>
                                        <option value="Room 203">Executive Room 203</option>
                                        <option value="Room 207">Executive Room 207</option>
                                    </optgroup>
                                    <optgroup label="Suite Rooms (₹2,000 / ₹3,000)">
                                        <option value="Suite Room 202">Suite Room 202</option>
                                    </optgroup>
                                    <optgroup label="Conference & Special Facilities">
                                        <option value="Conference Room">Conference Room</option>
                                        <option value="Glass Room">Glass Room</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>

                        <!-- Check-in Start Date -->
                        <div class="form-group">
                            <label for="block_date">Start Date <span style="color: #dc2626;">*</span></label>
                            <div class="input-wrapper">
                                <i class="ph ph-calendar"></i>
                                <input type="date" name="block_date" id="block_date" required value="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <!-- Check-out End Date -->
                        <div class="form-group">
                            <label for="end_date">End Date <span style="color: #dc2626;">*</span></label>
                            <div class="input-wrapper">
                                <i class="ph ph-calendar-check"></i>
                                <input type="date" name="end_date" id="end_date" required value="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <!-- Start Time -->
                        <div class="form-group">
                            <label for="start_time">Start Time</label>
                            <div class="input-wrapper">
                                <i class="ph ph-clock"></i>
                                <input type="time" name="start_time" id="start_time" value="00:00">
                            </div>
                        </div>

                        <!-- End Time -->
                        <div class="form-group">
                            <label for="end_time">End Time</label>
                            <div class="input-wrapper">
                                <i class="ph ph-clock-afternoon"></i>
                                <input type="time" name="end_time" id="end_time" value="23:59">
                            </div>
                        </div>

                        <!-- Reason -->
                        <div class="form-group full-width">
                            <label for="reason">Reason / Notes</label>
                            <div class="input-wrapper">
                                <i class="ph ph-notebook"></i>
                                <input type="text" name="reason" id="reason" placeholder="e.g. Maintenance & Cleaning, Official VIP Reserved">
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid #f1f5f9;">
                        <button type="submit" class="btn-submit">
                            <i class="ph-bold ph-prohibit"></i> Confirm Room Block
                        </button>
                    </div>
                </form>
            </div>

            <!-- Active Room Blocks Table -->
            <div style="background: #ffffff; border-radius: 16px; border: 1px solid var(--border); padding: 1.75rem; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                    <h3 style="margin:0; font-size: 1.15rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                        <i class="ph-bold ph-list-checks" style="color: var(--primary-color);"></i>
                        Active & Recent Room Blocks ({{ $activeBlocks->count() }})
                    </h3>
                </div>

                @if($activeBlocks->isEmpty())
                    <div style="text-align: center; padding: 2.5rem 1rem; color: #64748b; background: #f8fafc; border-radius: 12px; border: 1px dashed #cbd5e1;">
                        <i class="ph-bold ph-check-circle" style="font-size: 2.5rem; color: #22c55e; margin-bottom: 0.5rem; display: block;"></i>
                        <h4 style="margin: 0 0 0.25rem 0; font-size: 1.05rem; font-weight: 800; color: #1e293b;">No Active Room Blocks</h4>
                        <p style="margin:0; font-size: 0.85rem;">All rooms are currently available for standard reservations.</p>
                    </div>
                @else
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.88rem;">
                            <thead>
                                <tr style="background: #f8fafc; border-bottom: 1.5px solid #e2e8f0; color: #475569; text-transform: uppercase; font-size: 0.72rem; letter-spacing: 0.05em;">
                                    <th style="padding: 0.75rem 1rem;">ID</th>
                                    <th style="padding: 0.75rem 1rem;">Room(s) Blocked</th>
                                    <th style="padding: 0.75rem 1rem;">Duration (Clock In &rarr; Clock Out)</th>
                                    <th style="padding: 0.75rem 1rem;">Reason / Notes</th>
                                    <th style="padding: 0.75rem 1rem;">Status</th>
                                    <th style="padding: 0.75rem 1rem; text-align: right;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activeBlocks as $block)
                                    <tr style="border-bottom: 1px solid #f1f5f9;">
                                        <td style="padding: 0.85rem 1rem; font-weight: 800; color: var(--primary-color);">#{{ $block->id }}</td>
                                        <td style="padding: 0.85rem 1rem; font-weight: 700; color: #0f172a;">
                                            <i class="ph-bold ph-bed" style="color: #dc2626;"></i> {{ $block->room_name }}
                                        </td>
                                        <td style="padding: 0.85rem 1rem; color: #475569; font-weight: 600;">
                                            <i class="ph-bold ph-clock"></i> {{ $block->clock_in ? \Carbon\Carbon::parse($block->clock_in)->format('d M Y, h:i A') : $block->booking_date }}
                                            &rarr;
                                            {{ $block->clock_out ? \Carbon\Carbon::parse($block->clock_out)->format('d M Y, h:i A') : $block->end_time }}
                                        </td>
                                        <td style="padding: 0.85rem 1rem; color: #334155;">{{ $block->booking_reason ?: $block->name }}</td>
                                        <td style="padding: 0.85rem 1rem;">
                                            <span class="status-pill pill-blocked"><i class="ph-bold ph-prohibit"></i> BLOCKED</span>
                                        </td>
                                        <td style="padding: 0.85rem 1rem; text-align: right;">
                                            <form action="{{ route('admin.bookings.destroy', $block->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to unblock this room? This will make the room available for new bookings.');" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" style="padding: 5px 12px; font-size: 0.78rem; font-weight: 700; border-radius: 6px; border: 1px solid #fecdd3; background: #fff1f2; color: #dc2626; cursor: pointer; transition: all 0.2s;">
                                                    <i class="ph-bold ph-trash"></i> Unblock Room
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </main>

    <script>
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
