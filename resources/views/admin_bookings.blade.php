<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Bookings</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <style>
        body { background: #f8fafc; padding: 2rem; font-family: 'Inter', sans-serif; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        h1 { margin-bottom: 2rem; color: #1e293b; display: flex; align-items: center; gap: 0.5rem; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { padding: 1rem; text-align: left; border-bottom: 1px solid #e2e8f0; }
        th { background: #f1f5f9; color: #475569; font-weight: 600; }
        .status { padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; }
        .status-paid { background: #dcfce7; color: #15803d; }
        .status-pending { background: #fef9c3; color: #854d0e; }
        .status-failed { background: #fee2e2; color: #b91c1c; }
        .btn-refresh { background: #0ea5e9; color: white; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; font-weight: 500; font-size: 0.875rem; transition: background 0.2s; }
        .btn-refresh:hover { background: #0284c7; }
    </style>
</head>
<body>
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h1><i class="ph-bold ph-calendar-check"></i> Booking Management</h1>
            <a href="{{ url('/admin/bookings') }}" class="btn-refresh"><i class="ph ph-arrows-clockwise"></i> Refresh Data</a>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Room</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Booked At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                <tr>
                    <td>#{{ $booking->id }}</td>
                    <td>
                        <div style="font-weight: 600;">{{ $booking->name }}</div>
                        <div style="font-size: 0.75rem; color: #64748b;">{{ $booking->email }}</div>
                    </td>
                    <td>{{ $booking->room_name }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</td>
                    <td>₹{{ number_format($booking->total_price, 2) }}</td>
                    <td>
                        <span class="status status-{{ strtolower($booking->payment_status) }}">
                            {{ $booking->payment_status }}
                        </span>
                    </td>
                    <td style="font-size: 0.75rem; color: #64748b;">{{ $booking->created_at->diffForHumans() }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 3rem; color: #94a3b8;">No bookings found in the database.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div style="margin-top: 2rem;">
            <a href="{{ route('home') }}" style="color: #64748b; text-decoration: none;"><i class="ph ph-arrow-left"></i> Back to Website</a>
        </div>
    </div>
</body>
</html>