<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MCC IGH</title>
    <!-- Modern Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Phosphor Icons for Modern Aesthetics -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <style>
        /* =============================================================
           GLOBAL RESET & BASE
        ============================================================= */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { width: 100%; overflow-x: hidden; }

        /* =============================================================
           HERO SLIDER OVERLAYS & NAV (Base is in responsive.css)
        ============================================================= */
        .hero-prev, .hero-next {
            position: absolute; top: 50%; transform: translateY(-50%);
            width: 50px; height: 50px; background: #fff;
            border: 1px solid #eee;
            border-radius: 50%; color: var(--primary-color); font-size: 1.5rem; cursor: pointer;
            z-index: 10; display: flex; align-items: center; justify-content: center;
            transition: all 0.2s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        .hero-prev { left: 30px; }
        .hero-next { right: 30px; }
        .hero-prev:hover, .hero-next:hover { 
            background: #fff; 
            color: var(--primary-color);
            border-color: var(--primary-color);
            transform: translateY(-50%) scale(1.08); 
            box-shadow: 0 8px 25px rgba(255,122,0,0.25);
        }

        .hero-dot {
            background-color: rgba(255, 255, 255, 0.4) !important;
        }

        .hero-dot.active {
            background-color: var(--primary-color) !important;
        }

        /* =============================================================
           HERO WELCOME SECTION (Base is in responsive.css)
        ============================================================= */
        .hero-section { text-align: center; padding: 1rem 1.5rem 0.5rem; background: #f8fafc; }
        .welcome-title {
            font-size: 3rem;
            font-weight: 800;
            color: #1e293b;
            letter-spacing: -1px;
            position: relative;
            display: inline-block;
            margin-bottom: 8px;
        }
        /* Design Accent Touch */
        .welcome-title::after {
            content: '';
            display: inline-block;
            width: 8px;
            height: 8px;
            background: var(--primary-color, #850f0f);
            border-radius: 50%;
            margin-left: 6px;
            vertical-align: super;
        }
        .welcome-subtitle {
            font-size: 1.15rem;
            font-weight: 500;
            color: #64748b;
            max-width: 700px;
            margin: 1.25rem auto 0;
            line-height: 1.6;
        }

        /* =============================================================
           PREMIUM FACILITY CARD (Base is in responsive.css)
        ============================================================= */
        /*  FACILITY SECTION  */
        .premium-facility-card {
            background: linear-gradient(135deg, #fff, #fbfbfb);
            border-radius: 24px; padding: 3rem 2.5rem;
            box-shadow: 0 10px 50px rgba(0,0,0,0.04);
            border: 1px solid #f1f1f1;
            transition: all 0.5s cubic-bezier(0.165,0.84,0.44,1);
            max-width: 900px; margin: 1.5rem auto;
            opacity: 0; transform: translateY(30px);
            text-align: center;
        }
        .premium-facility-card.visible { opacity: 1; transform: translateY(0); }
        .premium-facility-card:hover { transform: translateY(-5px); box-shadow: 0 15px 60px rgba(0,0,0,0.08); }
        .facility-title {
            font-size: 2rem; font-weight: 800; color: #111;
            margin-bottom: 0.5rem; display: inline-block; letter-spacing: -0.5px;
        }
        .facility-divider {
            width: 50px; height: 3px; background: var(--primary-color);
            margin: 1.25rem auto; border-radius: 5px; opacity: 0.8;
        }
        .premium-facility-card .desc-content p {
            font-family: 'Inter', sans-serif !important;
            text-align: left !important;
            font-size: 1.15rem;
            line-height: 1.75;
            color: #475569;
            max-width: 750px;
            margin: 0 auto;
        }
        .feature-grid {
            display: grid; 
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem; 
            margin-top: 2rem;
            width: 100%;
        }
        @media (max-width: 900px) {
            .feature-grid { grid-template-columns: repeat(2, 1fr); }
            .premium-facility-card { padding: 2.5rem 1.5rem; }
        }
        @media (max-width: 650px) {
            .feature-grid { grid-template-columns: repeat(2, 1fr); gap: 0.75rem; }
            .premium-facility-card { padding: 2rem 1.25rem; margin: 1rem auto; }
            .facility-title { font-size: 1.6rem; }
            .premium-facility-card .desc-content p {
                font-family: 'Inter', sans-serif !important;
                text-align: left !important;
                font-size: 0.95rem !important;
                line-height: 1.6 !important;
            }
        }
        .feature-item {
            display: flex; align-items: center; gap: 15px;
            padding: 1.25rem 1.5rem; background: white; border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02); 
            border: 1px solid #f1f5f9;
            transition: all 0.3s ease;
            text-align: left;
        }

        /* Visibility Improvements for Room Categories */
        /* Slider Card Consistency */
        .slider-card {
            display: flex !important;
            flex-direction: column !important;
            height: auto !important;
            min-height: auto !important;
            background: #ffffff !important;
            border-radius: 20px !important;
            border: 1px solid #f1f5f9 !important;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03) !important;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1) !important;
            overflow: hidden;
        }
        .slider-card:hover {
            transform: translateY(-8px) !important;
            box-shadow: 0 20px 40px rgba(133, 15, 15, 0.08) !important;
            border-color: rgba(133, 15, 15, 0.18) !important;
        }
        .slider-card .card-image-wrapper {
            height: 185px !important;
            overflow: hidden !important;
            position: relative !important;
        }
        .slider-card .card-image-wrapper img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1) !important;
        }
        .slider-card:hover .card-image-wrapper img {
            transform: scale(1.08) !important;
        }
        .slider-card .card-content {
            flex: 1 !important;
            display: flex !important;
            flex-direction: column !important;
            padding: 1.5rem !important;
        }
        .slider-card h2 {
            color: #0f172a !important;
            font-weight: 800 !important;
            letter-spacing: -0.5px;
            margin-bottom: 0.5rem;
        }
        .slider-card .description {
            color: #475569 !important;
            font-weight: 400 !important;
            line-height: 1.5 !important;
            height: auto !important;
            min-height: auto !important;
            margin-bottom: 0.5rem !important;
            flex-grow: 0 !important;
        }
        .slider-card .card-btn-wrapper {
            margin-top: auto !important;
        }

        /* Category Card Alignment */
        .premium-card {
            display: flex !important;
            flex-direction: column !important;
            height: auto !important;
            min-height: 450px !important;
        }
        .premium-card .card-content {
            flex: 1 !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: flex-start !important;
        }
        .premium-card h2 {
            font-size: 2.1rem !important;
            font-weight: 800 !important;
            color: #850f0f !important;
            letter-spacing: -0.5px;
            margin-bottom: 0.6rem !important;
        }
        .premium-card .description {
            min-height: 60px !important;
            flex: none !important;
            margin-bottom: 8px !important;
        }
        .premium-card .card-btn-wrapper {
            margin-top: auto !important;
        }

        .feature-item i { font-size: 1.8rem; color: var(--primary-color); flex-shrink: 0; }
        .feature-item span { font-weight: 700; color: #2d3748; font-size: 0.95rem; line-height: 1.2; }
        .feature-item:hover { background: #fff8f3; border-color: rgba(255,122,0,0.2); transform: translateY(-3px); box-shadow: 0 8px 25px rgba(255,122,0,0.08); }
        
        /* Optimization for Slider Lag */
        .hero-slide {
            will-change: opacity;
            transform: translateZ(0);
        }
        .slider-card {
            transform: translateZ(0); 
            backface-visibility: hidden;
        }

        /* =============================================================
           TARIFF PLANS REDESIGN (Pricing Overview)
        ============================================================= */
        .pricing-overview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
            width: 100%;
        }

        .tariff-card {
            position: relative;
            border-radius: 28px;
            padding: 2.2rem;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            border: 1px solid rgba(0, 0, 0, 0.05);
            min-height: 420px;
        }

        .tariff-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        }

        /* Card Themes */
        .tariff-card.tariff-standard {
            background: linear-gradient(135deg, rgba(var(--primary-rgb), 0.05) 0%, rgba(var(--primary-rgb), 0.12) 100%);
            color: var(--primary-color);
            border-color: rgba(var(--primary-rgb), 0.15);
        }

        .tariff-card.tariff-premium {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.05) 0%, rgba(30, 41, 59, 0.12) 100%);
            color: #1e293b;
            border-color: rgba(30, 41, 59, 0.15);
        }

        /* Card Header */
        .tariff-header {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }

        .tariff-title {
            font-size: 2.2rem;
            font-weight: 700;
            letter-spacing: -1px;
            line-height: 1.1;
            margin: 0;
            color: inherit;
        }

        .tariff-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.4rem 1rem;
            border: 1.5px solid currentColor;
            border-radius: 9999px;
            font-size: 0.85rem;
            font-weight: 600;
            background: rgba(255, 255, 255, 0.25);
            letter-spacing: -0.2px;
        }

        /* Description */
        .tariff-desc {
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 1.75rem;
            opacity: 0.85;
            font-weight: 500;
            max-width: 90%;
        }

        /* Features List */
        .tariff-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
            margin: 0 0 auto 0; /* Pushes footer to the bottom */
            padding: 0;
        }

        .tariff-list li {
            position: relative;
            padding-left: 1.5rem;
            font-size: 0.95rem;
            font-weight: 600;
            line-height: 1.4;
            color: inherit;
        }

        .tariff-list li::before {
            content: "";
            position: absolute;
            left: 2px;
            top: 0.5rem;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: currentColor;
            opacity: 0.75;
        }

        /* Footer Section */
        .tariff-footer {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-top: 2rem;
            position: relative;
            z-index: 2;
        }

        .tariff-price-block {
            display: flex;
            flex-direction: column;
            gap: 0.15rem;
        }

        .tariff-price-main {
            display: flex;
            align-items: baseline;
            gap: 2px;
        }

        .tariff-currency {
            font-size: 2.5rem;
            font-weight: 800;
            line-height: 1;
        }

        .tariff-amount {
            font-size: 3rem;
            font-weight: 800;
            letter-spacing: -1px;
            line-height: 1;
        }

        .tariff-period {
            font-size: 0.9rem;
            font-weight: 500;
            opacity: 0.75;
            margin-left: 0.15rem;
        }

        .tariff-gst {
            font-size: 0.75rem;
            font-weight: 600;
            opacity: 0.65;
            letter-spacing: 0.2px;
        }

        /* Call To Action Buttons */
        .tariff-cta {
            display: inline-flex;
            align-items: center;
            color: #fff;
            padding: 0.25rem 0.25rem 0.25rem 1.4rem;
            border-radius: 9999px;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.9rem;
            gap: 0.75rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 14px rgba(0,0,0,0.1);
        }

        .tariff-standard .tariff-cta {
            background: var(--primary-color); /* Maroon */
        }
        .tariff-standard .tariff-cta:hover {
            filter: brightness(0.9);
        }

        .tariff-premium .tariff-cta {
            background: #1e293b; /* Dark slate */
        }
        .tariff-premium .tariff-cta:hover {
            background: #0f172a;
        }

        .tariff-cta:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        .tariff-cta-arrow {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            font-size: 0.9rem;
            transition: transform 0.3s ease;
        }

        .tariff-cta:hover .tariff-cta-arrow {
            transform: rotate(45deg);
        }

        /* Geometric Watermark */
        .tariff-watermark {
            position: absolute;
            right: -25px;
            bottom: -25px;
            width: 150px;
            height: 150px;
            opacity: 0.05;
            pointer-events: none;
            color: currentColor;
            z-index: 1;
        }

        @media (max-width: 480px) {
            .tariff-card {
                padding: 1.75rem;
            }
            .tariff-title {
                font-size: 1.8rem;
            }
            .tariff-badge {
                font-size: 0.75rem;
                padding: 0.3rem 0.75rem;
            }
            .tariff-footer {
                flex-direction: column;
                align-items: flex-start;
                gap: 1.25rem;
            }
            .tariff-cta {
                width: 100%;
                justify-content: space-between;
                box-sizing: border-box;
            }
        }
    </style>
    @include('partials.dynamic-styles')
</head>

<body>
    @include('partials.header', ['showHelpBtn' => true])

    <!-- MAIN IMAGE SLIDER SECTION -->
    <section class="main-image-slider">
        <!-- Slide 1 -->
        <div class="hero-slide active-slide">
            <img src="{{ asset('assets/standard/banner.JPG') }}" alt="MCC IGH Home" style="width:100%;height:100%;object-fit:cover;pointer-events:none;" loading="eager">
            <div class="hero-layer" style="position:absolute;inset:0;pointer-events:none;">
                <h2 class="slide-title">Welcome to MCC IGH</h2>
                <p class="slide-subtitle">Comfortable and secure guest house booking</p>
                <a href="#explore-rooms" class="btn btn-primary banner-cta" style="pointer-events: auto;">BOOK NOW</a>
            </div>
        </div>
        <!-- Slide 2 -->
        <div class="hero-slide">
            <img src="{{ asset('assets/mcc1.png') }}" alt="MCC IGH Premium" style="width:100%;height:100%;object-fit:cover;pointer-events:none;">
            <div class="hero-layer" style="position:absolute;inset:0;pointer-events:none;">
                <h2 class="slide-title">Premium Stay Experience</h2>
                <p class="slide-subtitle">Book rooms easily with modern facilities</p>
                <a href="#explore-rooms" class="btn btn-primary banner-cta" style="pointer-events: auto;">BOOK NOW</a>
            </div>
        </div>
        <!-- Slide 3 -->
        <div class="hero-slide">
            <img src="{{ asset('assets/mcc2.png') }}" alt="MCC IGH Booking" style="width:100%;height:100%;object-fit:cover;pointer-events:none;">
            <div class="hero-layer" style="position:absolute;inset:0;pointer-events:none;">
                <h2 class="slide-title">Simple &amp; Fast Booking</h2>
                <p class="slide-subtitle">Plan your stay with ease and convenience</p>
                <a href="#explore-rooms" class="btn btn-primary banner-cta" style="pointer-events: auto;">BOOK NOW</a>
            </div>
        </div>
        <!-- Slide 4 -->
        <div class="hero-slide">
            <img src="{{ asset('assets/standard/banner2.jpg') }}" alt="MCC IGH Modern" style="width:100%;height:100%;object-fit:cover;pointer-events:none;">
            <div class="hero-layer" style="position:absolute;inset:0;pointer-events:none;">
                <h2 class="slide-title">Modern Amenities</h2>
                <p class="slide-subtitle">Experience comfort with state-of-the-art facilities</p>
                <a href="#explore-rooms" class="btn btn-primary banner-cta" style="pointer-events: auto;">BOOK NOW</a>
            </div>
        </div>
        <!-- Slide 5 -->
        <div class="hero-slide">
            <img src="{{ asset('assets/standard/banner1.JPG') }}" alt="MCC IGH Serene" style="width:100%;height:100%;object-fit:cover;pointer-events:none;">
            <div class="hero-layer" style="position:absolute;inset:0;pointer-events:none;">
                <h2 class="slide-title">Serene Environment</h2>
                <p class="slide-subtitle">Enjoy a peaceful and quiet stay at the campus</p>
                <a href="#explore-rooms" class="btn btn-primary banner-cta" style="pointer-events: auto;">BOOK NOW</a>
            </div>
        </div>

    </section>

    <!-- Slider Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const slider = document.querySelector('.main-image-slider');
            const slides = document.querySelectorAll('.hero-slide');

            let current = 0;
            let timer;
            let isDragging = false;
            let isHovered = false;
            let startX = 0;

            function renderSlide(index) {
                if (index < 0 || index >= slides.length) return;
                slides.forEach(s => s.classList.remove('active-slide'));
                slides[index].classList.add('active-slide');
                current = index;
            }

            function nextSlide() { renderSlide((current + 1) % slides.length); }
            function prevSlide() { renderSlide((current - 1 + slides.length) % slides.length); }

            function startTimer() {
                stopTimer();
                timer = setInterval(() => {
                    if (!isHovered && !isDragging) {
                        nextSlide();
                    }
                }, 2000);
            }

            function stopTimer() {
                if (timer) clearInterval(timer);
            }
            // Pause on hover
            slider.addEventListener('mouseenter', () => {
                isHovered = true;
                stopTimer();
            });
            slider.addEventListener('mouseleave', () => {
                isHovered = false;
                if (!isDragging) startTimer();
            });

            // Drag / Swipe Logic
            function dragStart(e) {
                isDragging = true;
                startX = e.type.includes('mouse') ? e.pageX : e.touches[0].clientX;
                stopTimer();
                slider.style.cursor = 'grabbing';
            }
            function dragEnd(e) {
                if (!isDragging) return;
                isDragging = false;
                slider.style.cursor = 'default';
                
                let endX = e.type.includes('mouse') ? e.pageX : e.changedTouches[0].clientX;
                let diff = startX - endX;

                if (Math.abs(diff) > 50) {
                    if (diff > 0) nextSlide();
                    else prevSlide();
                }
                startTimer();
            }

            slider.addEventListener('mousedown', dragStart);
            slider.addEventListener('mouseup', dragEnd);
            slider.addEventListener('mouseleave', (e) => { if(isDragging) dragEnd(e); });
            slider.addEventListener('touchstart', dragStart, {passive: true});
            slider.addEventListener('touchend', dragEnd);

            // Execute boot
            startTimer();
        });
    </script>

    <!-- BOOKING WIDGET OVERLAY -->
    <style>
        #bookingWidget { position: relative; z-index: 50; margin: -150px auto 0; max-width: 1300px; padding: 0 2rem; }
        .bw-dark-card {
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 16px;
            border: 1.5px solid var(--primary-color);
            box-shadow: 0 20px 48px rgba(0,0,0,0.12);
            padding: 2rem;
            color: #0f172a;
            font-family: inherit;
        }
        .bw-title {
            font-size: 0.85rem;
            font-weight: 800;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 1.25rem;
        }
        .bw-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .bw-field {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        .bw-label {
            font-size: 0.72rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .bw-input {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            font-weight: 500;
            color: #0f172a;
            outline: none;
            transition: border-color 0.2s, background 0.2s;
            width: 100%;
        }
        .bw-input::placeholder {
            color: #94a3b8;
        }
        .bw-input:focus {
            border-color: var(--primary-color);
            background: #ffffff;
        }
        .bw-input::-webkit-calendar-picker-indicator {
            opacity: 0.5;
            cursor: pointer;
        }
        .bw-input option {
            background: #ffffff;
            color: #0f172a;
        }
        .bw-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .bw-info {
            font-size: 0.8rem;
            color: #64748b;
            font-weight: 500;
        }
        .bw-btn {
            background: var(--primary-color);
            color: #fff;
            font-weight: 700;
            font-size: 0.95rem;
            padding: 0.85rem 2rem;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 15px rgba(var(--primary-rgb), 0.3);
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
        }
        .bw-btn:hover {
            background: #901010;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(var(--primary-rgb), 0.45);
            color: #fff;
        }
        @media (max-width: 768px) {
            #bookingWidget {
                margin: -60px auto 0 !important;
                padding: 0 1rem !important;
            }
            .bw-dark-card {
                padding: 1.25rem !important;
            }
            .bw-grid {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 0.75rem !important;
                margin-bottom: 1.25rem !important;
            }
            .bw-field {
                gap: 0.35rem !important;
                min-width: 0 !important;
            }
            .bw-label {
                font-size: 0.65rem !important;
            }
            .bw-input {
                padding: 0.6rem 0.5rem !important;
                font-size: 0.82rem !important;
                min-width: 0 !important;
            }
            .bw-input::-webkit-calendar-picker-indicator {
                display: none !important;
                -webkit-appearance: none !important;
            }
            .bw-footer {
                flex-direction: column !important;
                align-items: stretch !important;
                text-align: center !important;
                gap: 0.75rem !important;
            }
            .bw-info {
                font-size: 0.75rem !important;
            }
            .bw-btn {
                justify-content: center !important;
                padding: 0.75rem 1.5rem !important;
                font-size: 0.9rem !important;
            }
            
            /* Mobile slider card height and spacing optimizations */
            .slider-card {
                min-height: auto !important;
            }
            .slider-card .card-image-wrapper {
                height: 130px !important;
                margin-bottom: 0 !important;
            }
            .slider-card .card-content {
                padding: 12px !important;
            }
            .slider-card .card-price-row span:first-child {
                font-size: 1.15rem !important;
            }
            .slider-card h2 {
                font-size: 1.05rem !important;
                margin-bottom: 0.2rem !important;
            }
            .slider-card .description {
                min-height: auto !important;
                margin-bottom: 0.4rem !important;
                font-size: 0.78rem !important;
                line-height: 1.4 !important;
            }
            .slider-card .card-amenities {
                margin-bottom: 0.5rem !important;
                gap: 4px !important;
            }
            .slider-card .amenity-tag {
                padding: 2px 6px !important;
                font-size: 0.68rem !important;
            }
            .slider-card .btn-outline {
                height: 2.4rem !important;
                font-size: 0.8rem !important;
            }
            .slider-card .card-footer-info {
                display: flex !important;
                flex-direction: row !important;
                align-items: center !important;
                justify-content: space-between !important;
                min-height: 30px !important;
                width: 100% !important;
                gap: 4px !important;
            }
            .slider-card .gst-text {
                margin-top: 0 !important;
                margin-bottom: 0 !important;
                font-size: 0.65rem !important;
                padding: 3px 6px !important;
                gap: 4px !important;
                white-space: nowrap !important;
            }
            .slider-card .card-amenity-icons {
                gap: 4px !important;
            }
            .slider-card .card-amenity-icons button {
                width: 26px !important;
                height: 26px !important;
            }
            .slider-card .card-amenity-icons i {
                font-size: 0.85rem !important;
            }

            /* Mobile premium card height and spacing optimizations */
            .premium-card {
                min-height: 320px !important;
            }
            .premium-card .card-content {
                padding: 12px !important;
            }
            .premium-card .description {
                min-height: auto !important;
                margin-bottom: 0.5rem !important;
                font-size: 0.82rem !important;
                line-height: 1.5 !important;
            }
            .premium-card .btn-outline {
                height: 2.4rem !important;
                font-size: 0.8rem !important;
            }
        }
        .pricing-overview-grid {
            display: grid !important;
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 1.5rem !important;
            align-items: stretch !important;
            width: 100% !important;
        }
        @media (max-width: 768px) {
            .pricing-overview-grid {
                grid-template-columns: 1fr !important;
                gap: 1.25rem !important;
            }
        }
        
        /* Title section defaults (matches original desktop UI exactly) */
        .title-section {
            text-align: center !important;
            margin-bottom: 1.75rem !important;
            padding: 0 1rem !important;
        }
        .title-section .subtitle-badge {
            display: none !important;
        }
        .title-section .title-divider {
            display: none !important;
        }
        .title-section h2 {
            font-size: clamp(2rem, 5vw, 2.8rem) !important;
            font-weight: 800 !important;
            color: var(--text-color) !important;
            letter-spacing: -0.5px !important;
            margin: 0 auto 0.4rem auto !important;
            line-height: 1.2 !important;
        }
        .title-section p {
            color: #64748b !important;
            font-size: 1rem !important;
            font-weight: 400 !important;
            margin: 0 auto !important;
            font-family: inherit !important;
        }

        /* Specific overrides for Pricing Overview on desktop to match original size */
        .title-section.pricing-overview h2 {
            font-size: clamp(2.4rem, 6vw, 3.4rem) !important;
            letter-spacing: -1px !important;
            margin-bottom: 0.4rem !important;
        }
        .title-section.pricing-overview p {
            font-size: 1.25rem !important;
            font-weight: 500 !important;
            color: #475569 !important;
        }

        /* Specific overrides for Browse All Rooms heading on desktop to match original font-size */
        .title-section.browse-all-rooms h2 {
            font-size: clamp(1.4rem, 4vw, 1.9rem) !important;
            letter-spacing: -0.5px !important;
            margin-bottom: 0.3rem !important;
        }
        .title-section.browse-all-rooms p {
            font-size: 0.9rem !important;
        }

        /* Mobile responsive override (only active on mobile <= 768px) */
        @media (max-width: 768px) {
            .title-section {
                text-align: left !important;
                padding: 0 1.25rem !important;
                margin-bottom: 1.75rem !important;
            }
            .title-section .subtitle-badge {
                display: inline-block !important;
                font-size: 0.75rem !important;
                font-weight: 800 !important;
                color: var(--primary-color) !important;
                text-transform: uppercase !important;
                letter-spacing: 1.5px !important;
                margin-bottom: 0.4rem !important;
                font-family: 'Inter', sans-serif !important;
            }
            .title-section h2 {
                font-size: clamp(1.8rem, 5vw, 2.4rem) !important;
                letter-spacing: -0.8px !important;
                margin: 0 0 0.6rem 0 !important;
                text-wrap: balance !important;
            }
            .title-section.browse-all-rooms h2 {
                font-size: clamp(1.8rem, 5vw, 2.4rem) !important;
            }
            .title-section .title-divider {
                display: block !important;
                width: 40px !important;
                height: 3px !important;
                background: var(--primary-color) !important;
                margin: 0.75rem 0 !important;
                border-radius: 99px !important;
            }
            .title-section p,
            .title-section.pricing-overview p,
            .title-section.browse-all-rooms p {
                color: #475569 !important;
                font-size: 0.95rem !important;
                font-weight: 500 !important;
                line-height: 1.5 !important;
                margin: 0 !important;
                text-align: left !important;
                font-family: 'Inter', sans-serif !important;
                text-wrap: balance !important;
            }
        }
    </style>
    <div id="bookingWidget">
        <div class="bw-dark-card">
            <div class="bw-title">Quick Booking</div>
            <div class="bw-grid">
                <!-- Check-In -->
                <div class="bw-field">
                    <label class="bw-label">Check-In</label>
                    <input type="date" id="bw_checkin" class="bw-input" placeholder="dd-mm-yyyy">
                </div>

                <!-- Check-Out -->
                <div class="bw-field">
                    <label class="bw-label">Check-Out</label>
                    <input type="date" id="bw_checkout" class="bw-input" placeholder="dd-mm-yyyy">
                </div>

                <!-- Room Type -->
                <div class="bw-field">
                    <label class="bw-label">Room Type</label>
                    <select id="bw_roomtype" class="bw-input">
                        <option value="" disabled selected>Select room</option>
                        <option value="standard" data-url="{{ route('standard.rooms') }}">Standard</option>
                        <option value="advance" data-url="{{ route('advance.rooms') }}">Advance</option>
                        <option value="suite" data-url="{{ route('conference.rooms') }}#suite-room">Suite</option>
                        <option value="conference" data-url="{{ route('conference.rooms') }}">Conference</option>
                    </select>
                </div>

                <!-- Guests -->
                <div class="bw-field">
                    <label class="bw-label">Guests</label>
                    <input type="number" id="bw_guests" class="bw-input" min="1" value="1" placeholder="e.g., 25">
                </div>
            </div>

            <div class="bw-footer">
                <div class="bw-info">+ 5.8% GST applicable on all bookings</div>
                <a href="#" class="bw-btn">Check availability &rarr;</a>
            </div>
        </div>
    </div>


    <main class="main-content-flow">


        <!-- EXPLORE OUR ROOMS SLIDER -->

        <section id="explore-rooms" class="explore-rooms-section">
            <div class="slider-master-container">
                <div class="title-section">
                    <span class="subtitle-badge">Our Offerings</span>
                    <h2>Room <span class="primary-text">Categories</span></h2>
                    <div class="title-divider"></div>
                    <p>Choose from our range of professionally equipped rooms and halls, tailored for comfort and productivity.</p>
                </div>

                <div class="slider-outer-frame">
                    <button type="button" id="roomPrevBtn" class="room-nav-btn left" aria-label="Previous">
                        <i class="ph ph-caret-left" style="font-size: 1.4rem;"></i>
                    </button>
                    
                    <div id="cardsCarousel" class="cards-container" style="align-items: stretch;">
                        @php
                            $roomCards = [
                                [
                                    'badge' => 'Standard',
                                    'badgeClass' => 'standard-badge',
                                    'image' => asset('assets/standard/standardroom.JPG'),
                                    'title' => 'Standard Rooms',
                                    'price' => '₹1,400',
                                    'period' => '12 Hours',
                                    'desc' => 'Thoughtfully designed for efficiency and comfort, providing a restful haven with essential modern amenities.',
                                    'route' => 'standard.rooms',
                                    'btnText' => 'EXPLORE STANDARD',
                                    'amenities' => [
                                        ['name' => 'AC', 'icon' => 'ph-snowflake'],
                                        ['name' => 'Free WiFi', 'icon' => 'ph-wifi-high'],
                                        ['name' => 'Desk', 'icon' => 'ph-desktop'],
                                    ]
                                ],
                                [
                                    'badge' => 'Premium',
                                    'badgeClass' => 'premium-badge',
                                    'image' => asset('assets/room1.JPG'),
                                    'title' => 'Advance Rooms',
                                    'price' => '₹2,500',
                                    'period' => 'Day',
                                    'desc' => 'Curated for guests seeking enhanced privacy and premium comfort during longer stays with high-end bedding.',
                                    'route' => 'advance.rooms',
                                    'btnText' => 'EXPLORE ADVANCE',
                                    'amenities' => [
                                        ['name' => 'AC', 'icon' => 'ph-snowflake'],
                                        ['name' => 'Free WiFi', 'icon' => 'ph-wifi-high'],
                                        ['name' => 'Upgraded', 'icon' => 'ph-sparkle'],
                                    ]
                                ],
                                [
                                    'badge' => 'Conference',
                                    'badgeClass' => 'conference-badge',
                                    'image' => asset('assets/standard/conference.JPG'),
                                    'title' => 'Conference Room',
                                    'price' => '₹500',
                                    'period' => 'Hour (Min 4h)',
                                    'desc' => 'Versatile venue for large-scale gatherings and corporate events with professional projection and sound.',
                                    'route' => 'conference.rooms',
                                    'btnText' => 'EXPLORE HALLS',
                                    'amenities' => [
                                        ['name' => '60 Capacity', 'icon' => 'ph-users'],
                                        ['name' => 'Projector', 'icon' => 'ph-projector-screen'],
                                        ['name' => 'Sound Sys', 'icon' => 'ph-speaker-high'],
                                    ]
                                ],
                                [
                                    'badge' => 'Glass Room',
                                    'badgeClass' => 'conference-badge',
                                    'image' => asset('assets/standard/glass.JPG'),
                                    'title' => 'Glass Room',
                                    'price' => '₹500',
                                    'period' => 'Hour (Min 4h)',
                                    'desc' => 'Inspire creativity in our modern Glass Room, designed for collaborative brainstorming and focused team sessions.',
                                    'route' => 'conference.rooms',
                                    'btnText' => 'EXPLORE HALLS',
                                    'amenities' => [
                                        ['name' => '15 Capacity', 'icon' => 'ph-users'],
                                        ['name' => 'AC', 'icon' => 'ph-snowflake'],
                                        ['name' => 'Brainstorm', 'icon' => 'ph-lightbulb'],
                                    ]
                                ],
                                [
                                    'badge' => 'Suite',
                                    'badgeClass' => 'suite-badge',
                                    'image' => asset('assets/suite.JPG'),
                                    'title' => 'Suite Room',
                                    'price' => '₹500',
                                    'period' => 'Hour (Min 4h)',
                                    'desc' => 'Flagship Suite Room offering the pinnacle of luxury, featuring a grand king-size bed and premium toiletries.',
                                    'route' => 'conference.rooms',
                                    'btnText' => 'EXPLORE SUITE',
                                    'amenities' => [
                                        ['name' => 'King Bed', 'icon' => 'ph-bed'],
                                        ['name' => 'AC', 'icon' => 'ph-snowflake'],
                                        ['name' => 'Luxury Bath', 'icon' => 'ph-shower'],
                                    ]
                                ],
                            ];
                        @endphp

                        @for ($i = 0; $i < 2; $i++)
                            @foreach ($roomCards as $card)
                                <div class="card slider-card" style="display: flex; flex-direction: column; border-radius: 20px; overflow: hidden; transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1); border: 1px solid rgba(0,0,0,0.05);">
                                    <div class="card-image-wrapper" style="height: 185px; flex-shrink: 0; overflow: hidden; position: relative; border-radius: 20px 20px 0 0;">
                                        <span class="badge {{ $card['badgeClass'] }}" style="position: absolute; top: 1rem; left: 1rem; z-index: 5; background: rgba(255,255,255,0.92); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); border: 1px solid rgba(133, 15, 15, 0.15); color: var(--primary-color); font-weight: 700; padding: 4px 12px; border-radius: 30px; font-size: 0.72rem; letter-spacing: 0.5px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">{{ $card['badge'] }}</span>
                                        <img src="{{ $card['image'] }}" alt="{{ $card['title'] }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);">
                                    </div>
                                    <div class="card-content" style="flex: 1; display: flex; flex-direction: column; padding: 1.5rem;">
                                        <div class="card-price-row" style="display: flex; align-items: baseline; gap: 6px; margin-bottom: 0.35rem;">
                                            <span style="font-size: 1.4rem; font-weight: 800; color: var(--primary-color); letter-spacing: -0.5px;">{!! str_replace('₹', '<span class="rupee-symbol">₹</span>', $card['price']) !!}</span>
                                            <span style="font-size: 0.8rem; color: #64748b; font-weight: 600;">/ {{ $card['period'] }}</span>
                                        </div>
                                        <h2 style="font-size: 1.2rem; font-weight: 800; margin-bottom: 0.5rem; color: #0f172a; letter-spacing: -0.3px;">{{ $card['title'] }}</h2>
                                        <p class="description" style="font-size: 0.86rem; color: #64748b; line-height: 1.5; margin-bottom: 0.5rem; flex-grow: 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{!! $card['desc'] !!}</p>
                                        
                                        @php $gstRate = \App\Models\Setting::where('key', 'gst_rate')->value('value') ?? 5; @endphp
                                        <div class="card-footer-info" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; min-height: 32px;">
                                            <p class="gst-text" style="font-size: 0.72rem; color: #94a3b8; font-weight: 600; margin: 0 !important; display: flex; align-items: center; gap: 4px;">
                                                <i class="ph-bold ph-info" style="font-size: 0.78rem; color: #cbd5e1;"></i> 
                                                + {{ $gstRate }}% GST
                                            </p>
                                            <div class="card-amenity-icons" style="display: flex; align-items: center; gap: 6px;">
                                                @foreach ($card['amenities'] as $amenity)
                                                    <div style="position: relative; display: inline-flex;">
                                                        <button type="button" onclick="showAmenityTooltip(this, '{{ $amenity['name'] }}')" title="{{ $amenity['name'] }}" style="width: 30px; height: 30px; border-radius: 50%; background: #f8fafc; border: 1.5px solid #e2e8f0; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; padding: 0;" onmouseover="this.style.borderColor='var(--primary-color)'; this.style.transform='scale(1.08)'" onmouseout="this.style.borderColor='#e2e8f0'; this.style.transform='scale(1)'">
                                                            <i class="ph {{ $amenity['icon'] }}" style="color: var(--primary-color); font-size: 1rem;"></i>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="card-btn-wrapper" style="margin-top: auto !important;">
                                            <a href="{{ route($card['route']) }}" class="btn btn-outline" style="width: 100%; text-align: center; justify-content: center; text-transform: uppercase; font-weight: 700; border-radius: 12px; height: 2.8rem; font-size: 0.85rem; letter-spacing: 0.3px;">{{ $card['btnText'] }}</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endfor
                    </div>

                    <button type="button" id="roomNextBtn" class="room-nav-btn right" aria-label="Next">
                        <i class="ph ph-caret-right" style="font-size: 1.4rem;"></i>
                    </button>
                </div>
            </div>
        </section>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const container = document.getElementById('cardsCarousel');
                const leftArrow = document.getElementById('roomPrevBtn');
                const rightArrow = document.getElementById('roomNextBtn');
                
                let speed = 1.0; // 1px per frame (perfectly smooth on 60fps)
                let isHovered = false;
                let isManualPaused = false;
                let scrollPos = container.scrollLeft;
                let manualPauseTimer = null;

                function autoScroll() {
                    if (!isHovered && !isManualPaused) {
                        scrollPos += speed;
                        if (scrollPos >= container.scrollWidth / 2) {
                            scrollPos = 0;
                        }
                        container.scrollLeft = scrollPos;
                    }
                    requestAnimationFrame(autoScroll);
                }

                autoScroll();

                function triggerManualPause() {
                    isManualPaused = true;
                    if (manualPauseTimer) clearTimeout(manualPauseTimer);
                    manualPauseTimer = setTimeout(() => {
                        isManualPaused = false;
                        // Synchronize scrollPos with actual scrollLeft when resuming
                        scrollPos = container.scrollLeft;
                    }, 4000); // 4s manual override after interaction
                }

                leftArrow.onclick = (e) => {
                    e.preventDefault();
                    triggerManualPause();
                    container.scrollBy({ left: -320, behavior: 'smooth' });
                };

                rightArrow.onclick = (e) => {
                    e.preventDefault();
                    triggerManualPause();
                    container.scrollBy({ left: 320, behavior: 'smooth' });
                };

                // Track hover state
                container.addEventListener('mouseenter', () => {
                    isHovered = true;
                });
                
                container.addEventListener('mouseleave', () => {
                    isHovered = false;
                    // Sync position on leave to ensure smooth pickup
                    scrollPos = container.scrollLeft;
                });

                // Periodic sync to handle manual browser scrolling or touch
                container.addEventListener('scroll', () => {
                    if (isHovered || isManualPaused) {
                        scrollPos = container.scrollLeft;
                    }
                });
            });
        </script>
        <!-- ROOM CATEGORIES QUICK REFERENCE -->
        <section style="max-width: 1100px; padding-top: 0; margin-top: -4rem; position: relative; z-index: 10;">
            <div class="title-section pricing-overview">
                <span class="subtitle-badge">Tariff Guide</span>
                <h2 style="font-size: clamp(2.4rem, 6vw, 3.4rem) !important;">Pricing <span class="primary-text">Overview</span></h2>
                <div class="title-divider"></div>
                <p>Quick reference for room rates and availability</p>
            </div>
            <div class="pricing-overview-grid">

                <!-- Standard Rooms Card -->
                <div class="tariff-card tariff-standard">
                    <!-- Geometric Watermark -->
                    <svg class="tariff-watermark" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M50 15L85 35V75L50 95L15 75V35L50 15Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                        <path d="M50 15V95" stroke="currentColor" stroke-width="2"/>
                        <path d="M15 35L50 55L85 35" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                    </svg>

                    <div class="tariff-header">
                        <h3 class="tariff-title">Standard</h3>
                        <span class="tariff-badge">Rooms 1 – 8</span>
                    </div>

                    <p class="tariff-desc">Ideal for short-term stays, stopovers, and budget-conscious academic or corporate visits.</p>

                    <ul class="tariff-list">
                        <li>Ideal for short stays</li>
                        <li>AC, WiFi &amp; Work Desk included</li>
                        <li>Clean bedding &amp; basic amenities</li>
                    </ul>

                    <div class="tariff-footer">
                        <div class="tariff-price-block">
                            <div class="tariff-price-main">
                                <span class="tariff-currency">₹</span><span class="tariff-amount">1400</span>
                                <span class="tariff-period">/ 12 hours</span>
                            </div>
                            <div class="tariff-gst">+ {{ $gstRate }}% GST applicable</div>
                        </div>
                        <a href="{{ route('standard.rooms') }}" class="tariff-cta">
                            <span>View Rooms</span>
                            <span class="tariff-cta-arrow">
                                <i class="ph ph-arrow-up-right"></i>
                            </span>
                        </a>
                    </div>
                </div>

                <!-- Premium Rooms Card -->
                <div class="tariff-card tariff-premium">
                    <!-- Geometric Watermark -->
                    <svg class="tariff-watermark" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M50 15L85 35V75L50 95L15 75V35L50 15Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                        <path d="M50 15V95" stroke="currentColor" stroke-width="2"/>
                        <path d="M15 35L50 55L85 35" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                    </svg>

                    <div class="tariff-header">
                        <h3 class="tariff-title">Premium</h3>
                        <span class="tariff-badge">Rooms 101 – 207</span>
                    </div>

                    <p class="tariff-desc">Spacious and premium lodging offering enhanced comfort, privacy, and full amenities for long stays.</p>

                    <ul class="tariff-list">
                        <li>Premium stay experience</li>
                        <li>Better interiors &amp; privacy</li>
                        <li>Smart TV &amp; Mini Fridge included</li>
                    </ul>

                    <div class="tariff-footer">
                        <div class="tariff-price-block">
                            <div class="tariff-price-main">
                                <span class="tariff-currency">₹</span><span class="tariff-amount">2500</span>
                                <span class="tariff-period">/ day</span>
                            </div>
                            <div class="tariff-gst">+ {{ $gstRate }}% GST applicable</div>
                        </div>
                        <a href="{{ route('advance.rooms') }}" class="tariff-cta">
                            <span>View Rooms</span>
                            <span class="tariff-cta-arrow">
                                <i class="ph ph-arrow-up-right"></i>
                            </span>
                        </a>
                    </div>
                </div>

            </div>
        </section>

        <!-- CATEGORY SELECTION -->
        <section style="max-width: 1350px;">
            <div class="title-section browse-all-rooms" style="text-align: center; margin-bottom: 2.2rem;">
                <h2 style="font-size: clamp(2.0rem, 5vw, 2.5rem) !important; font-weight: 800 !important; color: #0f172a !important; font-family: 'Outfit', sans-serif !important; margin-bottom: 0.4rem !important;">Browse All <span style="color: var(--primary-color) !important;">Rooms</span></h2>
                <p style="color: #64748b !important; font-size: 0.95rem !important; font-family: 'Inter', sans-serif !important; margin: 0 !important;">Select a category to explore availability and book your stay</p>
            </div>
            <div class="dashboard-rooms-grid" style="align-items: stretch;">

                <!-- Standard Rooms -->
                <div class="card premium-card">
                    <div class="card-image-wrapper">
                        <img src="{{ asset('assets/standard/standardroom.JPG') }}" alt="Standard Rooms">
                        <span class="badge standard-badge" style="position: absolute; top: 1rem; left: 1rem; z-index: 5; background: #547395; color: #ffffff; font-weight: 700; padding: 6px 12px; border-radius: 4px; font-size: 0.7rem; letter-spacing: 0.8px; text-transform: uppercase; box-shadow: none; font-family: 'Inter', sans-serif;">STANDARD</span>
                    </div>
                    <div class="card-content">
                        <h2 style="font-size: 1.35rem; font-weight: 700; color: var(--primary-color); margin: 0 0 0.6rem 0; font-family: 'Outfit', sans-serif;">Standard Rooms</h2>
                        <p class="description">Thoughtfully designed for efficiency and comfort, our Standard Rooms provide a restful haven for short-term visitors with essential modern amenities.</p>

                        <div style="margin-bottom: 1.25rem;">
                            <span class="gst-tag" style="font-size: 0.72rem; color: #15803d; background: #eafaf1; padding: 5px 10px; border-radius: 4px; font-weight: 700; display: inline-block; font-family: 'Inter', sans-serif;">
                                + {{ $gstRate }}% GST applicable
                            </span>
                        </div>

                        <div class="card-btn-wrapper">
                            <a href="{{ route('standard.rooms') }}" class="btn btn-outline view-details-btn">
                                VIEW DETAILS
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Advance Rooms -->
                <div class="card premium-card">
                    <div class="card-image-wrapper">
                        <img src="{{ asset('assets/room1.JPG') }}" alt="Advance Rooms">
                        <span class="badge premium-badge" style="position: absolute; top: 1rem; left: 1rem; z-index: 5; background: var(--primary-color); color: #ffffff; font-weight: 700; padding: 6px 12px; border-radius: 4px; font-size: 0.7rem; letter-spacing: 0.8px; text-transform: uppercase; box-shadow: none; font-family: 'Inter', sans-serif;">PREMIUM</span>
                    </div>
                    <div class="card-content">
                        <h2 style="font-size: 1.35rem; font-weight: 700; color: var(--primary-color); margin: 0 0 0.6rem 0; font-family: 'Outfit', sans-serif;">Advance Rooms</h2>
                        <p class="description">Experience elevated hospitality in our Advance Rooms, specifically curated for guests seeking enhanced privacy and premium comfort during longer stays.</p>

                        <div style="margin-bottom: 1.25rem;">
                            <span class="gst-tag" style="font-size: 0.72rem; color: #15803d; background: #eafaf1; padding: 5px 10px; border-radius: 4px; font-weight: 700; display: inline-block; font-family: 'Inter', sans-serif;">
                                + {{ $gstRate }}% GST applicable
                            </span>
                        </div>

                        <div class="card-btn-wrapper">
                            <a href="{{ route('advance.rooms') }}" class="btn btn-outline view-details-btn">
                                VIEW DETAILS
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Conference / Glass Rooms -->
                <div class="card premium-card">
                    <div class="card-image-wrapper">
                        <img src="{{ asset('assets/standard/conference.JPG') }}" alt="Conference Rooms">
                        <span class="badge conference-badge" style="position: absolute; top: 1rem; left: 1rem; z-index: 5; background: #2b76df; color: #ffffff; font-weight: 700; padding: 6px 12px; border-radius: 4px; font-size: 0.7rem; letter-spacing: 0.8px; text-transform: uppercase; box-shadow: none; font-family: 'Inter', sans-serif;">CONFERENCE</span>
                    </div>
                    <div class="card-content">
                        <h2 style="font-size: 1.35rem; font-weight: 700; color: var(--primary-color); margin: 0 0 0.6rem 0; font-family: 'Outfit', sans-serif;">Conference &amp; Glass Rooms</h2>
                        <p class="description">A versatile and professionally equipped venue designed for large-scale gatherings, corporate events, and interactive workshops with HD projection.</p>

                        <div style="margin-bottom: 1.25rem;">
                            <span class="gst-tag" style="font-size: 0.72rem; color: #15803d; background: #eafaf1; padding: 5px 10px; border-radius: 4px; font-weight: 700; display: inline-block; font-family: 'Inter', sans-serif;">
                                + {{ $gstRate }}% GST applicable
                            </span>
                        </div>

                        <div class="card-btn-wrapper">
                            <a href="{{ route('conference.rooms') }}" class="btn btn-outline view-details-btn">
                                VIEW DETAILS
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ABOUT FACILITIES -->
        <section class="description-section">
            <div class="premium-facility-card" id="facilityCard">
                <h2 class="facility-title">About Our <span class="primary-text">Facilities</span></h2>
                <div class="facility-divider"></div>
                <div class="desc-content">
                    <p>Experience a refined stay tailored to the needs of modern professionals and distinguished guests. At MCC IGH, we combine traditional hospitality with premium modern amenities, ensuring every moment of your visit is both relaxing and highly productive.</p>
                </div>

                <div class="feature-grid">
                    <div class="feature-item">
                        <i class="ph-fill ph-wifi-high"></i>
                        <span>High-Speed WiFi</span>
                    </div>
                    <div class="feature-item">
                        <i class="ph-fill ph-wind"></i>
                        <span>Smart AC Rooms</span>
                    </div>
                    <div class="feature-item">
                        <i class="ph-fill ph-headset"></i>
                        <span>24/7 Support</span>
                    </div>
                    <div class="feature-item">
                        <i class="ph-fill ph-shield-check"></i>
                        <span>Secure & Clean</span>
                    </div>
                </div>
            </div>
        </section>

        <script>
            // Simple Intersection Observer for the fade-in effect
            document.addEventListener('DOMContentLoaded', function() {
                const card = document.getElementById('facilityCard');
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('visible');
                        }
                    });
                }, { threshold: 0.1 });
                observer.observe(card);
            });
        </script>
    </main>

    @include('partials.footer')

    <!-- Modal confirmation overlay -->
    <div class="modal-overlay" id="bookingModal">
        <div class="modal">
            <div class="modal-header">
                <h3>Confirm Space Booking</h3>
                <button class="close-btn" onclick="closeModal()"><i class="ph ph-x"></i></button>
            </div>
            <div class="modal-body">
                <div class="confirmation-details">
                    <p><strong>Space:</strong> <span id="modalRoomName"></span></p>
                    <p><strong>Date:</strong> <span id="modalDate"></span></p>
                    <p><strong>Time:</strong> <span id="modalTime"></span></p>
                    <p><strong>Rate:</strong> <span class="rupee-symbol">₹</span><span id="modalPrice"></span> / hr</p>
                    <p class="gst-text">+ {{ $gstRate }}% GST applicable</p>
                </div>
                <div class="modal-warning" id="modalWarning" style="display:none;"></div>
                <p>Are you sure you want to proceed to the details page with this schedule?</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal()">Cancel</button>
                <button class="btn" id="confirmProceedBtn" onclick="confirmProceed()">Confirm & Proceed</button>
            </div>
        </div>
    </div>

    <!-- Success Modal popup overlay after coming back from payment -->
    <div class="modal-overlay" id="successModal">
        <div class="modal">
            <div class="modal-body success-modal">
                <div class="status-icon"><i class="ph-fill ph-check-circle"></i></div>
                <h3 style="margin-bottom: 0.5rem; font-size:1.5rem;">Booking Confirmed!</h3>
                <p style="color: var(--text-light); margin-bottom: 1.5rem;">Your space has been successfully booked.
                    We've sent the details to your email.</p>
                <button class="btn" onclick="closeModal()">Awesome, thanks!</button>
            </div>
        </div>
    </div>

    <!-- Alert Toast (View Details dummy action) -->
    <div class="toast" id="toast"></div>

    @include('partials.help-modal')


    <script src="{{ asset('js/script.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            initIndexPage();

            // Dynamic Room Category Redirection
            const roomTypeSelect = document.getElementById('bw_roomtype');
            const checkAvailabilityBtn = document.querySelector('.bw-footer .bw-btn');

            if (roomTypeSelect && checkAvailabilityBtn) {
                roomTypeSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const targetUrl = selectedOption.getAttribute('data-url');
                    if (targetUrl) {
                        checkAvailabilityBtn.setAttribute('href', targetUrl);
                    }
                });

                checkAvailabilityBtn.addEventListener('click', function(e) {
                    if (!roomTypeSelect.value) {
                        e.preventDefault();
                        alert('Please select a Room Type first.');
                    }
                });
            }
        });

        function showAmenityTooltip(el, text) {
            document.querySelectorAll('.custom-amenity-tooltip').forEach(t => t.remove());
            
            const tooltip = document.createElement('div');
            tooltip.className = 'custom-amenity-tooltip';
            tooltip.innerText = text;
            tooltip.style.position = 'absolute';
            tooltip.style.bottom = '125%';
            tooltip.style.left = '50%';
            tooltip.style.transform = 'translateX(-50%) translateY(4px)';
            tooltip.style.background = '#0f172a';
            tooltip.style.color = '#fff';
            tooltip.style.padding = '5px 10px';
            tooltip.style.borderRadius = '6px';
            tooltip.style.fontSize = '0.75rem';
            tooltip.style.fontWeight = '600';
            tooltip.style.whiteSpace = 'nowrap';
            tooltip.style.boxShadow = '0 4px 12px rgba(0,0,0,0.2)';
            tooltip.style.zIndex = '100';
            tooltip.style.opacity = '0';
            tooltip.style.pointerEvents = 'none';
            tooltip.style.transition = 'all 0.2s cubic-bezier(0.16, 1, 0.3, 1)';
            
            const arrow = document.createElement('div');
            arrow.style.position = 'absolute';
            arrow.style.top = '100%';
            arrow.style.left = '50%';
            arrow.style.transform = 'translateX(-50%)';
            arrow.style.borderWidth = '4px';
            arrow.style.borderStyle = 'solid';
            arrow.style.borderColor = '#0f172a transparent transparent transparent';
            tooltip.appendChild(arrow);
            
            el.parentElement.appendChild(tooltip);
            
            requestAnimationFrame(() => {
                tooltip.style.opacity = '1';
                tooltip.style.transform = 'translateX(-50%) translateY(0)';
            });
            
            setTimeout(() => {
                tooltip.style.opacity = '0';
                tooltip.style.transform = 'translateX(-50%) translateY(4px)';
                setTimeout(() => tooltip.remove(), 200);
            }, 2500);
        }



        function slideLeft() {
            const slider = document.getElementById('roomSlider');
            if (slider) {
                const card = slider.querySelector('.card');
                if (card) {
                    const cardWidth = card.offsetWidth + 32; // Includes 2rem (32px) gap
                    slider.scrollBy({ left: -cardWidth, behavior: 'smooth' });
                }
            }
        }

        function slideRight() {
            const slider = document.getElementById('roomSlider');
            if (slider) {
                const card = slider.querySelector('.card');
                if (card) {
                    const cardWidth = card.offsetWidth + 32;
                    slider.scrollBy({ left: cardWidth, behavior: 'smooth' });
                }
            }
        }

        function calcSpecialPrice(roomId) {
            const hoursInput = document.getElementById('hours-' + roomId);
            const priceDisplay = document.getElementById('price-' + roomId);
            const timeTextDisplay = document.getElementById('time-text-' + roomId);
            
            let hours = parseFloat(hoursInput.value);
            if (isNaN(hours) || hours <= 0) {
                priceDisplay.innerHTML = '<span class="rupee-symbol">₹</span>0';
                timeTextDisplay.innerHTML = '0 hours';
                return;
            }
            let finalPrice = hours > 4 ? 5000 : 2000;
            priceDisplay.innerHTML = '<span class="rupee-symbol">₹</span>' + finalPrice;
            timeTextDisplay.innerHTML = hours + (hours === 1 ? ' hour' : ' hours');
        }
    </script>
</body>

</html>