<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed - MCC IGH</title>
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
        .success-checkmark {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background-color: #dcfce7;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .checkmark-icon {
            color: #166534;
            font-size: 40px;
            font-weight: bold;
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
            font-weight: 500;
        }
        .btn {
            display: inline-block;
            padding: 14px 40px;
            background-color: {{ $primaryColor }};
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .footer {
            background-color: #f9f9f9;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Reservation Confirmed!</h1>
        </div>
        <div class="content" style="text-align: center;">
            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="80" height="80" style="margin: 0 auto 20px; background-color: #dcfce7; border-radius: 40px;">
                <tr>
                    <td align="center" valign="middle" style="color: #166534; font-size: 40px; font-weight: bold; line-height: 80px;">
                        ✓
                    </td>
                </tr>
            </table>
            
            <h2 style="color: #1e293b; margin-top: 0;">Hello {{ ucwords(strtolower($booking->name)) }},</h2>
            <p style="color: #64748b; line-height: 1.6; font-size: 16px;">Great news! Your booking request for <strong>{{ str_replace('-', ' ', ucwords($booking->room_name, '- ')) }}</strong> has been officially approved and confirmed.</p>
            <p style="color: #64748b; font-size: 14px; margin-top: -10px;">(Please find your official receipt attached to this email as a PDF)</p>

            <div style="background-color: {{ $primaryColor }}10; border-left: 4px solid {{ $primaryColor }}; padding: 20px; margin: 30px 0; text-align: left; border-radius: 8px;">
                <p style="margin: 0; color: {{ $primaryColor }}; font-weight: 700; font-size: 15px;">Next Steps:</p>
                <p style="margin: 8px 0 0; color: #475569; font-size: 14px; line-height: 1.5;">Since this is a <strong>Direct Pay</strong> system, you can come directly to the guest house. Please settle the payment at the reception counter upon arrival.</p>
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
                    <td>{{ \Carbon\Carbon::parse($booking->booking_date . ' ' . $booking->start_time)->format('M d, Y, h:i A') }}</td>
                </tr>
                <tr>
                    <th>Check-Out</th>
                    <td>{{ \Carbon\Carbon::parse($booking->booking_date . ' ' . $booking->end_time)->format('M d, Y, h:i A') }}</td>
                </tr>
                <tr>
                    <th>Pay at Counter</th>
                    <td style="color: {{ $primaryColor }}; font-weight: 700;">Rs. {{ number_format($booking->total_price, 2) }}</td>
                </tr>
            </table>
            
            <a href="{{ route('checkout.success', $booking->id) }}?download=1" class="btn" style="color: #ffffff !important; display: inline-block;">
                View & Download Receipt Online
            </a>

            <p style="margin-top: 30px; font-size: 14px; color: #94a3b8;">
                We look forward to seeing you at MCC IGH. If you have any questions, feel free to visit us or contact support.
            </p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} MCC IGH. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
