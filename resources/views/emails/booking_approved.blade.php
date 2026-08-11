<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light dark">
    <meta name="supported-color-schemes" content="light dark">
    <title>Booking Confirmed - MCC IGH</title>
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
            color: #ffffff;
        }
        .content {
            padding: 28px 24px;
            text-align: center;
        }
        .booking-details {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: #f8fafc;
            border-radius: 8px;
            overflow: hidden;
        }
        .booking-details th {
            text-align: left;
            padding: 12px 15px;
            color: #64748b;
            font-size: 13px;
            text-transform: uppercase;
            width: 40%;
        }
        .booking-details td {
            text-align: left;
            padding: 12px 15px;
            color: #1e293b;
            font-weight: 600;
            word-break: break-word;
        }
        .btn {
            display: inline-block;
            padding: 14px 32px;
            background-color: {{ $primaryColor }};
            color: #ffffff !important;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 700;
            font-size: 15px;
            margin-top: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
        }
        .footer {
            background-color: #f8fafc;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
        }

        @media only screen and (max-width: 600px) {
            .container {
                margin: 0 auto !important;
                width: 100% !important;
                max-width: 100% !important;
                border-radius: 0 !important;
                border: none !important;
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
            .booking-details th {
                padding: 10px 12px 2px 12px !important;
                font-size: 11px !important;
                border-bottom: none !important;
            }
            .booking-details td {
                padding: 2px 12px 10px 12px !important;
                border-bottom: 1px solid #e2e8f0 !important;
                font-size: 15px !important;
            }
            .btn {
                display: block !important;
                width: 100% !important;
                padding: 14px 16px !important;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Reservation Confirmed!</h1>
        </div>
        <div class="content">
            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="70" height="70" style="margin: 0 auto 16px; background-color: #dcfce7; border-radius: 35px;">
                <tr>
                    <td align="center" valign="middle" style="color: #166534; font-size: 36px; font-weight: bold; line-height: 70px;">
                        ✓
                    </td>
                </tr>
            </table>
            
            <h2 style="color: #1e293b; margin-top: 0; font-size: 20px;">Hello {{ ucwords(strtolower($booking->name)) }},</h2>
            <p style="color: #64748b; line-height: 1.6; font-size: 15px;">Great news! Your booking request for <strong>{{ str_replace('-', ' ', ucwords($booking->room_name, '- ')) }}</strong> has been officially approved and confirmed.</p>
            <p style="color: #64748b; font-size: 13px; margin-top: -6px;">(Please find your official receipt attached to this email as a PDF)</p>

            <div style="background-color: {{ $primaryColor }}10; border-left: 4px solid {{ $primaryColor }}; padding: 16px; margin: 24px 0; text-align: left; border-radius: 8px;">
                <p style="margin: 0; color: {{ $primaryColor }}; font-weight: 700; font-size: 14px;">Next Steps:</p>
                <p style="margin: 6px 0 0; color: #475569; font-size: 13px; line-height: 1.5;">Since this is a <strong>Direct Pay</strong> system, you can come directly to the guest house. Please settle the payment at the reception counter upon arrival.</p>
            </div>
            
            <table class="booking-details">
                <tr>
                    <th>Booking ID</th>
                    <td>BK-{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</td>
                </tr>
                <tr>
                    <th>Workspace</th>
                    <td>{{ str_replace('-', ' ', ucwords($booking->room_name, '- ')) }}</td>
                </tr>
                <tr>
                    <th>Check-In</th>
                    <td>{{ $booking->clock_in ? $booking->clock_in->format('M d, Y, h:i A') : \Carbon\Carbon::parse($booking->booking_date . ' ' . $booking->start_time)->format('M d, Y, h:i A') }}</td>
                </tr>
                <tr>
                    <th>Check-Out</th>
                    <td>{{ $booking->clock_out ? $booking->clock_out->format('M d, Y, h:i A') : \Carbon\Carbon::parse($booking->booking_date . ' ' . $booking->end_time)->format('M d, Y, h:i A') }}</td>
                </tr>
                <tr>
                    <th>Pay at Counter</th>
                    <td style="color: {{ $primaryColor }}; font-weight: 700;">Rs. {{ number_format($booking->total_price, 2) }}</td>
                </tr>
            </table>
            
            <a href="{{ route('checkout.success', $booking->id) }}?download=1" class="btn">
                View & Download Receipt Online
            </a>

            <p style="margin-top: 24px; font-size: 13px; color: #94a3b8;">
                We look forward to seeing you at MCC IGH. If you have any questions, feel free to visit us or contact support.
            </p>
        </div>
        <div class="footer">
            <p style="margin: 0;">&copy; {{ date('Y') }} MCC IGH. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
