<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed - MCC IGH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Outfit:wght@700;800;900&family=Roboto+Mono:wght@500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    @include('partials.dynamic-styles')
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background: linear-gradient(135deg, #fdf2f2 0%, #f8f9fc 50%, #f0f4ff 100%);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
        }
        main { padding: 2rem 1rem 4rem; }
        .success-page { max-width: 680px; margin: 0 auto; }

        /* STATUS BADGE */
        .status-badge-row { display: flex; justify-content: center; margin-top: 2%; margin-bottom: 1rem; }
        .status-badge {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 5px 14px; border-radius: 50px;
            font-size: 0.68rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1.2px;
        }
        .status-badge.pending  { background: rgba(245,158,11,0.1); color: #b45309; border: 1px solid rgba(245,158,11,0.25); }
        .status-badge.approved { background: rgba(16,185,129,0.1); color: #059669; border: 1px solid rgba(16,185,129,0.25); }
        .status-badge.rejected { background: rgba(239,68,68,0.1);  color: #dc2626; border: 1px solid rgba(239,68,68,0.25); }

        /* HERO BANNER */
        .hero-banner {
            background: linear-gradient(135deg, #850f0f 0%, #6b0d0d 100%);
            border-radius: 18px;
            padding: 0.75rem 1.25rem; /* reduced padding for compact look */
            display: flex;
            align-items: center;
            gap: 0.75rem;
            position: relative;
            overflow: hidden;
            margin-bottom: 0.75rem; /* reduced margin */
            box-shadow: 0 6px 20px rgba(133,15,15,0.20); /* softer shadow */
        }
        .hero-banner::before {
            content: ''; position: absolute; inset: 0;
            background: radial-gradient(ellipse at 20% 50%, rgba(255,255,255,0.07) 0%, transparent 60%);
        }
        .hero-icon-ring {
            position: relative; z-index: 2; flex-shrink: 0;
            width: 48px; height: 48px; border-radius: 50%;
            background: rgba(255,255,255,0.18); border: 1.5px solid rgba(255,255,255,0.28);
            display: flex; align-items: center; justify-content: center;
        }
        .hero-icon-ring i { font-size: 1.4rem; color: #ffffff !important; }
        .hero-text { position: relative; z-index: 2; text-align: left; }
        .hero-banner h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.15rem; font-weight: 800; color: #ffffff !important;
            letter-spacing: -0.3px; margin-bottom: 3px; line-height: 1.2;
        }
        .hero-banner p {
            color: rgba(255,255,255,0.78);
            font-size: 0.8rem; line-height: 1.5; margin: 0;
        }
        .hero-banner p strong { color: #ffffff; }

        /* RECEIPT CARD */
        .receipt-card {
            background: #ffffff; border-radius: 24px;
            border: 1px solid rgba(226,232,240,0.8); overflow: hidden;
            box-shadow: 0 20px 50px rgba(0,0,0,0.06), 0 1px 3px rgba(0,0,0,0.02);
            margin-bottom: 1.5rem;
        }

        /* LETTERHEAD */
        .receipt-letterhead {
            background: linear-gradient(180deg, #fafafa 0%, #ffffff 100%);
            border-bottom: 1px solid #f1f5f9; padding: 0.75rem 1.5rem 0.6rem; text-align: center;
        }
        .receipt-letterhead img { height: 32px; margin-bottom: 4px; filter: drop-shadow(0 1px 3px rgba(0,0,0,0.06)); }
        .receipt-letterhead h2 {
            font-family: 'Outfit', sans-serif; font-size: 0.78rem; font-weight: 900;
            color: #0f172a; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 1px;
        }
        .receipt-letterhead p { font-size: 0.65rem; color: #64748b; font-weight: 500; margin-bottom: 5px; }
        .receipt-stamp {
            display: inline-flex; align-items: center; gap: 4px;
            background: rgba(133,15,15,0.06); border: 1px solid rgba(133,15,15,0.15);
            color: #850f0f; padding: 3px 10px; border-radius: 50px;
            font-size: 0.6rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;
        }

        /* RECEIPT BODY */
        .receipt-body { padding: 1.5rem 2rem; }

        /* INFO GRID */
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem; }
        .info-block-label {
            font-size: 0.68rem; font-weight: 800; color: #850f0f;
            text-transform: uppercase; letter-spacing: 1.2px; margin-bottom: 10px;
            display: flex; align-items: center; gap: 6px;
            padding-bottom: 8px; border-bottom: 1.5px solid rgba(133,15,15,0.1);
        }
        .info-row { display: flex; flex-direction: column; margin-bottom: 8px; }
        .info-row:last-child { margin-bottom: 0; }
        .info-key { font-size: 0.72rem; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px; }
        .info-val { font-size: 0.9rem; font-weight: 700; color: #0f172a; }

        .section-divider {
            border-top: 1.5px dashed #cbd5e1;
            margin: 1.25rem 0;
            height: 0;
            background: none;
        }
        .bill-font {
            font-family: 'Inter', sans-serif;
            font-variant-numeric: tabular-nums;
            font-weight: 700;
            letter-spacing: -0.2px;
        }

        /* STAY INFO */
        .stay-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem; margin-bottom: 1rem; }
        .stay-chip {
            background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px;
            padding: 12px 14px; display: flex; flex-direction: column; gap: 3px;
        }
        .stay-chip .chip-label { font-size: 0.65rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.8px; }
        .stay-chip .chip-val { font-size: 0.85rem; font-weight: 700; color: #0f172a; }

        /* PRICING */
        .pricing-box {
            background: #ffffff;
            border-top: 2px dashed #cbd5e1;
            border-bottom: 2px dashed #cbd5e1;
            padding: 1.5rem 0.5rem; 
            margin: 1.5rem 0;
        }
        .pricing-row { display: flex; justify-content: space-between; align-items: center; padding: 6px 0; font-size: 0.85rem; }
        .pricing-row .p-label { color: #475569; font-weight: 500; }
        .pricing-row .p-val { font-weight: 600; color: #1e293b; }
        .pricing-separator { border-top: 1.5px dashed #cbd5e1; height: 0; background: none; margin: 10px 0; }
        .pricing-total-row { display: flex; justify-content: space-between; align-items: center; padding-top: 8px; }
        .pricing-total-row .t-label {
            font-family: 'Outfit', sans-serif; font-size: 0.8rem; font-weight: 900;
            color: #0f172a; text-transform: uppercase; letter-spacing: 1px;
        }
        .pricing-total-row .t-val {
            font-family: 'Inter', sans-serif;
            font-variant-numeric: tabular-nums;
            font-size: 1.35rem; font-weight: 800;
            color: #850f0f;
            letter-spacing: -0.5px;
        }

        /* PAYMENT STATUS */
        .payment-status-bar { margin-top: 1rem; text-align: center; padding-top: 0.75rem; border-top: 1px dashed #e2e8f0; }
        .payment-pill {
            display: inline-flex; align-items: center; gap: 6px; padding: 6px 16px;
            border-radius: 50px; font-size: 0.75rem; font-weight: 800;
            text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;
        }
        .payment-pill.pending  { background: rgba(245,158,11,0.1); color: #b45309; border: 1px solid rgba(245,158,11,0.3); }
        .payment-pill.paid     { background: rgba(16,185,129,0.1); color: #059669; border: 1px solid rgba(16,185,129,0.3); }
        .payment-pill.failed   { background: rgba(239,68,68,0.1);  color: #dc2626; border: 1px solid rgba(239,68,68,0.3); }
        .receipt-footer-address { font-size: 0.75rem; color: #94a3b8; font-weight: 500; }

        /* ACTION BUTTONS */
        .action-buttons { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 0.5rem; }
        .btn-dl {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            padding: 1rem 1.5rem; border-radius: 12px; font-weight: 700; font-size: 0.85rem;
            text-transform: uppercase; letter-spacing: 0.5px; text-decoration: none;
            border: none; cursor: pointer; transition: all 0.2s ease;
        }
        .btn-dl.primary {
            background: #850f0f;
            color: #ffffff; box-shadow: 0 4px 12px rgba(133,15,15,0.15);
        }
        .btn-dl.primary:hover { background: #6b0d0d; transform: translateY(-2px); box-shadow: 0 6px 16px rgba(133,15,15,0.25); }
        .btn-dl.secondary {
            background: #ffffff; color: #475569;
            border: 1.5px solid #cbd5e1; box-shadow: 0 2px 6px rgba(0,0,0,0.02);
        }
        .btn-dl.secondary:hover { background: #f8fafc; border-color: #94a3b8; color: #1e293b; transform: translateY(-1px); box-shadow: 0 4px 10px rgba(0,0,0,0.04); }

        /* MOBILE */
        @media (max-width: 768px) {
            main { padding: 1rem 0.75rem 3rem; }
            .hero-banner { padding: 2rem 1.5rem 1.75rem; border-radius: 20px; }
            .hero-banner h1 { font-size: 1.6rem; }
            .hero-banner p  { font-size: 0.88rem; }
            .hero-icon-ring { width: 72px; height: 72px; margin-bottom: 1.25rem; }
            .hero-icon-ring i { font-size: 2rem; }
            .receipt-letterhead { padding: 1.5rem 1.5rem 1.25rem; }
            .receipt-body { padding: 1.5rem; }
            .info-grid { grid-template-columns: 1fr; gap: 1rem; }
            .stay-info-grid { grid-template-columns: 1fr; }
            .action-buttons { grid-template-columns: 1fr; }
            .pricing-total-row .t-val { font-size: 1.35rem; }
        }
    </style>
</head>
<body>
    @include('partials.header')
    <main>
        <div class="success-page">

            <div class="status-badge-row">
                @if($booking->approval_status === 'Pending')
                    <div class="status-badge pending"><i class="ph-fill ph-clock" style="font-size:14px;"></i> Pending Approval</div>
                @elseif($booking->approval_status === 'Rejected')
                    <div class="status-badge rejected"><i class="ph-fill ph-x-circle" style="font-size:14px;"></i> Booking Rejected</div>
                @else
                    <div class="status-badge approved"><i class="ph-fill ph-check-circle" style="font-size:14px;"></i> Booking Confirmed</div>
                @endif
            </div>

            <div class="hero-banner">
                <div class="hero-icon-ring">
                    @if($booking->approval_status === 'Pending')
                        <i class="ph-fill ph-clock"></i>
                    @elseif($booking->approval_status === 'Rejected')
                        <i class="ph-fill ph-x-circle"></i>
                    @else
                        <i class="ph-fill ph-check-circle"></i>
                    @endif
                </div>
                <div class="hero-text">
                    @if($booking->approval_status === 'Pending')
                        <h1>Booking Submitted!</h1>
                        <p>Your request has been sent to the <strong>Principal</strong> for approval. You will receive an email once approved.</p>
                    @elseif($booking->approval_status === 'Rejected')
                        <h1>Booking Rejected</h1>
                        <p>Your booking was not approved. Please contact the office for details.</p>
                    @else
                        <h1>Booking Confirmed!</h1>
                        <p>Your booking has been confirmed. See your receipt below.</p>
                    @endif
                </div>
            </div>

            <div class="receipt-card" id="receiptContent">
                <div class="receipt-letterhead">
                    <img src="{{ asset('assets/logo.png') }}" alt="MCC Logo">
                    <h2>Madras Christian College</h2>
                    <p>International Guest House &amp; Conference Centre</p>
                    <div class="receipt-stamp"><i class="ph-bold ph-receipt" style="font-size:12px;"></i> Receipt Summary</div>
                </div>

                <div class="receipt-body">
                    <div class="info-grid">
                        <div>
                            <div class="info-block-label"><i class="ph-fill ph-user-circle" style="font-size:14px;"></i> Guest Details</div>
                            <div class="info-row"><span class="info-key">Name</span><span class="info-val">{{ ucwords(strtolower($booking->name)) }}</span></div>
                            <div class="info-row"><span class="info-key">Phone</span><span class="info-val bill-font">{{ $booking->phone }}</span></div>
                            <div class="info-row"><span class="info-key">Nationality</span><span class="info-val">{{ $booking->nationality ?: 'Indian' }}</span></div>
                            @if($booking->user_type)
                            <div class="info-row"><span class="info-key">User Type</span><span class="info-val">{{ $booking->user_type }}</span></div>
                            @endif
                            @if($booking->residence_status)
                            <div class="info-row"><span class="info-key">Residence</span><span class="info-val">{{ ucwords($booking->residence_status) }}</span></div>
                            @endif
                            @if($booking->nationality === 'Non-Indian')
                            <div class="info-row"><span class="info-key">Passport</span><span class="info-val bill-font">{{ $booking->passport_number ?: '—' }}</span></div>
                            <div class="info-row"><span class="info-key">Visa No.</span><span class="info-val bill-font">{{ $booking->visa_number ?: '—' }}</span></div>
                            @if($booking->passport_attachment)
                            <div class="info-row"><span class="info-key">Passport copy</span><span class="info-val"><a href="{{ asset('storage/' . $booking->passport_attachment) }}" target="_blank" style="color: #850f0f; font-weight: 700; text-decoration: none;">View Passport</a></span></div>
                            @endif
                            @if($booking->visa_attachment)
                            <div class="info-row"><span class="info-key">Visa copy</span><span class="info-val"><a href="{{ asset('storage/' . $booking->visa_attachment) }}" target="_blank" style="color: #850f0f; font-weight: 700; text-decoration: none;">View Visa</a></span></div>
                            @endif
                            @if($booking->passport_visa_attachment && !$booking->passport_attachment && !$booking->visa_attachment)
                            <div class="info-row"><span class="info-key">Visa/Passport copy</span><span class="info-val"><a href="{{ asset('storage/' . $booking->passport_visa_attachment) }}" target="_blank" style="color: #850f0f; font-weight: 700; text-decoration: none;">View Document</a></span></div>
                            @endif
                            @endif
                        </div>
                        <div>
                            <div class="info-block-label"><i class="ph-fill ph-calendar-check" style="font-size:14px;"></i> Booking Details</div>
                            <div class="info-row"><span class="info-key">Booking ID</span><span class="info-val bill-font">#{{ str_pad($booking->id, 8, '0', STR_PAD_LEFT) }}</span></div>
                            <div class="info-row"><span class="info-key">Date</span><span class="info-val bill-font">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M, Y') }}</span></div>
                        </div>
                    </div>

                    <div class="section-divider"></div>

                    <div class="info-block-label" style="margin-bottom: 12px;"><i class="ph-fill ph-building-office" style="font-size:14px;"></i> Stay Information</div>
                    <div class="stay-info-grid">
                        <div class="stay-chip"><span class="chip-label">Category</span><span class="chip-val">{{ str_replace('-', ' ', ucwords($booking->room_name, '- ')) }}</span></div>
                        <div class="stay-chip"><span class="chip-label">Check-In</span><span class="chip-val bill-font">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M, Y') }} &middot; {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}</span></div>
                        <div class="stay-chip"><span class="chip-label">Check-Out</span><span class="chip-val bill-font">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M, Y') }} &middot; {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}</span></div>
                        @if($booking->booking_reason)
                        <div class="stay-chip"><span class="chip-label">Purpose</span><span class="chip-val">{{ ucfirst($booking->booking_reason) }}</span></div>
                        @endif
                    </div>

                    <div class="section-divider"></div>

                    @php
                        $gstRate = \App\Models\Setting::where('key', 'gst_rate')->value('value') ?? 5;
                        $gstFactor = 1 + ($gstRate / 100);
                        $subtotal = $booking->total_price / $gstFactor;
                        $gstAmount = $booking->total_price - $subtotal;
                    @endphp

                    <div class="pricing-box">
                        <div class="pricing-row"><span class="p-label">Accommodation (Subtotal)</span><span class="p-val bill-font">₹ {{ number_format($subtotal, 2) }}</span></div>
                        <div class="pricing-row"><span class="p-label">Tax (GST {{ $gstRate }}%)</span><span class="p-val bill-font">₹ {{ number_format($gstAmount, 2) }}</span></div>
                        <div class="pricing-separator"></div>
                        <div class="pricing-total-row"><span class="t-label">Total Amount</span><span class="t-val bill-font">₹ {{ number_format($booking->total_price, 2) }}</span></div>
                    </div>

                    <div class="payment-status-bar">
                        @php $payStatus = strtolower($booking->payment_status); @endphp
                        <div class="payment-pill {{ in_array($payStatus, ['paid','success']) ? 'paid' : ($payStatus === 'failed' ? 'failed' : 'pending') }}">
                            <i class="ph-fill {{ in_array($payStatus, ['paid','success']) ? 'ph-check-circle' : ($payStatus === 'failed' ? 'ph-x-circle' : 'ph-clock') }}" style="font-size:13px;"></i>
                            Payment: {{ strtoupper($booking->payment_status) }}
                        </div>
                        <div class="receipt-footer-address"><i class="ph ph-map-pin" style="font-size:12px;"></i> Madras Christian College, East Tambaram, Chennai</div>
                    </div>
                </div>
            </div>

            <div class="action-buttons">
                <a href="{{ route('receipt.download', $booking->id) }}" class="btn-dl primary" id="manualDownloadBtn">
                    <i class="ph-bold ph-download-simple" style="font-size:16px;"></i> Download Receipt
                </a>
                <button class="btn-dl secondary" onclick="window.location.href='{{ route('home') }}'">
                    <i class="ph-bold ph-house" style="font-size:16px;"></i> Back to Home
                </button>
            </div>

        </div>
    </main>
    <script src="{{ asset('js/script.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.location.search.includes('download=1')) {
                setTimeout(() => { window.location.href = "{{ route('receipt.download', $booking->id) }}"; }, 1500);
            }
        });
    </script>
</body>
</html>
