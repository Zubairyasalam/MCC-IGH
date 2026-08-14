<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light dark">
    <meta name="supported-color-schemes" content="light dark">
    <title>New Booking Request</title>
    <style type="text/css">
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; background-color: #f4f4f7; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; }
        
        /* Mobile responsive overrides */
        @media only screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 auto !important;
                border-radius: 0 !important;
                border: none !important;
            }
            .header-banner {
                padding: 24px 16px !important;
            }
            .header-title {
                font-size: 20px !important;
            }
            .body-content {
                padding: 20px 16px !important;
            }
            .detail-row-th,
            .detail-row-td {
                display: block !important;
                width: 100% !important;
                box-sizing: border-box !important;
                text-align: left !important;
            }
            .detail-row-th {
                padding: 8px 0 2px 0 !important;
                border-bottom: none !important;
                font-size: 11px !important;
                text-transform: uppercase !important;
                letter-spacing: 0.5px !important;
                color: #64748b !important;
            }
            .detail-row-td {
                padding: 2px 0 10px 0 !important;
                border-bottom: 1px solid #f1f5f9 !important;
                font-size: 15px !important;
                font-weight: 700 !important;
                color: #0f172a !important;
            }
            .action-btn-link {
                padding: 14px 16px !important;
                font-size: 15px !important;
            }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f7; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <!-- Outer Wrapper -->
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f4f4f7; padding: 20px 0;">
        <tr>
            <td align="center" style="padding: 0 10px;">
                <!-- Main Email Card -->
                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="email-container" style="max-width: 600px; background-color: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.06);">
                    <!-- Header Banner -->
                    <tr>
                        <td class="header-banner" align="center" style="background: {{ $primaryColor }}; padding: 30px 24px; text-align: center; color: #ffffff;">
                            <div style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: rgba(255,255,255,0.85); margin-bottom: 6px;">APPROVAL ACTION REQUIRED</div>
                            <h1 class="header-title" style="margin: 0; font-size: 22px; font-weight: 800; color: #ffffff; letter-spacing: 0.3px;">New Booking Request</h1>
                            <p style="margin: 6px 0 0 0; font-size: 13px; color: rgba(255,255,255,0.9);">Ref #{{ $booking->reference_id ?? $booking->id }} • {{ ucwords(str_replace('-', ' ', $booking->room_name)) }}</p>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td class="body-content" style="padding: 28px 24px;">
                            <h3 style="margin: 0 0 16px 0; color: #0f172a; font-size: 16px; font-weight: 700; border-bottom: 2px solid #f1f5f9; padding-bottom: 8px;">
                                Reservation Summary
                            </h3>

                            <!-- Details Table -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; margin-bottom: 24px;">
                                <tr>
                                    <th class="detail-row-th" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #64748b; width: 38%; font-weight: 600; font-size: 13px;">Guest Name</th>
                                    <td class="detail-row-td" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #1e293b; font-size: 14px; font-weight: 700;">{{ ucwords(strtolower($booking->name)) }}</td>
                                </tr>
                                <tr>
                                    <th class="detail-row-th" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-weight: 600; font-size: 13px;">Email</th>
                                    <td class="detail-row-td" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #1e293b; font-size: 14px; font-weight: 600; word-break: break-all;">{{ $booking->email }}</td>
                                </tr>
                                <tr>
                                    <th class="detail-row-th" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-weight: 600; font-size: 13px;">Phone</th>
                                    <td class="detail-row-td" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #1e293b; font-size: 14px; font-weight: 600;">{{ $booking->phone }}</td>
                                </tr>
                                <tr>
                                    <th class="detail-row-th" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-weight: 600; font-size: 13px;">Nationality</th>
                                    <td class="detail-row-td" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #1e293b; font-size: 14px; font-weight: 600;">{{ $booking->nationality ?: 'Indian' }}</td>
                                </tr>
                                @if($booking->nationality === 'Non-Indian')
                                <tr>
                                    <th class="detail-row-th" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-weight: 600; font-size: 13px;">Passport Number</th>
                                    <td class="detail-row-td" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #1e293b; font-size: 14px; font-weight: 600;">{{ $booking->passport_number }}</td>
                                </tr>
                                <tr>
                                    <th class="detail-row-th" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-weight: 600; font-size: 13px;">Visa Number</th>
                                    <td class="detail-row-td" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #1e293b; font-size: 14px; font-weight: 600;">{{ $booking->visa_number }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th class="detail-row-th" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-weight: 600; font-size: 13px;">User Category</th>
                                    <td class="detail-row-td" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #1e293b; font-size: 14px; font-weight: 700;">{{ $booking->user_type }}</td>
                                </tr>
                                @if($booking->user_type === 'Student')
                                <tr>
                                    <th class="detail-row-th" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-weight: 600; font-size: 13px;">Academic Details</th>
                                    <td class="detail-row-td" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #1e293b; font-size: 14px; font-weight: 600;">{{ $booking->level }} | {{ $booking->stream }} | {{ $booking->department }}</td>
                                </tr>
                                @if($booking->residence_status)
                                <tr>
                                    <th class="detail-row-th" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-weight: 600; font-size: 13px;">Residence Status</th>
                                    <td class="detail-row-td" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #1e293b; font-size: 14px; font-weight: 600;">{{ ucwords($booking->residence_status) }}</td>
                                </tr>
                                @endif
                                @if($booking->hall_name)
                                <tr>
                                    <th class="detail-row-th" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-weight: 600; font-size: 13px;">Residence Hall</th>
                                    <td class="detail-row-td" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #850f0f; font-size: 14px; font-weight: 700;">{{ $booking->hall_name }}</td>
                                </tr>
                                @endif
                                @elseif($booking->user_type === 'Staff')
                                <tr>
                                    <th class="detail-row-th" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-weight: 600; font-size: 13px;">Staff Details</th>
                                    <td class="detail-row-td" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #1e293b; font-size: 14px; font-weight: 600;">{{ $booking->level }} ({{ $booking->department }})</td>
                                </tr>
                                @else
                                <tr>
                                    <th class="detail-row-th" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-weight: 600; font-size: 13px;">Department/Unit</th>
                                    <td class="detail-row-td" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #1e293b; font-size: 14px; font-weight: 600;">{{ $booking->department ?: 'N/A' }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th class="detail-row-th" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-weight: 600; font-size: 13px;">Workspace Name</th>
                                    <td class="detail-row-td" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #1e293b; font-size: 14px; font-weight: 700;">{{ ucwords(str_replace('-', ' ', $booking->room_name)) }}</td>
                                </tr>
                                <tr>
                                    <th class="detail-row-th" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-weight: 600; font-size: 13px;">Check-In</th>
                                    <td class="detail-row-td" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #1e293b; font-size: 14px; font-weight: 600;">{{ $booking->clock_in ? $booking->clock_in->format('d M Y, h:i A') : \Carbon\Carbon::parse($booking->booking_date . ' ' . $booking->start_time)->format('d M Y, h:i A') }}</td>
                                </tr>
                                <tr>
                                    <th class="detail-row-th" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-weight: 600; font-size: 13px;">Check-Out</th>
                                    <td class="detail-row-td" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #1e293b; font-size: 14px; font-weight: 600;">{{ $booking->clock_out ? $booking->clock_out->format('d M Y, h:i A') : \Carbon\Carbon::parse($booking->booking_date . ' ' . $booking->end_time)->format('d M Y, h:i A') }}</td>
                                </tr>
                                @if($booking->booking_reason)
                                <tr>
                                    <th class="detail-row-th" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-weight: 600; font-size: 13px;">Purpose</th>
                                    <td class="detail-row-td" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #1e293b; font-size: 14px; font-weight: 600;">{{ $booking->booking_reason }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th class="detail-row-th" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-weight: 600; font-size: 13px;">No. of Guests</th>
                                    <td class="detail-row-td" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #1e293b; font-size: 14px; font-weight: 600;">{{ $booking->no_of_persons }}</td>
                                </tr>
                                <tr>
                                    <th class="detail-row-th" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-weight: 600; font-size: 13px;">Total Amount</th>
                                    <td class="detail-row-td" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: {{ $primaryColor }}; font-size: 16px; font-weight: 800;">₹{{ number_format($booking->total_price, 2) }}</td>
                                </tr>
                                @if($booking->referral_attachment)
                                <tr>
                                    <th class="detail-row-th" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-weight: 600; font-size: 13px;">Referral Letter</th>
                                    <td class="detail-row-td" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #1e293b;"><span style="background-color: #fef3c7; color: #92400e; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 700; display: inline-block;">Attached</span></td>
                                </tr>
                                @endif
                                @if($booking->passport_attachment)
                                <tr>
                                    <th class="detail-row-th" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-weight: 600; font-size: 13px;">Passport Copy</th>
                                    <td class="detail-row-td" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #1e293b;"><span style="background-color: #fef3c7; color: #92400e; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 700; display: inline-block;">Attached</span></td>
                                </tr>
                                @endif
                                @if($booking->visa_attachment)
                                <tr>
                                    <th class="detail-row-th" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-weight: 600; font-size: 13px;">Visa Copy</th>
                                    <td class="detail-row-td" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #1e293b;"><span style="background-color: #fef3c7; color: #92400e; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 700; display: inline-block;">Attached</span></td>
                                </tr>
                                @endif
                                @if($booking->passport_visa_attachment && !$booking->passport_attachment && !$booking->visa_attachment)
                                <tr>
                                    <th class="detail-row-th" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-weight: 600; font-size: 13px;">Passport & Visa Doc</th>
                                    <td class="detail-row-td" style="text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #1e293b;"><span style="background-color: #fef3c7; color: #92400e; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 700; display: inline-block;">Attached</span></td>
                                </tr>
                                @endif
                            </table>

                            <p style="text-align: center; color: #64748b; font-size: 13px; font-weight: 500; margin: 0 0 20px 0;">
                                Please review the details above and select your approval decision below:
                            </p>

                            <!-- FULL-WIDTH MOBILE RESPONSIVE ACTION BUTTONS -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 12px; width: 100%;">
                                <tr>
                                    <td align="center" bgcolor="#16a34a" style="border-radius: 10px; background-color: #16a34a;">
                                        <a href="{{ $approveUrl }}" class="action-btn-link" target="_blank" style="font-size: 15px; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; color: #ffffff !important; text-decoration: none; border-radius: 10px; padding: 14px 20px; border: 1px solid #16a34a; display: block; font-weight: 800; text-align: center; background-color: #16a34a; letter-spacing: 0.5px; box-sizing: border-box; width: 100%;">
                                            ✓ APPROVE BOOKING
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="width: 100%;">
                                <tr>
                                    <td align="center" bgcolor="#dc2626" style="border-radius: 10px; background-color: #dc2626;">
                                        <a href="{{ $rejectUrl }}" class="action-btn-link" target="_blank" style="font-size: 15px; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; color: #ffffff !important; text-decoration: none; border-radius: 10px; padding: 14px 20px; border: 1px solid #dc2626; display: block; font-weight: 800; text-align: center; background-color: #dc2626; letter-spacing: 0.5px; box-sizing: border-box; width: 100%;">
                                            ✕ REJECT BOOKING
                                        </a>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; padding: 20px; text-align: center; font-size: 12px; color: #94a3b8; border-top: 1px solid #e2e8f0;">
                            <p style="margin: 0 0 4px 0; font-weight: 600;">&copy; {{ date('Y') }} MCC IGH. All rights reserved.</p>
                            <p style="margin: 0; font-size: 11px; color: #cbd5e1;">This is an automated notification email sent via Madras Christian College Guest House System.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
