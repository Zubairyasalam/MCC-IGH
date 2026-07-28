<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Booking Request</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .header {
            background: {{ $primaryColor }};
            background: linear-gradient(135deg, {{ $primaryColor }} 0%, {{ $primaryColor }} 100%);
            padding: 30px;
            text-align: center;
            color: white;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            letter-spacing: 1px;
        }
        .content {
            padding: 30px;
        }
        .booking-details {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .booking-details th {
            text-align: left;
            padding: 12px 10px;
            border-bottom: 1px solid #eee;
            color: #666;
            width: 40%;
            font-weight: 600;
            font-size: 15px;
        }
        .booking-details td {
            text-align: left;
            padding: 12px 10px;
            border-bottom: 1px solid #eee;
            color: #333;
            font-size: 15px;
            font-weight: 500;
        }
        .actions {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: transform 0.2s, background-color 0.2s;
            text-align: center;
            min-width: 120px;
        }
        .btn-approve {
            background-color: #28a745;
            color: #ffffff !important;
        }
        .btn-approve:hover {
            background-color: #218838;
        }
        .btn-reject {
            background-color: #dc3545;
            color: #ffffff !important;
        }
        .btn-reject:hover {
            background-color: #c82333;
        }
        .footer {
            background-color: #f9f9f9;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #eee;
        }
        .badge {
            background-color: #fff3cd;
            color: #856404;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>New Booking Request</h1>
            <p>A new reservation has been submitted for approval</p>
        </div>
        <div class="content">
            <h3>Booking Details</h3>
            <table class="booking-details">
                <tr>
                    <th>Guest Name</th>
                    <td>{{ ucwords(strtolower($booking->name)) }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $booking->email }}</td>
                </tr>
                <tr>
                    <th>Nationality</th>
                    <td>{{ $booking->nationality ?: 'Indian' }}</td>
                </tr>
                @if($booking->nationality === 'Non-Indian')
                <tr>
                    <th>Passport Number</th>
                    <td>{{ $booking->passport_number }}</td>
                </tr>
                <tr>
                    <th>Visa Number</th>
                    <td>{{ $booking->visa_number }}</td>
                </tr>
                @endif
                <tr>
                    <th>Phone</th>
                    <td>{{ $booking->phone }}</td>
                </tr>
                <tr>
                    <th>User Type</th>
                    <td>{{ $booking->user_type }}</td>
                </tr>
                @if($booking->user_type === 'Student')
                <tr>
                    <th>Academic Details</th>
                    <td>{{ $booking->level }} | {{ $booking->stream }} | {{ $booking->department }}</td>
                </tr>
                @if($booking->residence_status)
                <tr>
                    <th>Residence Status</th>
                    <td>{{ ucwords($booking->residence_status) }}</td>
                </tr>
                @endif
                @elseif($booking->user_type === 'Staff')
                <tr>
                    <th>Staff Details</th>
                    <td>{{ $booking->level }} ({{ $booking->department }})</td>
                </tr>
                @else
                <tr>
                    <th>Department/Unit</th>
                    <td>{{ $booking->department ?: 'N/A' }}</td>
                </tr>
                @endif
                <tr>
                    <th>Workspace</th>
                    <td>{{ ucwords(str_replace('-', ' ', $booking->room_name)) }}</td>
                </tr>
                <tr>
                    <th>Check-In</th>
                    <td>{{ \Carbon\Carbon::parse($booking->booking_date . ' ' . $booking->start_time)->format('d M Y, h:i A') }}</td>
                </tr>
                <tr>
                    <th>Check-Out</th>
                    <td>{{ \Carbon\Carbon::parse($booking->booking_date . ' ' . $booking->end_time)->format('d M Y, h:i A') }}</td>
                </tr>
                @if($booking->booking_reason)
                <tr>
                    <th>Purpose</th>
                    <td>{{ $booking->booking_reason }}</td>
                </tr>
                @endif
                <tr>
                    <th>Persons</th>
                    <td>{{ $booking->no_of_persons }}</td>
                </tr>
                <tr>
                    <th>Amount</th>
                    <td>₹{{ number_format($booking->total_price, 2) }}</td>
                </tr>
                @if($booking->referral_attachment)
                <tr>
                    <th>Referral</th>
                    <td><span class="badge">Attached</span></td>
                </tr>
                @endif
                @if($booking->passport_attachment)
                <tr>
                    <th>Passport Copy</th>
                    <td><span class="badge">Attached</span></td>
                </tr>
                @endif
                @if($booking->visa_attachment)
                <tr>
                    <th>Visa Copy</th>
                    <td><span class="badge">Attached</span></td>
                </tr>
                @endif
                @if($booking->passport_visa_attachment && !$booking->passport_attachment && !$booking->visa_attachment)
                <tr>
                    <th>Passport & Visa Doc</th>
                    <td><span class="badge">Attached</span></td>
                </tr>
                @endif
            </table>

            <p style="text-align: center; color: #666; font-size: 14px; margin-bottom: 20px;">
                Please review the booking details above and take an action.
            </p>

            <div class="actions">
                <table width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="center">
                            <a href="{{ $approveUrl }}" class="btn btn-approve" style="color: #ffffff !important;">APPROVE</a>
                            &nbsp;&nbsp;
                            <a href="{{ $rejectUrl }}" class="btn btn-reject" style="color: #ffffff !important;">REJECT</a>
                        </td>
                    </tr>
                </table>
            </div>
            

        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} MCC IGH. All rights reserved.</p>
            <p>This is an automated notification. Please do not reply directly to this email.</p>
        </div>
    </div>
</body>
</html>
