<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Form - MCC IGH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}?v={{ time() }}">
    <style>
        .booking-page-container {
            max-width: 1180px;
            margin: 0.5rem auto 2rem auto;
            padding: 8px 24px 24px 24px;
            width: 100%;
            box-sizing: border-box;
        }

        .page-hero-card {
            background: linear-gradient(135deg, #ffffff 0%, #fef8f8 100%);
            border: 1px solid rgba(133, 15, 15, 0.08);
            border-radius: 24px;
            padding: 1.5rem; /* reduced padding for compact look */
            margin-bottom: 1rem; /* reduced margin */
            box-shadow: 0 8px 20px -8px rgba(133, 15, 15, 0.04);
            position: relative;
            overflow: hidden;
        }

        .page-hero-card-badge {
            background: rgba(133, 15, 15, 0.06);
            color: #850f0f;
            font-size: 11px;
            font-weight: 800;
            padding: 6px 14px;
            border-radius: 50px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: 1px solid rgba(133, 15, 15, 0.15);
            display: inline-block;
            margin-bottom: 12px;
        }

        .page-hero-card h1 {
            font-size: 2.2rem;
            font-weight: 900;
            color: #0f172a;
            margin: 0 0 8px 0;
            letter-spacing: -0.5px;
        }

        .page-hero-card p {
            font-size: 1rem;
            color: #475569;
            margin: 0;
        }

        .booking-layout-grid {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 2.5rem;
            align-items: start;
            width: 100%;
        }

        @media (max-width: 991px) {
            .booking-layout-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
        }

        .form-container {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 15px 35px -10px rgba(0, 0, 0, 0.04), 0 1px 3px rgba(0, 0, 0, 0.02);
            padding: 2.5rem;
            border: 1px solid #f1f5f9;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            align-items: start;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .paired-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            align-items: start;
            grid-column: 1 / -1;
            width: 100%;
        }

        .form-label {
            font-size: 13.5px;
            font-weight: 750;
            color: #1e293b;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .form-label span {
            color: #ef4444;
        }

        .form-helper {
            font-size: 11.5px;
            color: #64748b;
            font-weight: 500;
            margin-top: 6px;
            display: block;
        }

        .form-input,
        .form-select,
        input.form-input,
        select.form-select {
            height: 48px;
            padding: 0 16px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-family: 'Inter', sans-serif;
            font-size: 14.5px;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            background: #ffffff;
            width: 100%;
            box-sizing: border-box;
            color: #0f172a !important;
            font-weight: 600 !important;
        }

        .form-select,
        select.form-select {
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23475569' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e") !important;
            background-repeat: no-repeat !important;
            background-position: right 16px center !important;
            background-size: 14px !important;
            padding-right: 40px !important;
        }

        .form-input::placeholder {
            color: #94a3b8 !important;
            font-weight: 400 !important;
        }

        .form-input:focus,
        .form-select:focus {
            outline: none;
            border-color: #850f0f;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(133, 15, 15, 0.08);
        }

        /* Custom Radio Button Group */
        .form-radio-group {
            display: flex;
            gap: 1.5rem;
            align-items: center;
            padding: 6px 0;
        }

        .radio-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14.5px;
            cursor: pointer;
            color: #334155;
            font-weight: 600;
            user-select: none;
        }

        .radio-label input[type="radio"] {
            appearance: none;
            background-color: #fff;
            margin: 0;
            font: inherit;
            color: #850f0f;
            width: 20px;
            height: 20px;
            border: 2px solid #cbd5e1;
            border-radius: 50%;
            display: grid;
            place-content: center;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            cursor: pointer;
        }

        .radio-label input[type="radio"]::before {
            content: "";
            width: 10px;
            height: 10px;
            border-radius: 50%;
            transform: scale(0);
            transition: 120ms transform ease-in-out;
            background-color: currentColor;
        }

        .radio-label input[type="radio"]:checked {
            border-color: #850f0f;
        }

        .radio-label input[type="radio"]:checked::before {
            transform: scale(1);
        }

        .breadcrumb {
            font-size: 0.9rem;
            color: #64748b;
            margin-bottom: 1.25rem;
            font-weight: 600;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
        }

        .breadcrumb a {
            color: #850f0f;
            text-decoration: none;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .breadcrumb-separator {
            color: #94a3b8;
        }

        .breadcrumb-current {
            color: #64748b;
        }

        .form-section-title {
            font-size: 1.2rem;
            font-weight: 800;
            color: #0f172a;
            padding-bottom: 8px;
            border-bottom: 2px solid rgba(133, 15, 15, 0.08);
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 0 !important;
            margin-bottom: 0.25rem !important;
            grid-column: 1 / -1;
        }

        .form-section-title i {
            font-size: 1.35rem;
            color: #850f0f;
        }

        .dynamic-field {
            display: none !important;
        }

        .dynamic-field.show {
            display: flex !important;
        }

        /* Booking Summary Card */
        .booking-summary-card {
            background: #ffffff;
            border: 1px solid #f1f5f9;
            border-radius: 24px;
            padding: 24px;
            box-shadow: 0 15px 35px -10px rgba(0, 0, 0, 0.04), 0 1px 3px rgba(0, 0, 0, 0.02);
            position: sticky;
            top: 90px;
            z-index: 10;
        }

        .summary-room-img {
            width: 100%;
            aspect-ratio: 16 / 9;
            object-fit: cover;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
        }

        .summary-room-badge {
            background: rgba(133, 15, 15, 0.06);
            border: 1px solid rgba(133, 15, 15, 0.12);
            color: #850f0f;
            padding: 4px 10px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            display: inline-block;
            margin-bottom: 10px;
        }

        .summary-room-title {
            font-size: 1.25rem;
            font-weight: 850;
            color: #0f172a;
            margin-bottom: 6px;
            line-height: 1.3;
        }

        .summary-detail-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 13.5px;
            color: #475569;
            margin-bottom: 12px;
        }

        .summary-detail-row span.label {
            font-weight: 600;
            color: #64748b;
        }

        .summary-detail-row span.value {
            font-weight: 750;
            color: #0f172a;
        }

        .summary-divider {
            height: 1px;
            background: linear-gradient(90deg, #e2e8f0 0%, rgba(226, 232, 240, 0.1) 100%);
            margin: 18px 0;
        }

        .summary-calculator-box {
            background: #fafbfc;
            border: 1px solid #f1f5f9;
            border-radius: 16px;
            padding: 16px;
            margin-top: 15px;
        }

        .calculator-title {
            font-size: 11px;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .calc-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13.5px;
            margin-bottom: 10px;
            color: #475569;
        }

        .calc-row.total {
            margin-top: 14px;
            padding-top: 12px;
            border-top: 1px dashed #cbd5e1;
            font-weight: 900;
            font-size: 16px;
            color: #850f0f;
        }

        .calc-row.total span.val {
            font-size: 22px;
            font-weight: 900;
        }

        .gst-badge {
            display: inline-flex;
            align-items: center;
            background-color: rgba(133, 15, 15, 0.05);
            color: #850f0f;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 750;
            border: 1px solid rgba(133, 15, 15, 0.15);
            white-space: nowrap;
        }

        .section-header-flex {
            grid-column: 1 / -1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1.5rem;
            margin-bottom: 0.5rem;
            border-bottom: 2px solid rgba(133, 15, 15, 0.08);
            padding-bottom: 8px;
        }

        .section-header-flex .form-section-title {
            margin: 0;
            padding: 0;
            border-bottom: none;
        }

        .submit-btn {
            background: linear-gradient(135deg, #850f0f, #b91c1c) !important;
            color: #ffffff !important;
            border: none !important;
            padding: 18px 24px !important;
            font-size: 15px !important;
            font-weight: 800 !important;
            border-radius: 16px !important;
            cursor: pointer !important;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px !important;
            width: 100% !important;
            margin-top: 1rem !important;
            box-shadow: 0 10px 25px rgba(133, 15, 15, 0.2) !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
            font-family: inherit !important;
        }

        .submit-btn:hover {
            box-shadow: 0 15px 35px rgba(133, 15, 15, 0.3) !important;
            transform: translateY(-2px) !important;
        }

        .submit-btn:active {
            transform: translateY(0) !important;
        }

        /* Custom File Zone */
        .custom-file-zone {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 18px;
            border: 1.5px dashed #cbd5e1;
            border-radius: 14px;
            background: #f8fafc;
            cursor: pointer;
            transition: all 0.25s ease;
            width: 100%;
            box-sizing: border-box;
            overflow: hidden;
        }

        .custom-file-zone:hover {
            border-color: #850f0f;
            background: rgba(133, 15, 15, 0.02);
        }

        .file-icon-box {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            flex-shrink: 0;
        }

        .file-icon-box i { font-size: 1.5rem; color: #850f0f; }
        .file-text-group { flex: 1; min-width: 0; }
        .file-main-text { display: block; font-weight: 750; font-size: 13.5px; color: #0f172a; margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        .section-divider {
            grid-column: 1 / -1;
            height: 1px;
            background: linear-gradient(90deg, rgba(133, 15, 15, 0.08) 0%, rgba(133, 15, 15, 0.02) 100%);
            margin: 0 !important;
            width: 100%;
        }

        @media (max-width: 768px) {
            html, body {
                max-width: 100% !important;
                overflow-x: hidden !important;
                width: 100% !important;
            }
            header, .header-container, .main-header {
                max-width: 100% !important;
                width: 100% !important;
                overflow: hidden !important;
                box-sizing: border-box !important;
            }
            footer, .main-footer, .footer-content {
                max-width: 100% !important;
                width: 100% !important;
                overflow: hidden !important;
                box-sizing: border-box !important;
            }
            main {
                padding: 0 8px 8px !important;
            }
            .booking-page-container {
                margin: 0 auto 1rem auto !important;
                padding: 8px 16px 16px 16px !important;
                max-width: 100% !important;
                width: 100% !important;
                box-sizing: border-box !important;
                overflow-x: hidden !important;
            }
            .booking-layout-grid {
                display: block !important;
                width: 100% !important;
            }
            .booking-form-column {
                width: 100% !important;
                display: block !important;
                margin: 0 0 0.75rem 0 !important;
                padding: 0 !important;
            }
            .booking-summary-column {
                width: 100% !important;
                display: block !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .form-container {
                padding: 20px 16px !important;
                border-radius: 20px !important;
                box-sizing: border-box !important;
                width: 100% !important;
                overflow-x: hidden !important;
                background: #ffffff !important;
                border: 1.5px solid var(--primary-color) !important;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05) !important;
            }

            .form-helper[style*="visibility: hidden"],
            .form-helper[style*="visibility:hidden"] {
                display: none !important;
            }
            .section-divider {
                display: none !important;
            }
            .section-header-flex {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 0.5rem !important;
                margin-top: 0.75rem !important;
                margin-bottom: 0.5rem !important;
                padding-bottom: 6px !important;
            }
            .gst-badge {
                white-space: normal !important;
                word-break: break-word !important;
                text-align: left !important;
                display: inline-flex !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
            }
            .page-hero-card {
                padding: 1.25rem 1rem !important;
                border-radius: 16px !important;
                margin-bottom: 0.75rem !important;
            }
            .page-hero-card h1 {
                font-size: 1.75rem !important;
            }
            .breadcrumb {
                margin-top: 0.25rem !important;
                margin-bottom: 0.75rem !important;
                font-size: 0.8rem !important;
            }
            form {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                box-sizing: border-box !important;
            }
            .form-grid, .paired-row {
                grid-template-columns: 100% !important;
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
                gap: 12px !important;
            }
            .form-group {
                height: auto !important;
                justify-content: flex-start !important;
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
            }
            .form-input, .form-select, select.form-select, input.form-input {
                font-size: 13.5px !important;
                padding: 0 12px !important;
                height: 44px !important;
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
            }
            .form-select,
            select.form-select {
                background-position: right 12px center !important;
                padding-right: 32px !important;
            }
            .form-label {
                font-size: 13px !important;
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
            }
            .form-section-title {
                font-size: 1.1rem !important;
                margin-top: 0 !important;
                margin-bottom: 8px !important;
            }
            .form-radio-group {
                gap: 1rem !important;
                flex-wrap: wrap !important;
            }
            .booking-summary-card {
                padding: 20px 16px !important;
                border-radius: 20px !important;
                border: 1.5px solid var(--primary-color) !important;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05) !important;
                position: static !important;
            }
            .calc-row.total span.val {
                font-size: 18px !important;
            }

            /* Custom file upload zone vertical stack on mobile */
            .custom-file-zone {
                flex-direction: column !important;
                align-items: center !important;
                text-align: center !important;
                padding: 20px 16px !important;
                gap: 10px !important;
            }
            .file-text-group {
                width: 100% !important;
                text-align: center !important;
            }
            .file-main-text {
                white-space: normal !important;
                text-overflow: unset !important;
                overflow: visible !important;
                font-size: 13.5px !important;
                line-height: 1.4 !important;
                margin-bottom: 4px !important;
                text-align: center !important;
            }
            .file-sub-text {
                text-align: center !important;
                font-size: 11px !important;
            }
            .browse-btn-mobile {
                width: 100% !important;
                text-align: center !important;
                padding: 10px !important;
                font-size: 0.9rem !important;
                border-radius: 10px !important;
                box-sizing: border-box !important;
            }
        }
    </style>
    @include('partials.dynamic-styles')
</head>

<body style="background: #f8fafc;">
    @include('partials.header', ['headerBackBtn' => ['url' => route('home'), 'label' => 'Home']])

    @php
        $rawRoomId = $roomId ?? 'conference-room';
        $normalizedSlug = \Illuminate\Support\Str::slug($rawRoomId);
        
        // Get GST rate from DB
        $gstRate = \App\Models\Setting::where('key', 'gst_rate')->value('value') ?? 5;
        
        // Master static room data for Summary Card and Form Validation
        $masterRooms = [
            'conference-hall' => [
                'name' => 'Conference Hall',
                'price' => 2000,
                'price_formatted' => '₹2,000',
                'time' => '/ 4 Hours',
                'capacity_num' => 60,
                'capacity' => '60 Members',
                'img' => asset('assets/standard/conference.JPG'),
                'category' => 'Conference Wing'
            ],
            'conference-room' => [
                'name' => 'Conference Room',
                'price' => 2000,
                'price_formatted' => '₹2,000',
                'time' => '/ 4 Hours',
                'capacity_num' => 60,
                'capacity' => '60 Members',
                'img' => asset('assets/standard/conference.JPG'),
                'category' => 'Conference Wing'
            ],
            'glass-room' => [
                'name' => 'Glass Room',
                'price' => 1500,
                'price_formatted' => '₹1,500',
                'time' => '/ 4 Hours',
                'capacity_num' => 15,
                'capacity' => '15 Members',
                'img' => asset('assets/standard/glass.JPG'),
                'category' => 'Conference Wing'
            ],
            'suite-room' => [
                'name' => 'Luxury Suite Room',
                'price' => 4500,
                'price_formatted' => '₹4,500',
                'time' => '/ Day',
                'capacity_num' => 2,
                'capacity' => '2 Members',
                'img' => asset('assets/suite.JPG'),
                'category' => 'Luxury Wing'
            ],
            'standard-guest-room' => [
                'name' => 'Standard Guest Room',
                'price' => 1400,
                'price_formatted' => '₹1,400',
                'time' => '/ 12 Hours',
                'capacity_num' => 2,
                'capacity' => '2 Members',
                'img' => asset('assets/standard/standardroom.JPG'),
                'category' => 'Guest Wing'
            ],
            'advance-executive-room' => [
                'name' => 'Advance Executive Room',
                'price' => 2500,
                'price_formatted' => '₹2,500',
                'time' => '/ Day',
                'capacity_num' => 4,
                'capacity' => '4 Members',
                'img' => asset('assets/room1.JPG'),
                'category' => 'Executive Wing'
            ]
        ];

        // Match room by exact key, slug match, or partial keyword match
        if (isset($masterRooms[$normalizedSlug])) {
            $room = $masterRooms[$normalizedSlug];
        } elseif (str_contains($normalizedSlug, 'glass')) {
            $room = $masterRooms['glass-room'];
        } elseif (str_contains($normalizedSlug, 'conference')) {
            $room = $masterRooms['conference-room'];
        } elseif (str_contains($normalizedSlug, 'suite')) {
            $room = $masterRooms['suite-room'];
        } elseif (str_contains($normalizedSlug, 'standard')) {
            $room = $masterRooms['standard-guest-room'];
        } elseif (str_contains($normalizedSlug, 'advance') || is_numeric($rawRoomId)) {
            $room = $masterRooms['advance-executive-room'];
        } else {
            $room = $masterRooms['conference-room'];
        }

        $maxCapacity = $room['capacity_num'];
    @endphp

    <main>
        <div class="booking-page-container">

            <!-- Breadcrumbs ALIGNED EXACTLY WITH HEADER & FORM -->
            <div class="breadcrumb">
                <a href="{{ route('home') }}">
                    <i class="ph-bold ph-house"></i>
                    Home
                </a>
                <span class="breadcrumb-separator">›</span>
                <a href="{{ route('standard.rooms') }}">Rooms</a>
                <span class="breadcrumb-separator">›</span>
                <span class="breadcrumb-current">Booking Form</span>
            </div>

            <!-- Page Header -->
            <div class="page-hero-card">
                <span class="page-hero-card-badge">Secure Your Stay</span>
                <h1>IGH <span style="color: #850f0f;">Booking</span></h1>
                <p>Secure your accommodation efficiently for <strong id="heroRoomTitle">{{ $room['name'] }}</strong></p>
            </div>

            <div class="booking-layout-grid">
                <!-- Left Column: The Form -->
                <div class="booking-form-column">
                    <div class="form-container">
                        @if($errors->any())
                        <div style="background: #fee2e2; border: 1px solid #ef4444; color: #b91c1c; padding: 1rem; border-radius: 8px; margin-bottom: 2rem;">
                            <ul style="margin: 0; padding-left: 1.5rem;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        @if(session('error'))
                        <div style="background: #fee2e2; border: 1px solid #ef4444; color: #b91c1c; padding: 1rem; border-radius: 8px; margin-bottom: 2rem;">
                            {{ session('error') }}
                        </div>
                        @endif

                        <form action="{{ route('booking.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="room_name" id="hiddenRoomNameInput" value="{{ $roomId }}">

                    <div class="form-grid">

                        <!-- SECTION: PROFILE DETAILS -->
                        <div class="form-section-title full-width"><i class="ph-bold ph-identification-card"></i>
                            Personal Details</div>

                        <!-- Nationality -->
                        <div class="form-group full-width" style="margin-bottom: 0.75rem;">
                            <label class="form-label">Nationality <span>*</span></label>
                            <div class="form-radio-group">
                                <label class="radio-label"><input type="radio" name="nationality" value="Indian"
                                        onchange="toggleNationalityFields()" checked> Indian</label>
                                <label class="radio-label"><input type="radio" name="nationality" value="Non-Indian"
                                        onchange="toggleNationalityFields()"> Non-Indian</label>
                            </div>
                        </div>

                        <!-- DYNAMIC: Non-Indian Fields (Passport & Visa) -->
                        <div class="paired-row dynamic-field non-indian-field" id="nonIndianRow">
                            <div class="form-group" id="passportFieldGroup">
                                <label class="form-label">Passport Number <span>*</span></label>
                                <input type="text" name="passport_number" class="form-input"
                                    placeholder="Required for Non-Indian guests" id="passportInput" value="{{ old('passport_number') }}">
                            </div>

                            <div class="form-group" id="visaFieldGroup">
                                <label class="form-label">Visa Number <span>*</span></label>
                                <input type="text" name="visa_number" class="form-input"
                                    placeholder="Required for Non-Indian guests" id="visaInput" value="{{ old('visa_number') }}">
                            </div>
                        </div>

                        <!-- Passport Document Upload -->
                        <div class="form-group dynamic-field non-indian-field full-width" id="passportUploadGroup" style="display: none; margin-top: 0.25rem;">
                            <label class="form-label">Passport Scanned Copy <span>*</span></label>
                            <div id="passportUploadZone" class="custom-file-zone" onclick="document.getElementById('passportFileInput').click()">
                                <div class="file-icon-box">
                                    <i class="ph-bold ph-file-arrow-up"></i>
                                </div>
                                <div class="file-text-group" style="min-width: 0; flex: 1;">
                                    <div id="passportFileNameDisplay" class="file-main-text">Click to attach Passport scanned copy</div>
                                    <div class="file-sub-text">PDF, JPG, PNG supported (Max 5MB)</div>
                                </div>
                                <div class="browse-btn-mobile" style="flex-shrink: 0; background: var(--primary-color); color: #fff; padding: 7px 16px; border-radius: 8px; font-size: 0.8rem; font-weight: 700; letter-spacing: 0.3px;">Browse</div>
                            </div>
                            <input type="file" id="passportFileInput" name="passport_attachment" accept=".pdf,.jpg,.jpeg,.png" style="display: none;" onchange="updatePassportFileName(this)">
                            <div class="form-helper" style="margin-top: 6px;">Upload a scanned copy of your Passport</div>
                        </div>

                        <!-- Visa Document Upload -->
                        <div class="form-group dynamic-field non-indian-field full-width" id="visaUploadGroup" style="display: none; margin-top: 0.25rem;">
                            <label class="form-label">Visa Scanned Copy <span>*</span></label>
                            <div id="visaUploadZone" class="custom-file-zone" onclick="document.getElementById('visaFileInput').click()">
                                <div class="file-icon-box">
                                    <i class="ph-bold ph-file-arrow-up"></i>
                                </div>
                                <div class="file-text-group" style="min-width: 0; flex: 1;">
                                    <div id="visaFileNameDisplay" class="file-main-text">Click to attach Visa scanned copy</div>
                                    <div class="file-sub-text">PDF, JPG, PNG supported (Max 5MB)</div>
                                </div>
                                <div class="browse-btn-mobile" style="flex-shrink: 0; background: var(--primary-color); color: #fff; padding: 7px 16px; border-radius: 8px; font-size: 0.8rem; font-weight: 700; letter-spacing: 0.3px;">Browse</div>
                            </div>
                            <input type="file" id="visaFileInput" name="visa_attachment" accept=".pdf,.jpg,.jpeg,.png" style="display: none;" onchange="updateVisaFileName(this)">
                            <div class="form-helper" style="margin-top: 6px;">Upload a scanned copy of your Visa</div>
                        </div>
                        <script>
                            function updatePassportFileName(input) {
                                const display = document.getElementById('passportFileNameDisplay');
                                if (input.files && input.files[0]) {
                                    display.textContent = input.files[0].name;
                                    display.style.color = 'var(--primary-color)';
                                } else {
                                    display.textContent = 'Click to attach Passport scanned copy';
                                    display.style.color = '#475569';
                                }
                            }
                            function updateVisaFileName(input) {
                                const display = document.getElementById('visaFileNameDisplay');
                                if (input.files && input.files[0]) {
                                    display.textContent = input.files[0].name;
                                    display.style.color = 'var(--primary-color)';
                                } else {
                                    display.textContent = 'Click to attach Visa scanned copy';
                                    display.style.color = '#475569';
                                }
                            }
                        </script>

                        <div class="form-group dynamic-field non-indian-field full-width" id="gstFieldGroup">
                            <label class="form-label">GST Number</label>
                            <input type="text" name="gst_id" class="form-input"
                                placeholder="If applicable for corporate booking (Optional)">
                        </div>

                        <!-- ISOLATED ROW 1: User Type (Left) | Applicant Name (Right) -->
                        <div class="paired-row">
                            <!-- User Type -->
                            <div class="form-group">
                                <label class="form-label">User Type <span>*</span></label>
                                <select class="form-select" id="userTypeSelect" name="user_type"
                                    onchange="toggleStudentFields()" required>
                                    <option value="" disabled selected>Select Current Status</option>
                                    <option value="Student">Student</option>
                                    <option value="Staff">Staff</option>
                                    <option value="Alumni">Alumni</option>
                                </select>
                                <div class="form-helper">Your formal association with the institution</div>
                            </div>

                            <!-- Name -->
                            <div class="form-group">
                                <label class="form-label">Applicant Name <span>*</span></label>
                                <input type="text" name="name" class="form-input"
                                    placeholder="Your full name as per official ID" value="{{ old('name') }}" required>
                            </div>
                        </div>

                        <div class="form-group dynamic-field student-field full-width" id="residenceStatusFieldGroup">
                            <label class="form-label">Residence Status <span>*</span></label>
                            <div class="form-radio-group">
                                <label class="radio-label"><input type="radio" name="residence_status" value="residence"> Residence</label>
                                <label class="radio-label"><input type="radio" name="residence_status" value="non residence"> Non Residence</label>
                            </div>
                            <div class="form-helper">Select your residential status in college</div>
                        </div>

                        <div class="form-group dynamic-field student-field full-width" id="streamFieldGroup">
                            <label class="form-label">Stream <span>*</span></label>
                            <div class="form-radio-group">
                                <label class="radio-label"><input type="radio" name="stream" value="Aided"
                                        onchange="updateDepartments('Aided')"> Aided</label>
                                <label class="radio-label"><input type="radio" name="stream" value="SFS"
                                        onchange="updateDepartments('SFS')"> SFS</label>
                            </div>
                            <div class="form-helper">Select academic stream</div>
                        </div>

                        <div class="form-group dynamic-field student-field" id="levelFieldGroup">
                            <label class="form-label">Level <span>*</span></label>
                            <select class="form-select" name="level" id="levelSelect" onchange="handleLevelChange()">
                                <option value="" disabled selected>Select Degree Level</option>
                                <option value="UG">Undergraduate (UG)</option>
                                <option value="PG">Postgraduate (PG)</option>
                                <option value="MPhil">Master of Philosophy (MPhil)</option>
                                <option value="PhD">Doctorate (PhD)</option>
                            </select>
                        </div>

                        <div class="form-group dynamic-field student-field full-width" id="departmentFieldGroup">
                            <label class="form-label">Department <span>*</span></label>
                            <select class="form-select" id="departmentSelect" name="department"
                                onchange="toggleOtherDepartment()">
                                <option value="" disabled selected>Select Stream First</option>
                            </select>

                            <!-- Hidden Smooth "Other" Field -->
                            <div id="otherDepartmentWrapper"
                                style="overflow: hidden; max-height: 0; display: none; margin-top: 0.5rem;">
                                <input type="text" class="form-input" id="otherDepartmentInput" name="department_other"
                                    placeholder="Enter Department Name" style="border-color: var(--primary-color);">
                            </div>
                        </div>

                        <!-- Dynamic Staff Fields -->
                        <div class="paired-row">
                            <div class="form-group dynamic-field staff-field" id="staffTypeFieldGroup" style="display: none;">
                                <label class="form-label">Staff Category <span>*</span></label>
                                <div class="form-radio-group">
                                    <label class="radio-label"><input type="radio" name="staff_type" value="Teaching"
                                            onchange="toggleStaffCategoryFields()"> Teaching Staff</label>
                                    <label class="radio-label"><input type="radio" name="staff_type" value="Non-Teaching"
                                            onchange="toggleStaffCategoryFields()"> Non-Teaching Staff</label>
                                </div>
                                <div class="form-helper">Select staff association type</div>
                            </div>

                            <div class="form-group dynamic-field staff-teaching-field" id="staffDepartmentFieldGroup" style="display: none;">
                                <label class="form-label">Department <span>*</span></label>
                                <select class="form-select" id="staffDepartmentSelect" name="department">
                                    <option value="" disabled selected>Select Department</option>
                                </select>
                            </div>
                        </div>
                        <!-- SECTION: CONTACT DETAILS -->
                        <div class="section-divider"></div>
                        <div class="form-section-title full-width"><i class="ph-bold ph-address-book"></i> Contact
                            Details</div>

                        <!-- ISOLATED ROW 2: Email (Left) | Contact (Right) -->
                        <div class="paired-row">
                            <!-- Email -->
                            <div class="form-group">
                                <label class="form-label">Email Address <span>*</span></label>
                                <input type="email" name="email" class="form-input" placeholder="you@domain.edu"
                                    value="{{ old('email') }}" required>
                                <div class="form-helper">Enter valid email for confirmation</div>
                            </div>

                            <!-- Contact -->
                            <div class="form-group">
                                <label class="form-label">Contact Number <span id="phoneRequiredAsterisk">*</span></label>
                                <input type="tel" name="phone" id="phoneInput" class="form-input" placeholder="+91 xxxxx xxxxx"
                                    value="{{ old('phone') }}" required>
                                <div class="form-helper">For booking updates</div>
                            </div>
                        </div>

                        <div class="form-group full-width">
                            <label class="form-label">Primary Guest Name</label>
                            <input type="text" name="primary_guest_name" class="form-input"
                                placeholder="Guest full name (Leave blank if self)">
                            <div class="form-helper">Enter the name of the guest staying if different from applicant
                            </div>
                        </div>

                        <div class="form-group full-width">
                            <label class="form-label">Number of Persons <span>*</span></label>
                            <input type="number" name="no_of_persons" id="noOfPersonsInput" min="1" max="{{ $maxCapacity }}" class="form-input"
                                placeholder="e.g. 2 (Maximum: {{ $maxCapacity }} persons)" required
                                oninput="enforceMaxCapacity(this, {{ $maxCapacity }})"
                                onblur="if(this.value === '' || parseInt(this.value, 10) < 1) this.value = 1;">
                            <div class="form-helper">Maximum capacity for this room is {{ $maxCapacity }} {{ Str::plural('person', $maxCapacity) }}</div>
                        </div>

                        <!-- SECTION: STAY DETAILS -->
                        <div class="section-divider"></div>
                        <div class="section-header-flex">
                            <div class="form-section-title">
                                <i class="ph-bold ph-calendar-check"></i> Booking Details
                            </div>
                            <span class="gst-badge"><i class="ph-bold ph-info" style="margin-right: 5px;"></i> + {{ $gstRate }}% GST applicable on all room rates</span>
                        </div>

                        <!-- ISOLATED ROW 5: Check-In (Left) | Check-Out (Right) -->
                        <div class="paired-row">
                            <div class="form-group">
                                <label class="form-label">Check-In Date & Time <span>*</span></label>
                                <input type="datetime-local" name="clock_in" class="form-input" value="{{ old('clock_in') }}" required>
                                <div class="form-helper">Select your intended arrival</div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Check-Out Date & Time <span>*</span></label>
                                <input type="datetime-local" name="clock_out" class="form-input" value="{{ old('clock_out') }}" required>
                                <div class="form-helper">Select your intended departure</div>
                            </div>
                        </div>

                        <!-- Purpose -->
                        <div class="form-group full-width" style="margin-top: 0.5rem;">
                            <label class="form-label">Purpose <span>*</span></label>
                            <textarea name="booking_reason" class="form-input" style="height: auto; min-height: 80px; padding: 0.75rem 1rem; resize: vertical;" placeholder="Briefly explain the purpose of this booking (e.g. guest lecture, official visit, internship, academic conference)" required>{{ old('booking_reason') }}</textarea>
                            <div class="form-helper">Purpose for reserving the accommodation</div>
                        </div>

                        <!-- Referral Attachment -->
                        <div class="form-group full-width" style="margin-top: 0.5rem;">
                            <label class="form-label">Referral Attachment <span style="font-weight: 400; color: #94a3b8; font-size: 0.8rem;">(Optional)</span></label>
                            <div id="fileUploadZone" class="custom-file-zone" onclick="document.getElementById('referralFileInput').click()">
                                <div class="file-icon-box">
                                    <i class="ph-bold ph-paperclip"></i>
                                </div>
                                <div class="file-text-group">
                                    <div id="fileNameDisplay" class="file-main-text">Click to attach a referral document</div>
                                    <div class="file-sub-text">PDF, DOC, JPG, PNG supported</div>
                                </div>
                                <div class="browse-btn-mobile" style="flex-shrink: 0; background: var(--primary-color); color: #fff; padding: 7px 16px; border-radius: 8px; font-size: 0.8rem; font-weight: 700; letter-spacing: 0.3px;">Browse</div>
                            </div>
                            <input type="file" id="referralFileInput" name="referral_attachment" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" style="display: none;" onchange="updateFileName(this)">
                            <div class="form-helper" style="margin-top: 6px;">Upload a referral document if applicable (PDF, Image, etc.)</div>
                        </div>
                        <script>
                            function updateFileName(input) {
                                const display = document.getElementById('fileNameDisplay');
                                if (input.files && input.files[0]) {
                                    display.textContent = input.files[0].name;
                                    display.style.color = 'var(--primary-color)';
                                } else {
                                    display.textContent = 'Click to attach a referral document';
                                    display.style.color = '#475569';
                                }
                            }
                        </script>

                        <!-- Submit -->
                        <div class="form-group full-width" style="margin-top: 1rem;">
                            <button type="submit" class="submit-btn confirm-booking-btn">CONFIRM BOOKING <i class="ph-bold ph-arrow-right"></i></button>
                        </div>
                    </div>
                </form>
            </div><!-- /.form-container -->
            </div>

            <!-- Right Column: Booking Summary -->
            <div class="booking-summary-column">
                <div class="booking-summary-card">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                        <h4 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 6px;">
                            <i class="ph-bold ph-shopping-bag-open" style="color: var(--primary-color, #850f0f);"></i> Selected Rooms (<span id="summaryCartCount">1</span>)
                        </h4>
                        <a href="{{ route('standard.rooms') }}" class="btn btn-outline" style="padding: 4px 10px; font-size: 0.75rem; border-radius: 6px; text-decoration: none;">
                            + Add Room
                        </a>
                    </div>
                    
                    <div id="selectedRoomsListContainer" style="display: flex; flex-direction: column; gap: 10px; max-height: 320px; overflow-y: auto; padding-right: 4px;">
                        <!-- Dynamic Selected Rooms rendered via JS -->
                    </div>
                    
                    <div class="summary-divider"></div>
                    
                    <div class="summary-detail-row">
                        <span class="label">Total Max Capacity:</span>
                        <span class="value" id="summaryTotalCapacityVal">{{ $room['capacity'] }}</span>
                    </div>
                    
                    <div class="summary-divider"></div>
                    
                    <div class="summary-calculator-box">
                        <div class="calculator-title">
                            <i class="ph-bold ph-calculator" style="color: #850f0f; font-size: 13px;"></i>
                            Live Price Estimator
                        </div>
                        
                        <div class="calc-row">
                            <span class="lbl">Stay Duration:</span>
                            <span class="val" id="summaryDurationVal">—</span>
                        </div>
                        
                        <div class="calc-row">
                            <span class="lbl">Subtotal:</span>
                            <span class="val" id="summarySubtotalVal">—</span>
                        </div>
                        
                        <div class="calc-row">
                            <span class="lbl">GST Tax ({{ $gstRate }}%):</span>
                            <span class="val" id="summaryGstVal">—</span>
                        </div>
                        
                        <div class="calc-row total">
                            <span class="lbl">Estimated Total:</span>
                            <span class="val" id="summaryTotalVal">—</span>
                        </div>
                    </div>
                </div>
            </div>
            </div> <!-- /.booking-layout-grid -->
        </div> <!-- /.booking-page-container -->
    </main>
    @include('partials.footer')

    <script>
        function toggleStudentFields() {
            const userType = document.getElementById('userTypeSelect').value;
            const studentFields = document.querySelectorAll('.student-field');
            const staffFields = document.querySelectorAll('.staff-field');

            studentFields.forEach(field => {
                if (userType === 'Student') {
                    field.classList.add('show');
                    field.style.setProperty('display', 'flex', 'important');
                    // Add required securely to dynamic inputs, explicitly excluding "Other" handler
                    const inputs = field.querySelectorAll('input:not(#otherDepartmentInput), select');
                    inputs.forEach(input => input.setAttribute('required', 'true'));
                } else {
                    field.classList.remove('show');
                    field.style.setProperty('display', 'none', 'important');
                    // Remove required
                    const inputs = field.querySelectorAll('input, select');
                    inputs.forEach(input => input.removeAttribute('required'));
                }
            });

            // Staff logic
            staffFields.forEach(field => {
                if (userType === 'Staff') {
                    field.classList.add('show');
                    field.style.setProperty('display', 'flex', 'important');
                    const inputs = field.querySelectorAll('input, select');
                    inputs.forEach(input => input.setAttribute('required', 'true'));
                } else {
                    field.classList.remove('show');
                    field.style.setProperty('display', 'none', 'important');
                    const inputs = field.querySelectorAll('input, select');
                    inputs.forEach(input => input.removeAttribute('required'));
                }
            });

            // If not staff, hide the staff department section too
            if (userType !== 'Staff') {
                const staffDeptField = document.getElementById('staffDepartmentFieldGroup');
                if (staffDeptField) {
                    staffDeptField.style.setProperty('display', 'none', 'important');
                    staffDeptField.classList.remove('show');
                }
                const staffDeptSelect = document.getElementById('staffDepartmentSelect');
                if (staffDeptSelect) staffDeptSelect.removeAttribute('required');
                // Uncheck radio buttons
                const radios = document.querySelectorAll('input[name="staff_type"]');
                radios.forEach(r => r.checked = false);
            }

            // Refresh Other Dept and Level logic on toggle
            if (userType === 'Student') {
                toggleOtherDepartment();
                handleLevelChange();
            }
        }
        function toggleStaffCategoryFields() {
            const staffType = document.querySelector('input[name="staff_type"]:checked');
            const staffDeptField = document.getElementById('staffDepartmentFieldGroup');
            const staffDeptSelect = document.getElementById('staffDepartmentSelect');

            if (staffType && staffType.value === 'Teaching') {
                if (staffDeptField) {
                    staffDeptField.classList.add('show');
                    staffDeptField.style.setProperty('display', 'flex', 'important');
                }
                if (staffDeptSelect) {
                    staffDeptSelect.setAttribute('required', 'true');
                    
                    // Populate departments combining aided and sfs departments
                    staffDeptSelect.innerHTML = '<option value="" disabled selected>Select Department</option>';
                    const allDepts = [...new Set([...aidedDepartments, ...sfsDepartments])].sort();
                    allDepts.forEach(dept => {
                        let opt = document.createElement('option');
                        opt.value = dept;
                        opt.innerHTML = dept;
                        staffDeptSelect.appendChild(opt);
                    });
                }
            } else {
                if (staffDeptField) {
                    staffDeptField.classList.remove('show');
                    staffDeptField.style.setProperty('display', 'none', 'important');
                }
                if (staffDeptSelect) {
                    staffDeptSelect.removeAttribute('required');
                    staffDeptSelect.value = '';
                }
            }
        }
        const aidedDepartments = [
            "English", "Tamil", "Languages", "History", "Political Science",
            "Public Administration", "Economics", "Philosophy", "Commerce",
            "Social Work", "Mathematics", "Statistics", "Physics", "Chemistry",
            "Botany", "Zoology", "Physical Education"
        ];

        const sfsDepartments = [
            "English", "Tamil", "Languages", "Journalism", "Social Work",
            "Commerce", "Business Administration", "Communication", "Geography",
            "Tourism Studies", "Mathematics", "Physics", "Chemistry", "Microbiology",
            "Computer Application (BCA)", "Computer Science (B.Sc)",
            "Computer Science (MCA)", "Visual Communication",
            "Physical Education, Health Education and Sports", "Psychology", "Data Science"
        ];

        const researchDepartments = [
            "Botany", "Chemistry", "Commerce", "Economics", "English", "History",
            "Mathematics", "Philosophy", "Physics", "Political Science",
            "Public Administration", "Social Work", "Statistics", "Tamil", "Zoology",
            "Computer Science", "Microbiology"
        ];

        function handleLevelChange() {
            const levelSelect = document.getElementById('levelSelect');
            if (!levelSelect) return;
            const level = levelSelect.value;
            const streamFieldGroup = document.getElementById('streamFieldGroup');
            const streamInputs = streamFieldGroup ? streamFieldGroup.querySelectorAll('input[type="radio"]') : [];
            const deptSelect = document.getElementById('departmentSelect');
            const userType = document.getElementById('userTypeSelect').value;

            if (level === 'MPhil' || level === 'PhD') {
                // Hide Stream field group for research levels
                if (streamFieldGroup) {
                    streamFieldGroup.style.setProperty('display', 'none', 'important');
                    streamInputs.forEach(input => input.removeAttribute('required'));
                }
                
                // Hide Department field group for research levels
                const deptFieldGroup = document.getElementById('departmentFieldGroup');
                if (deptFieldGroup) {
                    deptFieldGroup.style.setProperty('display', 'none', 'important');
                }
                if (deptSelect) {
                    deptSelect.removeAttribute('required');
                    deptSelect.value = '';
                }
                
                toggleOtherDepartment();
            } else {
                // Show Stream field group for UG / PG if student
                if (streamFieldGroup) {
                    if (userType === 'Student') {
                        streamFieldGroup.style.display = '';
                        streamInputs.forEach(input => input.setAttribute('required', 'true'));
                    } else {
                        streamFieldGroup.style.display = 'none';
                        streamInputs.forEach(input => input.removeAttribute('required'));
                    }
                }

                // Show Department field group
                const deptFieldGroup = document.getElementById('departmentFieldGroup');
                if (deptFieldGroup) {
                    deptFieldGroup.style.display = '';
                }
                if (deptSelect && userType === 'Student') {
                    deptSelect.setAttribute('required', 'true');
                }

                // Reset department options based on currently selected stream
                // Reset department options based on currently selected stream
                const selectedStream = document.querySelector('input[name="stream"]:checked');
                if (deptSelect) {
                    if (selectedStream) {
                        updateDepartments(selectedStream.value);
                    } else {
                        deptSelect.innerHTML = '<option value="" disabled selected>Select Stream First</option>';
                    }
                }
            }
        }

        function updateDepartments(stream) {
            const deptSelect = document.getElementById('departmentSelect');
            const levelSelect = document.getElementById('levelSelect');
            const level = levelSelect ? levelSelect.value : '';

            // Clean slate -> Resets selection smoothly on change
            deptSelect.innerHTML = '<option value="" disabled selected>Select Department</option>';

            let options = [];
            if (level === 'MPhil' || level === 'PhD') {
                options = researchDepartments;
            } else {
                options = stream === 'Aided' ? aidedDepartments : sfsDepartments;
            }

            options.forEach(dept => {
                let opt = document.createElement('option');
                opt.value = dept;
                opt.innerHTML = dept;
                deptSelect.appendChild(opt);
            });

            // Attach "Other"
            let otherOpt = document.createElement('option');
            otherOpt.value = 'Other';
            otherOpt.innerHTML = 'Other';
            deptSelect.appendChild(otherOpt);

            // Trigger cleanly
            toggleOtherDepartment();
        }

        function toggleOtherDepartment() {
            const deptSelect = document.getElementById('departmentSelect');
            const otherWrapper = document.getElementById('otherDepartmentWrapper');
            const otherInput = document.getElementById('otherDepartmentInput');
            const isStudent = document.getElementById('userTypeSelect').value === 'Student';

            if (deptSelect && deptSelect.value === 'Other' && isStudent) {
                otherWrapper.style.display = 'block';
                otherWrapper.style.maxHeight = '100px';
                otherInput.setAttribute('required', 'true');
            } else {
                if (otherWrapper) {
                    otherWrapper.style.display = 'none';
                    otherWrapper.style.maxHeight = '0';
                }
                if (otherInput) {
                    otherInput.removeAttribute('required');
                }
            }
        }

        // ISSUE 2: Date selection allows past dates
        document.addEventListener('DOMContentLoaded', function () {
            const now = new Date();
            const today = now.toISOString().split('T')[0];
            const todayDateTime = now.toISOString().slice(0, 16);

            document.querySelectorAll('input[type="date"]').forEach(input => {
                input.setAttribute('min', today);
            });

            document.querySelectorAll('input[type="datetime-local"]').forEach(input => {
                input.setAttribute('min', todayDateTime);
            });
        });

        function toggleNationalityFields() {
            const nonIndianRadio = document.querySelector('input[name="nationality"][value="Non-Indian"]');
            const isNonIndian = nonIndianRadio ? nonIndianRadio.checked : false;
            const nonIndianFields = document.querySelectorAll('.non-indian-field');
            const passportInput = document.getElementById('passportInput');
            const visaInput = document.getElementById('visaInput');
            const passportFileInput = document.getElementById('passportFileInput');
            const visaFileInput = document.getElementById('visaFileInput');
            const passportVisaFileInput = document.getElementById('passportVisaFileInput');
            const phoneInput = document.getElementById('phoneInput');
            const phoneAsterisk = document.getElementById('phoneRequiredAsterisk');

            nonIndianFields.forEach(field => {
                if (isNonIndian) {
                    field.classList.add('show');
                    if (field.classList.contains('paired-row')) {
                        field.style.setProperty('display', 'grid', 'important');
                    } else {
                        field.style.setProperty('display', 'flex', 'important');
                    }
                } else {
                    field.classList.remove('show');
                    field.style.setProperty('display', 'none', 'important');
                }
            });

            if (isNonIndian) {
                if (passportInput) passportInput.setAttribute('required', 'true');
                if (visaInput) visaInput.setAttribute('required', 'true');
                if (passportFileInput) passportFileInput.setAttribute('required', 'true');
                if (visaFileInput) visaFileInput.setAttribute('required', 'true');
                if (passportVisaFileInput) passportVisaFileInput.setAttribute('required', 'true');
                if (phoneInput) phoneInput.removeAttribute('required');
                if (phoneAsterisk) phoneAsterisk.style.display = 'none';
            } else {
                if (passportInput) passportInput.removeAttribute('required');
                if (visaInput) visaInput.removeAttribute('required');
                if (passportFileInput) passportFileInput.removeAttribute('required');
                if (visaFileInput) visaFileInput.removeAttribute('required');
                if (passportVisaFileInput) passportVisaFileInput.removeAttribute('required');
                if (phoneInput) phoneInput.setAttribute('required', 'true');
                if (phoneAsterisk) phoneAsterisk.style.display = '';
            }
        }

        // Live Estimator & Cart Sync Logic
        const gstRate = {{ $gstRate }};

        function syncBookingFormWithCart() {
            if (!window.IGHCart) return;

            // 1. Sync from URL if present
            const urlParams = new URLSearchParams(window.location.search);
            const roomsParam = urlParams.get('rooms');
            const singleRoomParam = urlParams.get('room');

            if (roomsParam) {
                const roomNames = roomsParam.split(',').map(s => s.trim()).filter(Boolean);
                if (roomNames.length > 0) {
                    // Check if cart already has these
                    const currentCart = window.IGHCart.getItems();
                    if (currentCart.length === 0) {
                        roomNames.forEach(rName => {
                            window.IGHCart.addItem({
                                id: rName.toLowerCase().replace(/\s+/g, '-'),
                                name: rName,
                                category: rName.toLowerCase().includes('standard') ? 'Standard Room' : (rName.toLowerCase().includes('advance') || !isNaN(rName) ? 'Advance Room' : 'Special Facility'),
                                price: rName.toLowerCase().includes('standard') ? '1400' : (rName.toLowerCase().includes('advance') || !isNaN(rName) ? '2500' : '2000'),
                                capacity: rName.toLowerCase().includes('standard') ? 2 : (rName.toLowerCase().includes('advance') || !isNaN(rName) ? 4 : 20)
                            });
                        });
                    }
                }
            } else if (singleRoomParam && window.IGHCart.getItems().length === 0) {
                window.IGHCart.addItem({
                    id: singleRoomParam.toLowerCase().replace(/\s+/g, '-'),
                    name: singleRoomParam,
                    category: singleRoomParam.toLowerCase().includes('standard') ? 'Standard Room' : (singleRoomParam.toLowerCase().includes('advance') || !isNaN(singleRoomParam) ? 'Advance Room' : 'Special Facility'),
                    price: singleRoomParam.toLowerCase().includes('standard') ? '1400' : (singleRoomParam.toLowerCase().includes('advance') || !isNaN(singleRoomParam) ? '2500' : '2000'),
                    capacity: singleRoomParam.toLowerCase().includes('standard') ? 2 : (singleRoomParam.toLowerCase().includes('advance') || !isNaN(singleRoomParam) ? 4 : 20)
                });
            }

            let cartItems = window.IGHCart.getItems();

            // Fallback if empty
            if (cartItems.length === 0) {
                cartItems = [{
                    id: '{{ $roomId }}',
                    name: '{{ $room["name"] }}',
                    category: '{{ $room["category"] }}',
                    price: '{{ $room["price"] }}',
                    capacity: {{ $maxCapacity }}
                }];
            }

            // 2. Set hidden input value
            const roomNamesStr = cartItems.map(i => i.name).join(', ');
            const hiddenInput = document.getElementById('hiddenRoomNameInput');
            if (hiddenInput) hiddenInput.value = roomNamesStr;

            // 3. Update Hero Title
            const heroTitle = document.getElementById('heroRoomTitle');
            if (heroTitle) heroTitle.textContent = roomNamesStr;

            // 4. Update Summary Cart Count
            const summaryCartCount = document.getElementById('summaryCartCount');
            if (summaryCartCount) summaryCartCount.textContent = cartItems.length;

            // 5. Render Selected Rooms List Container
            const listContainer = document.getElementById('selectedRoomsListContainer');
            if (listContainer) {
                let html = '';
                cartItems.forEach((item) => {
                    html += `
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 12px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.85rem;">
                            <div style="min-width: 0;">
                                <div style="font-weight: 700; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${item.name}</div>
                                <div style="font-size: 0.75rem; color: #64748b;">${item.category} • Max ${item.capacity || 2} guests</div>
                            </div>
                            ${cartItems.length > 1 ? `
                                <button type="button" onclick="window.IGHCart.removeItem('${item.name}')" style="background: none; border: none; color: #ef4444; cursor: pointer; padding: 4px; font-size: 1rem; flex-shrink: 0;" title="Remove Room">
                                    <i class="ph-bold ph-x"></i>
                                </button>
                            ` : ''}
                        </div>
                    `;
                });
                listContainer.innerHTML = html;
            }

            // 6. Update Combined Max Capacity
            const totalCap = cartItems.reduce((acc, i) => acc + (parseInt(i.capacity, 10) || 2), 0);
            const capValEl = document.getElementById('summaryTotalCapacityVal');
            if (capValEl) capValEl.textContent = `${totalCap} Members`;

            const noOfPersonsInput = document.getElementById('noOfPersonsInput');
            if (noOfPersonsInput) {
                noOfPersonsInput.max = totalCap;
                noOfPersonsInput.placeholder = `e.g. 2 (Maximum: ${totalCap} persons)`;
                noOfPersonsInput.setAttribute('oninput', `enforceMaxCapacity(this, ${totalCap})`);
                const helperEl = noOfPersonsInput.nextElementSibling;
                if (helperEl && helperEl.classList.contains('form-helper')) {
                    helperEl.textContent = `Maximum capacity for selected rooms is ${totalCap} persons`;
                }
            }

            calculateEstimatedPrice();
        }

        function calculateEstimatedPrice() {
            const clockInVal = document.querySelector('input[name="clock_in"]').value;
            const clockOutVal = document.querySelector('input[name="clock_out"]').value;
            
            const durationEl = document.getElementById('summaryDurationVal');
            const subtotalEl = document.getElementById('summarySubtotalVal');
            const gstEl = document.getElementById('summaryGstVal');
            const totalEl = document.getElementById('summaryTotalVal');
            
            if (!clockInVal || !clockOutVal) {
                if (durationEl) durationEl.textContent = '—';
                if (subtotalEl) subtotalEl.textContent = '—';
                if (gstEl) gstEl.textContent = '—';
                if (totalEl) totalEl.textContent = '—';
                return;
            }
            
            const inDate = new Date(clockInVal);
            const outDate = new Date(clockOutVal);
            
            const diffMs = outDate - inDate;
            if (diffMs <= 0) {
                if (durationEl) durationEl.textContent = 'Invalid Dates';
                if (subtotalEl) subtotalEl.textContent = '₹0.00';
                if (gstEl) gstEl.textContent = '₹0.00';
                if (totalEl) totalEl.textContent = '₹0.00';
                return;
            }
            
            const durationMinutes = diffMs / (1000 * 60);
            const durationHours = durationMinutes / 60.0;
            
            const cartItems = (window.IGHCart ? window.IGHCart.getItems() : []);
            const itemsToCalc = cartItems.length > 0 ? cartItems : [{
                name: '{{ $room["name"] }}',
                category: '{{ $room["category"] }}',
                price: '{{ $room["price"] }}',
                rateType: '{{ $room["time"] }}'
            }];

            let subtotal = 0;
            itemsToCalc.forEach(item => {
                const rName = (item.name || '').toLowerCase();
                const rCategory = (item.category || '').toLowerCase();
                const rRateType = (item.rateType || '').toLowerCase();
                
                if (rRateType.includes('day') || rCategory.includes('executive') || rCategory.includes('advance') || !isNaN(item.name)) {
                    const days = Math.max(1, Math.ceil(durationHours / 24.0));
                    subtotal += days * 2500;
                } else if (rRateType.includes('12') || rName.includes('standard')) {
                    const blocks = Math.max(1, Math.ceil(durationHours / 12.0));
                    subtotal += blocks * 1400;
                } else if (rRateType.includes('4') || rCategory.includes('conference') || rName.includes('conference') || rName.includes('glass') || rName.includes('suite')) {
                    const billableHours = Math.max(4, Math.ceil(durationHours));
                    subtotal += billableHours * 500;
                } else {
                    subtotal += (parseFloat(item.price) || 2000);
                }
            });

            const gst = subtotal * (gstRate / 100);
            const total = subtotal + gst;

            let durationDisplay = '';
            if (durationHours >= 24) {
                const d = (durationHours / 24).toFixed(1);
                durationDisplay = `${d} ${d === '1.0' ? 'Day' : 'Days'}`;
            } else {
                const h = durationHours.toFixed(1);
                durationDisplay = `${h} ${h === '1.0' ? 'Hour' : 'Hours'}`;
            }
            
            if (durationEl) durationEl.textContent = durationDisplay;
            if (subtotalEl) subtotalEl.textContent = `₹${subtotal.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
            if (gstEl) gstEl.textContent = `₹${gst.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
            if (totalEl) totalEl.textContent = `₹${total.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
        }

        function enforceMaxCapacity(input, maxCap) {
            if (input.value === '') return;
            let val = parseInt(input.value, 10);
            if (isNaN(val)) {
                input.value = '';
                return;
            }
            if (val > maxCap) {
                input.value = maxCap;
            } else if (val < 1) {
                input.value = 1;
            }
        }

        function syncClockOutMin() {
            const clockInEl = document.querySelector('input[name="clock_in"]');
            const clockOutEl = document.querySelector('input[name="clock_out"]');
            
            if (clockInEl && clockOutEl && clockInEl.value) {
                clockOutEl.min = clockInEl.value;
                if (!clockOutEl.value || clockOutEl.value <= clockInEl.value) {
                    const inDate = new Date(clockInEl.value);
                    if (!isNaN(inDate.getTime())) {
                        const defaultOut = new Date(inDate.getTime() + 12 * 60 * 60 * 1000);
                        const year = defaultOut.getFullYear();
                        const month = String(defaultOut.getMonth() + 1).padStart(2, '0');
                        const day = String(defaultOut.getDate()).padStart(2, '0');
                        const hours = String(defaultOut.getHours()).padStart(2, '0');
                        const mins = String(defaultOut.getMinutes()).padStart(2, '0');
                        clockOutEl.value = `${year}-${month}-${day}T${hours}:${mins}`;
                    }
                }
            }
        }

        // Listen for Cart updates
        window.addEventListener('ighCartUpdated', function () {
            syncBookingFormWithCart();
        });

        // Initialize state natively on load to prevent glitch rendering
        document.addEventListener('DOMContentLoaded', () => {
            toggleStudentFields();
            toggleStaffCategoryFields();
            toggleNationalityFields();
            syncBookingFormWithCart();

            const form = document.querySelector('form[action="{{ route("booking.store") }}"]');
            if (form) {
                form.addEventListener('submit', (e) => {
                    const clockInEl = document.querySelector('input[name="clock_in"]');
                    const clockOutEl = document.querySelector('input[name="clock_out"]');
                    if (clockInEl && clockOutEl) {
                        const inDate = new Date(clockInEl.value);
                        const outDate = clockOutEl.value ? new Date(clockOutEl.value) : null;
                        if (isNaN(inDate.getTime()) || !outDate || isNaN(outDate.getTime()) || outDate <= inDate) {
                            e.preventDefault();
                            alert('Check-Out date and time must be strictly after Check-In date and time.');
                            clockOutEl.focus();
                            return false;
                        }
                    }
                    if (window.IGHCart) {
                        window.IGHCart.clearCart();
                    }
                });
            }

            const clockInEl = document.querySelector('input[name="clock_in"]');
            const clockOutEl = document.querySelector('input[name="clock_out"]');
            
            if (clockInEl && clockOutEl) {
                const handleClockInChange = () => {
                    syncClockOutMin();
                    calculateEstimatedPrice();
                };

                clockInEl.addEventListener('change', handleClockInChange);
                clockInEl.addEventListener('input', handleClockInChange);

                clockOutEl.addEventListener('change', calculateEstimatedPrice);
                clockOutEl.addEventListener('input', calculateEstimatedPrice);

                if (clockInEl.value && !clockOutEl.value) {
                    syncClockOutMin();
                }
            }
            
            calculateEstimatedPrice();
        });
    </script>
</body>

</html>