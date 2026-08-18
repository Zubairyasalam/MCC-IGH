<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $actionTitle ?? 'Booking Review' }} - MCC IGH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    @include('partials.dynamic-styles')
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            padding: 2rem 1rem;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }
        .card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            max-width: 520px;
            width: 100%;
            padding: 2rem;
            border: 1px solid #e2e8f0;
            margin-top: 1.5rem;
        }
        .card-title {
            font-size: 1.65rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 1.25rem;
        }
        .banner-warning {
            background-color: #fef9c3;
            border: 1px solid #fef08a;
            color: #854d0e;
            padding: 0.85rem 1.15rem;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
            line-height: 1.4;
        }
        .banner-success {
            background-color: #dcfce7;
            border: 1px solid #bbf7d0;
            color: #166534;
            padding: 0.85rem 1.15rem;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
            line-height: 1.4;
        }
        .banner-error {
            background-color: #fee2e2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 0.85rem 1.15rem;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
            line-height: 1.4;
        }
        .detail-group {
            margin-bottom: 1.1rem;
        }
        .detail-label {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }
        .detail-value {
            font-size: 0.95rem;
            color: #334155;
            line-height: 1.5;
        }
        .detail-value a {
            color: #1e293b;
            text-decoration: underline;
        }
        .reason-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 0.9rem;
            color: #475569;
            margin-top: 0.25rem;
        }
        .form-group {
            margin-top: 1.5rem;
        }
        .form-label {
            display: block;
            font-size: 0.9rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }
        .form-textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.95rem;
            min-height: 90px;
            resize: vertical;
        }
        .form-textarea:focus {
            outline: none;
            border-color: #0284c7;
            box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
        }
        .btn-submit {
            display: block;
            width: 100%;
            padding: 0.85rem;
            background-color: #dc2626;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 1rem;
            transition: background-color 0.15s;
        }
        .btn-submit:hover {
            background-color: #b91c1c;
        }
        .footer-link {
            margin-top: 2rem;
            text-align: center;
        }
        .footer-link a {
            color: #64748b;
            font-size: 0.875rem;
            text-decoration: none;
        }
        .footer-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1 class="card-title">{{ $actionTitle ?? 'Booking Review' }}</h1>

        @if(!empty($alreadyReviewed))
            <div class="banner-warning">
                This booking has already been reviewed: {{ $statusDisplay ?? 'Processed' }}.
            </div>
        @elseif(!empty($success) || session('success'))
            <div class="banner-success">
                {{ $success ?? session('success') }}
            </div>
        @elseif(!empty($error) || session('error'))
            <div class="banner-error">
                {{ $error ?? session('error') }}
            </div>
        @elseif(session('info'))
            <div class="banner-warning">
                {{ session('info') }}
            </div>
        @endif

        @if(!empty($booking))
            <div class="detail-group">
                <div class="detail-label">Booking Number</div>
                <div class="detail-value">#{{ $booking->reference_id ?? $booking->id }}</div>
            </div>

            <div class="detail-group">
                <div class="detail-label">Space</div>
                <div class="detail-value">{{ $booking->room_name }}</div>
            </div>

            <div class="detail-group">
                <div class="detail-label">Customer</div>
                <div class="detail-value">{{ $booking->name }} (<a href="mailto:{{ $booking->email }}">{{ $booking->email }}</a>)</div>
            </div>

            <div class="detail-group">
                <div class="detail-label">Purpose</div>
                <div class="detail-value">{{ $booking->booking_reason ?: 'Guest Accommodation' }}</div>
            </div>

            <div class="detail-group">
                <div class="detail-label">Total Amount</div>
                <div class="detail-value">₹{{ number_format((float)($booking->total_price ?? 0)) }}</div>
            </div>

            @if(!empty($booking->rejection_reason))
                <div class="detail-group">
                    <div class="detail-label">Rejection Reason</div>
                    <div class="reason-box">{{ $booking->rejection_reason }}</div>
                </div>
            @endif

            @if(!empty($booking->approval_remarks) || !empty($booking->principal_remarks))
                <div class="detail-group">
                    <div class="detail-label">Principal Approval Remarks</div>
                    <div class="reason-box" style="background: #f0fdf4; border-color: #bbf7d0; color: #166534;">
                        {{ $booking->approval_remarks ?? $booking->principal_remarks }}
                    </div>
                </div>
            @endif

            @if(empty($alreadyReviewed) && !empty($showApproveForm))
                <form action="{{ route('admin.bookings.approve.get', $booking->id) }}" method="POST" class="form-group">
                    @csrf
                    <label class="form-label">Approval Remarks / Notes <span style="color: #64748b; font-weight: 400; font-size: 0.8rem;">(Optional)</span></label>
                    <textarea name="approval_remarks" class="form-textarea" placeholder="Add any special instructions or remarks for this booking (optional)..."></textarea>
                    <button type="submit" class="btn-submit" style="background-color: #166534;">Confirm & Approve Booking</button>
                </form>
            @endif

            @if(empty($alreadyReviewed) && !empty($showRejectForm))
                <form action="{{ route('admin.bookings.reject', $booking->id) }}" method="POST" class="form-group">
                    @csrf
                    <label class="form-label">Reason for Rejection <span style="color: #dc2626;">*</span></label>
                    <textarea name="rejection_reason" class="form-textarea" placeholder="Please enter the reason for rejecting this booking..." required></textarea>
                    <button type="submit" class="btn-submit">Reject Booking</button>
                </form>
            @endif
        @else
            <div class="detail-value">{{ session('info') ?? session('success') ?? session('error') ?? 'Booking processed.' }}</div>
        @endif

        <div class="footer-link">
            <a href="{{ route('home') }}">Return to Homepage</a>
        </div>
    </div>
</body>
</html>
