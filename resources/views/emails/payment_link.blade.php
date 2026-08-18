<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light dark">
    <meta name="supported-color-schemes" content="light dark">
    <title>Complete Your Payment</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f8fafc; margin: 0; padding: 0; color: #1e293b; width: 100% !important; -webkit-text-size-adjust: 100%; }
        .wrapper { width: 100%; background-color: #f8fafc; padding-bottom: 30px; }
        .main { background-color: #ffffff; margin: 20px auto; width: 100%; max-width: 600px; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); border: 1px solid #e2e8f0; }
        .header { background: {{ $primaryColor }}; padding: 30px 20px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 22px; font-weight: 700; }
        .content { padding: 30px 24px; line-height: 1.6; }
        .greeting { font-size: 17px; font-weight: 700; margin-bottom: 12px; color: #0f172a; }
        .details { background-color: #f1f5f9; border-radius: 10px; padding: 20px; margin: 20px 0; border: 1px solid #e2e8f0; }
        .detail-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; }
        .detail-label { color: #64748b; font-weight: 500; }
        .detail-value { color: #1e293b; font-weight: 600; }
        .cta-container { text-align: center; margin: 30px 0 20px; }
        .btn { background-color: {{ $primaryColor }}; color: #ffffff !important; text-decoration: none; padding: 14px 28px; border-radius: 10px; font-weight: 700; font-size: 15px; display: inline-block; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15); box-sizing: border-box; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #64748b; border-top: 1px solid #f1f5f9; background-color: #f8fafc; }
        .expiry-note { font-size: 13px; color: #dc2626; margin-top: 20px; text-align: center; font-weight: 600; }

        @media only screen and (max-width: 600px) {
            .main {
                margin: 0 auto !important;
                border-radius: 0 !important;
                border: none !important;
            }
            .content {
                padding: 20px 16px !important;
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
    <div class="wrapper">
        <div class="main">
            <div class="header">
                <h1>Reservation Approved</h1>
            </div>
            <div class="content">
                <p class="greeting">Hello {{ $booking->name }},</p>
                <p>Great news! Your reservation for <strong>{{ $booking->room_name }}</strong> has been approved by our administrator. To secure your booking, please complete the payment using the link below.</p>
                
                <div class="details">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td class="detail-label" style="padding-bottom: 8px;">Booking ID:</td>
                            <td class="detail-value" style="padding-bottom: 8px; text-align: right;">MCC/IGH/{{ date('Y') }}/{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</td>
                        </tr>
                        <tr>
                            <td class="detail-label" style="padding-bottom: 8px;">Date:</td>
                            <td class="detail-value" style="padding-bottom: 8px; text-align: right;">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M, Y') }}</td>
                        </tr>
                        <tr>
                            <td class="detail-label" style="padding-bottom: 8px;">Time:</td>
                            <td class="detail-value" style="padding-bottom: 8px; text-align: right;">{{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}</td>
                        </tr>
                        @if(($booking->discount_amount ?? 0) > 0)
                        <tr>
                            <td class="detail-label" style="padding-bottom: 8px;">Standard Price:</td>
                            <td class="detail-value" style="padding-bottom: 8px; text-align: right; text-decoration: line-through; color: #94a3b8;">₹{{ number_format($booking->original_price, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="detail-label" style="padding-bottom: 8px; color: #166534; font-weight: 700;">Discount / Special Offer:</td>
                            <td class="detail-value" style="padding-bottom: 8px; text-align: right; color: #166534; font-weight: 700;">- ₹{{ number_format($booking->discount_amount, 2) }} ({{ $booking->discount_reason ?: 'Special Offer' }})</td>
                        </tr>
                        @endif
                        <tr>
                            <td colspan="2" style="border-top: 1px dashed #cbd5e1; padding-top: 12px;">
                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td style="font-size: 16px; font-weight: 700; color: {{ $primaryColor }};">Final Payable Amount:</td>
                                        <td style="font-size: 16px; font-weight: 700; color: {{ $primaryColor }}; text-align: right;">₹{{ number_format($booking->total_price, 2) }}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="cta-container">
                    <a href="{{ $paymentUrl }}" class="btn">Pay Now & Secure Booking</a>
                </div>

                <p class="expiry-note">
                    This payment link is valid for 24 hours only.
                </p>

                <p style="margin-top: 30px; font-size: 13px; color: #64748b; text-align: center;">
                    If you have any questions, please contact our support team. We look forward to hosting you!
                </p>
            </div>
            <div class="footer">
                &copy; {{ date('Y') }} MCC International Guest House. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
