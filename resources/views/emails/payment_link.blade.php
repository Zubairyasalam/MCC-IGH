<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Payment</title>
    <style>
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; background-color: #f8fafc; margin: 0; padding: 0; color: #1e293b; }
        .wrapper { width: 100%; table-layout: fixed; background-color: #f8fafc; padding-bottom: 40px; }
        .main { background-color: #ffffff; margin: 40px auto; width: 100%; max-width: 600px; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1); border: 1px solid #e2e8f0; }
        .header { background: {{ $primaryColor }}; padding: 40px 20px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; font-weight: 700; letter-spacing: -0.025em; }
        .content { padding: 40px 30px; line-height: 1.6; }
        .greeting { font-size: 18px; font-weight: 600; margin-bottom: 16px; }
        .details { background-color: #f1f5f9; border-radius: 12px; padding: 24px; margin: 24px 0; border: 1px solid #e2e8f0; }
        .detail-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; }
        .detail-label { color: #64748b; font-weight: 500; }
        .detail-value { color: #1e293b; font-weight: 600; }
        .total-row { border-top: 1px dashed #cbd5e1; margin-top: 16px; padding-top: 16px; display: flex; justify-content: space-between; font-size: 18px; font-weight: 700; color: {{ $primaryColor }}; }
        .cta-container { text-align: center; margin: 40px 0 20px; }
        .btn { background-color: {{ $primaryColor }}; color: #ffffff !important; text-decoration: none; padding: 16px 32px; border-radius: 12px; font-weight: 700; font-size: 16px; display: inline-block; transition: all 0.2s; box-shadow: 0 10px 15px -3px rgba(133, 15, 15, 0.3); }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #64748b; border-top: 1px solid #f1f5f9; }
        .expiry-note { font-size: 13px; color: #ef4444; margin-top: 24px; text-align: center; font-weight: 500; }
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
                        <tr>
                            <td colspan="2" style="border-top: 1px dashed #cbd5e1; padding-top: 16px;">
                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td style="font-size: 18px; font-weight: 700; color: {{ $primaryColor }};">Total Amount:</td>
                                        <td style="font-size: 18px; font-weight: 700; color: {{ $primaryColor }}; text-align: right;">₹{{ number_format($booking->total_price, 2) }}</td>
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
                    <i class="ph-bold ph-clock"></i> 
                    This payment link is valid for 24 hours only.
                </p>

                <p style="margin-top: 40px; font-size: 14px; color: #64748b;">
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
