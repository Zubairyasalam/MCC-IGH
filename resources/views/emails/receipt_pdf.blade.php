<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Official Invoice - {{ $booking->id }}</title>
    <style>
        @page { margin: 0; }
        body { 
            font-family: 'Inter', 'Helvetica', 'Arial', sans-serif; 
            color: #1e293b; 
            line-height: 1.25; 
            margin: 0; 
            padding: 15px 30px;
            background: #fff;
        }
        .header { 
            text-align: center; 
            margin-bottom: 12px;
            border-bottom: 2px solid {{ $primaryColor }};
            padding-bottom: 10px;
        }
        .logo {
            height: 90px;
            margin-bottom: 8px;
        }
        .institution-name {
            font-size: 18px;
            font-weight: 800;
            color: {{ $primaryColor }};
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }
        .dept-name {
            font-size: 11px;
            color: #64748b;
            margin: 2px 0 0 0;
            font-weight: 600;
        }
        .receipt-label {
            display: inline-block;
            background: #f1f5f9;
            padding: 2px 12px;
            border-radius: 4px;
            font-weight: 800;
            font-size: 11px;
            margin-top: 8px;
            color: #334155;
            letter-spacing: 1px;
        }
        .grid {
            width: 100%;
            margin-bottom: 12px;
        }
        .section-title {
            font-size: 12px;
            font-weight: 800;
            color: {{ $primaryColor }};
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0 0 6px 0;
            border-bottom: 1.5px solid #f1f5f9;
            padding-bottom: 2px;
        }
        .info-table { width: 100%; border-collapse: collapse; }
        .info-table th { 
            text-align: left; 
            padding: 4px 0; 
            color: #000; 
            font-size: 12px; 
            text-transform: uppercase;
            width: 35%;
            font-weight: 800;
        }
        .info-table td { 
            text-align: left; 
            padding: 2px 0; 
            color: #000; 
            font-weight: 800; 
            font-size: 13px;
        }
        .price-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 5px;
            background: #f8fafc;
            border-radius: 6px;
        }
        .price-table th, .price-table td { 
            padding: 6px 12px; 
            border-bottom: 1px solid #e2e8f0;
        }
        .price-table th {
            text-align: left;
            font-size: 10px;
            color: #64748b;
            text-transform: uppercase;
        }
        .price-table td {
            font-size: 13px;
            font-weight: 600;
        }
        .total-amount {
            font-size: 18px;
            font-weight: 800;
            color: {{ $primaryColor }};
        }
        .footer { 
            margin-top: 15px;
            text-align: center; 
            font-size: 9px; 
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
            padding-top: 8px;
            width: 100%;
        }
        .status-badge {
            background: #dcfce7;
            color: #166534;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 800;
        }
        .unpaid-badge {
            background: #fee2e2;
            color: #991b1b;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 800;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/logo_transparent.png'))) }}" class="logo">
        <h1 class="institution-name">Madras Christian College</h1>
        <p class="dept-name">International Guest House & Conference Centre</p>
        <div class="receipt-label">OFFICIAL TAX INVOICE</div>
    </div>

    <table class="grid" style="table-layout: fixed;">
        <tr>
            <td style="vertical-align: top; padding-right: 15px;">
                <p class="section-title">Guest Details</p>
                <table class="info-table">
                    <tr>
                        <th>Name</th>
                        <td>{{ ucwords(strtolower($booking->name)) }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td style="font-size: 11px;">{{ $booking->email }}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{{ $booking->phone }}</td>
                    </tr>
                    <tr>
                        <th>Nationality</th>
                        <td>{{ $booking->nationality ?: 'Indian' }}</td>
                    </tr>
                    <tr>
                        <th>User Type</th>
                        <td>{{ $booking->user_type }}</td>
                    </tr>
                    @if($booking->residence_status)
                    <tr>
                        <th>Residence</th>
                        <td>{{ ucwords($booking->residence_status) }}</td>
                    </tr>
                    @endif
                    @if($booking->user_type === 'Student')
                    <tr>
                        <th>Academic</th>
                        <td style="font-size: 10px;">{{ $booking->level }} | {{ $booking->stream }} | {{ $booking->department }}</td>
                    </tr>
                    @elseif($booking->user_type === 'Staff')
                    <tr>
                        <th>Staff Details</th>
                        <td style="font-size: 10px;">{{ $booking->level }} ({{ $booking->department }})</td>
                    </tr>
                    @else
                    <tr>
                        <th>Unit/Dept</th>
                        <td>{{ $booking->department ?: 'N/A' }}</td>
                    </tr>
                    @endif
                    @if($booking->nationality === 'Non-Indian')
                    <tr>
                        <th>Passport</th>
                        <td>{{ $booking->passport_number ?: 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Visa No</th>
                        <td>{{ $booking->visa_number ?: 'N/A' }}</td>
                    </tr>
                    @if($booking->passport_visa_attachment)
                    <tr>
                        <th>Visa/Passport Copy</th>
                        <td>Attached</td>
                    </tr>
                    @endif
                    @endif
                </table>
            </td>
            <td style="vertical-align: top; padding-left: 15px;">
                <p class="section-title">Invoice Info</p>
                <table class="info-table">
                    <tr>
                        <th>Invoice No</th>
                        <td>#INV-{{ str_pad($booking->id, 8, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td>{{ date('d M, Y') }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($booking->payment_status === 'Paid')
                                <span class="status-badge">PAID</span>
                            @else
                                <span class="unpaid-badge">{{ strtoupper($booking->payment_status) }}</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <p class="section-title">Stay & Service Details</p>
    <table class="info-table" style="margin-bottom: 12px; width: 100%;">
        <tr>
            <th style="width: 25%;">Category</th>
            <td>{{ str_replace('-', ' ', ucwords($booking->room_name, '- ')) }}</td>
        </tr>
        <tr>
            <th style="width: 25%;">Booking ID</th>
            <td>MCC/IGH/{{ date('Y') }}/{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</td>
        </tr>
        <tr>
            <th style="width: 25%;">Check-In</th>
            <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d F, Y') }} | {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}</td>
        </tr>
        <tr>
            <th style="width: 25%;">Check-Out</th>
            <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d F, Y') }} | {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}</td>
        </tr>
        @if($booking->booking_reason)
        <tr>
            <th style="width: 25%;">Purpose</th>
            <td>{{ $booking->booking_reason }}</td>
        </tr>
        @endif
    </table>

    @if(isset($payment))
        <p class="section-title">Payment Transaction Details</p>
        <table class="info-table" style="margin-bottom: 12px; width: 100%;">
            <tr>
                <th style="width: 25%;">Transaction ID</th>
                <td>{{ $payment->txnid }}</td>
            </tr>
            <tr>
                <th style="width: 25%;">Bank Ref No</th>
                <td>{{ $payment->payu_id }}</td>
            </tr>
            <tr>
                <th style="width: 25%;">Payment Mode</th>
                <td>{{ strtoupper($payment->payment_mode ?? 'Online') }}</td>
            </tr>
        </table>
    @endif

    @php
        $gstRate = \App\Models\Setting::where('key', 'gst_rate')->value('value') ?? 5;
        $gstFactor = 1 + ($gstRate / 100);
        $subtotal = $booking->total_price / $gstFactor;
        $gstAmount = $booking->total_price - $subtotal;
    @endphp

    <p class="section-title">Charge Breakdown</p>
    <table class="price-table">
        <tr>
            <th style="width: 70%;">Description</th>
            <th style="text-align: right;">Amount (INR)</th>
        </tr>
        <tr>
            <td>Accommodation Charges (Base)</td>
            <td style="text-align: right;">{{ number_format($subtotal, 2) }}</td>
        </tr>
        <tr>
            <td>GST ({{ $gstRate }}%)</td>
            <td style="text-align: right;">{{ number_format($gstAmount, 2) }}</td>
        </tr>
        <tr style="background: #fff;">
            <td style="text-align: right; border-bottom: none; font-weight: 800; padding-top: 15px;">TOTAL PAYABLE</td>
            <td style="text-align: right; border-bottom: none; padding-top: 15px;">
                <span class="total-amount">Rs. {{ number_format($booking->total_price, 2) }}</span>
            </td>
        </tr>
    </table>

    <div style="margin-top: 12px; font-size: 10px; color: #64748b; line-height: 1.4;">
        <p style="margin-bottom: 3px;"><strong>Terms & Conditions:</strong></p>
        <ul style="padding-left: 15px; margin: 0;">
            <li>This is a computer-generated tax invoice and does not require a physical signature.</li>
            <li>Government-approved Photo ID is mandatory for all guests during check-in.</li>
            <li>All disputes are subject to the jurisdiction of Chennai courts only.</li>
        </ul>
    </div>

    <div class="footer">
        <p><strong>Madras Christian College (Autonomous)</strong> | East Tambaram, Chennai - 600 059</p>
        <p style="margin-top: 5px;">&copy; {{ date('Y') }} MCC IGH. Generated on {{ date('d/m/Y h:i A') }}</p>
    </div>
</body>
</html>
