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
            align-items: end;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            position: relative;
            justify-content: flex-end;
            height: 100%;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .paired-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            align-items: end;
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
            display: none;
        }

        .dynamic-field.show {
            display: flex;
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
        $normalizedRoomId = strtolower($roomId);
        
        // Get GST rate from DB
        $gstRate = \App\Models\Setting::where('key', 'gst_rate')->value('value') ?? 5;
        
        // Static room data for Summary Card
        $roomsData = [
            'conference-hall' => [
                'name' => 'Conference Hall',
                'price' => 2000,
                'price_formatted' => '₹2,000',
                'time' => '/ 4 Hours',
                'capacity' => '60 Members',
                'img' => asset('assets/standard/conference.JPG'),
                'category' => 'Conference Wing'
            ],
            'conference-room' => [
                'name' => 'Conference Room',
                'price' => 1500,
                'price_formatted' => '₹1,500',
                'time' => '/ 4 Hours',
                'capacity' => '12 Members',
                'img' => asset('assets/standard/conferenceroom.JPG'),
                'category' => 'Conference Wing'
            ],
            'glass-room' => [
                'name' => 'Glass Room',
                'price' => 1500,
                'price_formatted' => '₹1,500',
                'time' => '/ 4 Hours',
                'capacity' => '20 Members',
                'img' => asset('assets/standard/glassroom.JPG'),
                'category' => 'Conference Wing'
            ],
            'suite-room' => [
                'name' => 'Luxury Suite Room',
                'price' => 4500,
                'price_formatted' => '₹4,500',
                'time' => '/ Day',
                'capacity' => '4 Members',
                'img' => asset('assets/suite.JPG'),
                'category' => 'Luxury Wing'
            ]
        ];

        // Check for standard/advance rooms
        if (str_contains($normalizedRoomId, 'standard')) {
            $room = [
                'name' => 'Standard Guest Room',
                'price' => 1400,
                'price_formatted' => '₹1,400',
                'time' => '/ 12 Hours',
                'capacity' => '2 Members',
                'img' => asset('assets/standard/standardroom.JPG'),
                'category' => 'Guest Wing'
            ];
            $maxCapacity = 2;
        } elseif (str_contains($normalizedRoomId, 'advance')) {
            $room = [
                'name' => 'Advance Executive Room',
                'price' => 2500,
                'price_formatted' => '₹2,500',
                'time' => '/ Day',
                'capacity' => '4 Members',
                'img' => asset('assets/room1.JPG'),
                'category' => 'Executive Wing'
            ];
            $maxCapacity = 4;
        } else {
            // Fallback using matched data or general values
            $key = isset($roomsData[$normalizedRoomId]) ? $normalizedRoomId : 'conference-hall';
            $room = $roomsData[$key];
            
            if (str_contains($normalizedRoomId, 'conference-hall') || str_contains($normalizedRoomId, 'conference-room')) {
                $maxCapacity = 60;
            } elseif (str_contains($normalizedRoomId, 'glass-room')) {
                $maxCapacity = 20;
            } else {
                $maxCapacity = 4;
            }
        }
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
                <p>Secure your accommodation efficiently for <strong>{{ $room['name'] }}</strong></p>
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
                            <input type="hidden" name="room_name" value="{{ $roomId }}">

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

                        <!-- SECTION: CONTACT & GUEST -->
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
                                <label class="form-label">Contact Number <span>*</span></label>
                                <input type="tel" name="phone" class="form-input" placeholder="+91 xxxxx xxxxx"
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
                            <input type="number" name="no_of_persons" min="1" max="{{ $maxCapacity }}" class="form-input"
                                placeholder="e.g. 2 (Maximum: {{ $maxCapacity }} persons)" required>
                            <div class="form-helper">Maximum capacity for this room is {{ $maxCapacity }} {{ Str::plural('person', $maxCapacity) }}</div>
                        </div>

                        <!-- DYNAMIC: Non-Indian Fields -->
                        <div class="form-group dynamic-field non-indian-field full-width" id="passportFieldGroup"
                            style="grid-column: 1/-1;">
                            <label class="form-label">Passport Number <span>*</span></label>
                            <input type="text" name="passport_number" class="form-input"
                                placeholder="Required for Non-Indian guests" id="passportInput" value="{{ old('passport_number') }}">
                        </div>

                        <div class="form-group dynamic-field non-indian-field full-width" id="visaFieldGroup"
                            style="grid-column: 1/-1;">
                            <label class="form-label">Visa Number <span>*</span></label>
                            <input type="text" name="visa_number" class="form-input"
                                placeholder="Required for Non-Indian guests" id="visaInput" value="{{ old('visa_number') }}">
                        </div>

                        <div class="form-group dynamic-field non-indian-field full-width" id="gstFieldGroup"
                            style="grid-column: 1/-1;">
                            <label class="form-label">GST Number</label>
                            <input type="text" name="gst_id" class="form-input"
                                placeholder="If applicable for corporate booking (Optional)">
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

                        <!-- Reason for Booking -->
                        <div class="form-group full-width" style="margin-top: 0.5rem;">
                            <label class="form-label">Reason for Booking <span>*</span></label>
                            <textarea name="booking_reason" class="form-input" style="height: auto; min-height: 80px; padding: 0.75rem 1rem; resize: vertical;" placeholder="Briefly explain the purpose of this booking (e.g. guest lecture, official visit, internship, academic conference)" required>{{ old('booking_reason') }}</textarea>
                            <div class="form-helper">Purpose/Reason for reserving the accommodation</div>
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
                    <span class="summary-room-badge">{{ $room['category'] }}</span>
                    <img src="{{ $room['img'] }}" alt="{{ $room['name'] }}" class="summary-room-img">
                    
                    <h3 class="summary-room-title" style="margin-top: 16px;">{{ $room['name'] }}</h3>
                    
                    <div class="summary-divider"></div>
                    
                    <div class="summary-detail-row">
                        <span class="label">Max Capacity:</span>
                        <span class="value">{{ $room['capacity'] }}</span>
                    </div>
                    <div class="summary-detail-row">
                        <span class="label">Wing Location:</span>
                        <span class="value">{{ $room['category'] }}</span>
                    </div>
                    <div class="summary-detail-row">
                        <span class="label">Pricing Tier:</span>
                        <span class="value">{{ $room['price_formatted'] }} {{ $room['time'] }}</span>
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

            studentFields.forEach(field => {
                if (userType === 'Student') {
                    field.classList.add('show');
                    field.style.display = ''; // Reset display style
                    // Add required securely to dynamic inputs, explicitly excluding "Other" handler
                    const inputs = field.querySelectorAll('input:not(#otherDepartmentInput), select');
                    inputs.forEach(input => input.setAttribute('required', 'true'));
                } else {
                    field.classList.remove('show');
                    field.style.display = 'none'; // Hide completely
                    // Remove required
                    const inputs = field.querySelectorAll('input, select');
                    inputs.forEach(input => input.removeAttribute('required'));
                }
            });

            // Refresh Other Dept and Level logic on toggle
            if (userType === 'Student') {
                toggleOtherDepartment();
                handleLevelChange();
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
                
                // Populate departments directly with research departments
                if (deptSelect) {
                    deptSelect.innerHTML = '<option value="" disabled selected>Select Department</option>';
                    researchDepartments.forEach(dept => {
                        let opt = document.createElement('option');
                        opt.value = dept;
                        opt.innerHTML = dept;
                        deptSelect.appendChild(opt);
                    });
                    
                    // Attach "Other" option
                    let otherOpt = document.createElement('option');
                    otherOpt.value = 'Other';
                    otherOpt.innerHTML = 'Other';
                    deptSelect.appendChild(otherOpt);
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
            const isNonIndian = document.querySelector('input[name="nationality"][value="Non-Indian"]').checked;
            const nonIndianFields = document.querySelectorAll('.non-indian-field');
            const passportInput = document.getElementById('passportInput');
            const visaInput = document.getElementById('visaInput');

            nonIndianFields.forEach(field => {
                if (isNonIndian) {
                    field.classList.add('show');
                } else {
                    field.classList.remove('show');
                }
            });

            if (isNonIndian) {
                if (passportInput) passportInput.setAttribute('required', 'true');
                if (visaInput) visaInput.setAttribute('required', 'true');
            } else {
                if (passportInput) passportInput.removeAttribute('required');
                if (visaInput) visaInput.removeAttribute('required');
            }
        }

        // Live Estimator Calculator Logic
        const basePrice = {{ $room['price'] }};
        const gstRate = {{ $gstRate }};
        const rateType = "{{ $room['time'] }}";

        function calculateEstimatedPrice() {
            const clockInVal = document.querySelector('input[name="clock_in"]').value;
            const clockOutVal = document.querySelector('input[name="clock_out"]').value;
            
            const durationEl = document.getElementById('summaryDurationVal');
            const subtotalEl = document.getElementById('summarySubtotalVal');
            const gstEl = document.getElementById('summaryGstVal');
            const totalEl = document.getElementById('summaryTotalVal');
            
            if (!clockInVal || !clockOutVal) {
                durationEl.textContent = '—';
                subtotalEl.textContent = '—';
                gstEl.textContent = '—';
                totalEl.textContent = '—';
                return;
            }
            
            const inDate = new Date(clockInVal);
            const outDate = new Date(clockOutVal);
            
            const diffMs = outDate - inDate;
            if (diffMs <= 0) {
                durationEl.textContent = 'Invalid Dates';
                subtotalEl.textContent = '₹0.00';
                gstEl.textContent = '₹0.00';
                totalEl.textContent = '₹0.00';
                return;
            }
            
            let duration = 1;
            let durationUnit = "";
            
            if (rateType.includes('Day')) {
                duration = Math.max(1, Math.ceil(diffMs / (1000 * 60 * 60 * 24)));
                durationUnit = duration === 1 ? 'Day' : 'Days';
            } else if (rateType.includes('12')) {
                duration = Math.max(1, Math.ceil(diffMs / (1000 * 60 * 60 * 12)));
                durationUnit = duration === 1 ? '12-Hr Interval' : '12-Hr Intervals';
            } else if (rateType.includes('4')) {
                duration = Math.max(1, Math.ceil(diffMs / (1000 * 60 * 60 * 4)));
                durationUnit = duration === 1 ? '4-Hr Interval' : '4-Hr Intervals';
            }
            
            const subtotal = basePrice * duration;
            const gst = subtotal * (gstRate / 100);
            const total = subtotal + gst;
            
            durationEl.textContent = `${duration} ${durationUnit}`;
            subtotalEl.textContent = `₹${subtotal.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
            gstEl.textContent = `₹${gst.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
            totalEl.textContent = `₹${total.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
        }

        // Initialize state natively on load to prevent glitch rendering
        document.addEventListener('DOMContentLoaded', () => {
            toggleStudentFields();
            toggleNationalityFields();

            const clockInEl = document.querySelector('input[name="clock_in"]');
            const clockOutEl = document.querySelector('input[name="clock_out"]');
            
            if (clockInEl && clockOutEl) {
                clockInEl.addEventListener('change', calculateEstimatedPrice);
                clockOutEl.addEventListener('change', calculateEstimatedPrice);
            }
            
            calculateEstimatedPrice();
        });
    </script>
</body>

</html>