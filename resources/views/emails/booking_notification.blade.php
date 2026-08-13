<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light dark">
    <meta name="supported-color-schemes" content="light dark">
    <title>New Booking Request</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f4f4f7;
            margin: 0;
            padding: 0;
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            color: #333333;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
        }
        .header {
            background: {{ $primaryColor }};
            background: linear-gradient(135deg, {{ $primaryColor }} 0%, {{ $primaryColor }} 100%);
            padding: 28px 20px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #ffffff;
        }
        .header p {
            margin: 6px 0 0;
            font-size: 13px;
            color: rgba(255,255,255,0.9);
        }
        .content {
            padding: 28px 24px;
        }
        .booking-details {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        .booking-details th {
            text-align: left;
            padding: 10px 12px;
            border-bottom: 1px solid #e2e8f0;
            color: #64748b;
            width: 38%;
            font-weight: 600;
            font-size: 14px;
        }
        .booking-details td {
            text-align: left;
            padding: 10px 12px;
            border-bottom: 1px solid #e2e8f0;
            color: #1e293b;
            font-size: 14px;
            font-weight: 600;
            word-break: break-word;
        }
        .actions-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
            text-align: center;
            box-sizing: border-box;
            line-height: 1.2;
        }
        .btn-approve {
            background-color: #16a34a;
            color: #ffffff !important;
        }
        .btn-reject {
            background-color: #dc2626;
            color: #ffffff !important;
        }
        .footer {
            background-color: #f8fafc;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
        }
        .badge {
            background-color: #fef3c7;
            color: #92400e;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 700;
        }

        /* Mobile responsive adjustments */
        @media only screen and (max-width: 600px) {
            .container {
                margin: 0 auto !important;
                width: 100% !important;
                max-width: 100% !important;
                border-radius: 0 !important;
                border: none !important;
            }
            .header {
                padding: 20px 16px !important;
            }
            .header h1 {
                font-size: 19px !important;
            }
            .content {
                padding: 20px 16px !important;
            }
            .booking-details, 
            .booking-details tbody, 
            .booking-details tr, 
            .booking-details th, 
            .booking-details td {
                display: block !important;
                width: 100% !important;
                box-sizing: border-box !important;
            }
            .booking-details tr {
                margin-bottom: 8px !important;
            }
            .booking-details th {
                padding: 8px 0 2px 0 !important;
                border-bottom: none !important;
                font-size: 11px !important;
                text-transform: uppercase !important;
                letter-spacing: 0.5px !important;
                color: #64748b !important;
            }
            .booking-details td {
                padding: 2px 0 10px 0 !important;
                border-bottom: 1px solid #f1f5f9 !important;
                font-size: 15px !important;
                font-weight: 700 !important;
                color: #0f172a !important;
            }
            .actions-table, 
            .actions-table tbody, 
            .actions-table tr, 
            .actions-table td {
                display: block !important;
                width: 100% !important;
                box-sizing: border-box !important;
            }
            .actions-table td {
                padding: 0 !important;
                text-align: center !important;
            }
            .btn {
                display: block !important;
                width: 100% !important;
                box-sizing: border-box !important;
                margin-bottom: 10px !important;
                padding: 14px 16px !important;
                font-size: 15px !important;
            }
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
            <h3 style="margin-top: 0; margin-bottom: 16px; color: #0f172a; font-size: 17px; font-weight: 700;">Booking Details</h3>
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
                @if($booking->hall_name)
                <tr>
                    <th>Residence Hall</th>
                    <td><strong style="color: #850f0f;">{{ $booking->hall_name }}</strong></td>
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
                    <td>{{ $booking->clock_in ? $booking->clock_in->format('d M Y, h:i A') : \Carbon\Carbon::parse($booking->booking_date . ' ' . $booking->start_time)->format('d M Y, h:i A') }}</td>
                </tr>
                <tr>
                    <th>Check-Out</th>
                    <td>{{ $booking->clock_out ? $booking->clock_out->format('d M Y, h:i A') : \Carbon\Carbon::parse($booking->booking_date . ' ' . $booking->end_time)->format('d M Y, h:i A') }}</td>
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
                    <td style="color: {{ $primaryColor }};">₹{{ number_format($booking->total_price, 2) }}</td>
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

            <p style="text-align: center; color: #64748b; font-size: 14px; margin-bottom: 20px;">
                Please review the booking details above and take an action.
            </p>

            <table class="actions-table">
                <tr>
                    <td align="center">
                        <a href="{{ $approveUrl }}" class="btn btn-approve">APPROVE</a>
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <a href="{{ $rejectUrl }}" class="btn btn-reject">REJECT</a>
                    </td>
                </tr>
            </table>

        </div>
        <div class="footer">
            <p style="margin: 0 0 4px 0;">&copy; {{ date('Y') }} MCC IGH. All rights reserved.</p>
            <p style="margin: 0;">This is an automated notification. Please do not reply directly to this email.</p>
        </div>
    </div>
</body>
</html>
