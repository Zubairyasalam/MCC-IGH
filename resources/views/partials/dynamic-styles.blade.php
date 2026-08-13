@php
    $primaryColor = \App\Models\Setting::where('key', 'primary_color')->first()->value ?? '#850f0f';
    $secondaryColor = \App\Models\Setting::where('key', 'secondary_color')->first()->value ?? '#001a33';

    function hexToRgb($hex)
    {
        $hex = str_replace("#", "", $hex);
        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        return "$r, $g, $b";
    }
    $primaryRgb = hexToRgb($primaryColor);
    $secondaryRgb = hexToRgb($secondaryColor);
    $useSecondary = \App\Models\Setting::where('key', 'use_secondary_color')->first()->value ?? '0';
@endphp
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@300;400;500;600;700;800&display=swap');

    :root {
        --primary-color:
            {{ $primaryColor }}
        ;
        --primary-rgb:
            {{ $primaryRgb }}
        ;
        --primary:
            {{ $primaryColor }}
        ;
        --secondary-color:
            {{ $secondaryColor }}
        ;
        --secondary:
            {{ $secondaryColor }}
        ;
    }

    body,
    input,
    select,
    textarea,
    button {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    .welcome-title,
    .slide-title,
    .page-hero-card h1 {
        font-family: 'Outfit', sans-serif;
        letter-spacing: -0.02em;
    }

    /* Secondary Style Overrides for Visual Balance */
    @if($useSecondary == '1')
        header:not(.auth-header) {
            border-bottom: 2px solid var(--secondary-color) !important;
        }

        .sidebar,
        .admin-sidebar {
            border-right: 1px solid var(--secondary-color) !important;
        }

    @endif

    /* Global overrides for common elements */
    .btn:not(.btn-outline),
    .btn-primary,
    .btn-save,
    .submit-btn,
    .help-send-btn,
    .confirm-booking-btn,
    .active-nav,
    .badge-primary {
        background-color: var(--primary-color) !important;
        border-color: var(--primary-color) !important;
        color: #ffffff !important;
    }

    /* Dynamic Theme Frame/Border Card & Box Styling */
    .card,
    .slider-card,
    .room-card,
    .premium-card,
    .modal-card,
    .help-modal-card,
    .pricing-info-card,
    .auth-card,
    .success-wrapper,
    .summary-cards-grid > div,
    .guide-box,
    .webhook-item,
    .room-hero,
    .timeline-card,
    .modal-content,
    .confirm-modal-content {
        border: 1.5px solid var(--border, #e2e8f0) !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02) !important;
        transition: border-color 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease !important;
    }

    /* Permanent Brand Frame for Forms and Settings Cards */
    .form-card,
    .form-container,
    .booking-form-container,
    .login-card,
    .settings-card,
    .filter-card,
    .details-card,
    .payment-summary,
    .content-card,
    .report-table-container,
    .stat-card,
    .dashboard-section,
    .summary-card {
        border: 1.5px solid var(--primary-color) !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02) !important;
        transition: none !important;
        transform: none !important;
    }
    .form-card:hover,
    .form-container:hover,
    .booking-form-container:hover,
    .login-card:hover,
    .settings-card:hover,
    .filter-card:hover,
    .details-card:hover,
    .payment-summary:hover,
    .content-card:hover,
    .report-table-container:hover,
    .stat-card:hover,
    .dashboard-section:hover,
    .summary-card:hover {
        transform: none !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02) !important;
        border-color: var(--primary-color) !important;
    }

    /* Permanent Brand Frame for Main Facilities Box */
    .premium-facility-card {
        border: 1.5px solid var(--primary-color) !important;
        box-shadow: 0 8px 30px rgba(var(--primary-rgb), 0.04) !important;
        transition: box-shadow 0.3s ease !important;
    }
    .premium-facility-card:hover {
        box-shadow: 0 12px 40px rgba(var(--primary-rgb), 0.08) !important;
    }
    .card:hover,
    .card:active,
    .card:focus,
    .card.active,
    .slider-card:hover,
    .slider-card:active,
    .room-card:hover,
    .room-card:active,
    .room-card.active,
    .premium-card:hover,
    .premium-card:active,
    .pricing-info-card:hover,
    .pricing-info-card:active,
    .auth-card:hover,
    .auth-card:active,
    .success-wrapper:hover,
    .success-wrapper:active,
    .summary-cards-grid > div:hover,
    .summary-cards-grid > div:active,
    .guide-box:hover,
    .guide-box:active,
    .webhook-item:hover,
    .webhook-item:active,
    .room-hero:hover,
    .room-hero:active,
    .timeline-card:hover,
    .timeline-card:active {
        border-color: var(--primary-color) !important;
        box-shadow: 0 12px 32px rgba(var(--primary-rgb), 0.12) !important;
        transform: translateY(-4px) !important;
    }

    /* Keep hover frame border highlight, but do not move/animate */
    .modal-content:hover,
    .modal-content:active,
    .confirm-modal-content:hover,
    .confirm-modal-content:active {
        border-color: var(--primary-color) !important;
    }

    /* Dynamic Theme for Facility Cards */
    .feature-grid .feature-item {
        border: 1.5px solid var(--border, #e2e8f0) !important;
        transition: all 0.3s ease !important;
    }
    .feature-grid .feature-item:hover,
    .feature-grid .feature-item:active,
    .feature-grid .feature-item.active {
        background-color: rgba(var(--primary-rgb), 0.05) !important;
        border-color: var(--primary-color) !important;
        transform: translateY(-3px) !important;
        box-shadow: 0 8px 25px rgba(var(--primary-rgb), 0.08) !important;
    }
    .feature-grid .feature-item:hover i,
    .feature-grid .feature-item:active i,
    .feature-grid .feature-item.active i {
        color: var(--primary-color) !important;
    }

    /* Dynamic Outline Buttons */
    .btn-outline {
        background-color: transparent !important;
        border: 1.5px solid var(--primary-color) !important;
        color: var(--primary-color) !important;
        transition: all 0.2s ease !important;
    }
    .btn-outline:hover,
    .btn-outline:active {
        background-color: var(--primary-color) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(var(--primary-rgb), 0.2) !important;
    }

    .text-primary,
    .primary-text,
    .welcome-title span,
    .price-highlight span,
    .modal-price-line span:first-child,
    .breadcrumb a,
    .header-logo i,
    .hero-prev:hover,
    .hero-next:hover,
    .dropdown-item:hover {
        color: var(--primary-color) !important;
    }

    .rupee-symbol {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
        font-weight: 500 !important;
        font-size: 0.82em !important;
        margin-right: 0.05em !important;
        display: inline-block !important;
        vertical-align: baseline !important;
        position: relative !important;
        top: -0.04em !important;
    }

    .room-highlights i,
    .facility-item i,
    .ph-list {
        color: var(--primary-color) !important;
    }

    /* Premium Carousel & Slider Arrow Buttons styling */
    .hero-prev,
    .hero-next,
    .room-nav-btn {
        background: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(8px) !important;
        -webkit-backdrop-filter: blur(8px) !important;
        border: 1.5px solid rgba(0, 0, 0, 0.05) !important;
        color: var(--primary-color) !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08) !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        font-family: inherit !important;
    }

    .hero-prev:hover,
    .hero-next:hover,
    .room-nav-btn:hover {
        background: #ffffff !important;
        color: var(--primary-color) !important;
        border-color: var(--primary-color) !important;
        box-shadow: 0 10px 30px rgba({{ $primaryRgb }}, 0.25) !important;
        transform: translateY(-50%) scale(1.1) !important;
    }

    .hero-prev:active,
    .hero-next:active,
    .room-nav-btn:active {
        transform: translateY(-50%) scale(0.95) !important;
    }

    .room-nav-btn.left {
        left: 20px !important;
    }

    .room-nav-btn.right {
        right: 20px !important;
    }

    .header-container,
    header:not(.auth-header) {
        background: #ffffff !important;
        border-bottom: 1px solid #f1f5f9 !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02) !important;
        padding: 0 2.5rem !important;
        height: 95px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        position: sticky !important;
        top: 0 !important;
        z-index: 1000 !important;
        width: 100% !important;
        box-sizing: border-box !important;
        transition: all 0.3s ease !important;
    }

    .logo-text {
        gap: 2px !important;
    }

    .mcc-text {
        font-family: 'Outfit', sans-serif !important;
        font-weight: 700 !important;
        color: var(--primary-color) !important;
        font-size: 2.0rem !important;
        letter-spacing: 0.01em !important;
        line-height: 1.0 !important;
        margin-bottom: 0px !important;
        text-shadow: none !important;
    }

    .igh-text {
        font-family: 'Inter', sans-serif !important;
        color: var(--primary-color) !important;
        font-weight: 700 !important;
        font-size: 0.72rem !important;
        letter-spacing: 0.22em !important;
        line-height: 1.1 !important;
        text-shadow: none !important;
        text-transform: uppercase !important;
    }

    /* Standardized Breadcrumb Spacing for Category Pages */
    .breadcrumb {
        margin-top: 30px !important;
        margin-bottom: 1.5rem !important;
        font-size: 0.9rem !important;
        color: #7f1d1d !important; /* Maroon */
        font-weight: 500 !important;
    }

    .breadcrumb a {
        color: #7f1d1d !important;
        font-weight: 700 !important;
        text-decoration: none !important;
    }

    .breadcrumb span {
        color: #7f1d1d !important;
        opacity: 0.8 !important;
    }

    .help-btn {
        background: var(--primary-color) !important;
        color: #ffffff !important;
        border: 1px solid var(--primary-color) !important;
        padding: 0.58rem 1.45rem !important;
        border-radius: 9999px !important;
        font-weight: 700 !important;
        font-size: 0.78rem !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 6px !important;
        transition: all 0.2s ease !important;
        cursor: pointer !important;
        box-shadow: 0 4px 10px rgba({{ $primaryRgb }}, 0.15) !important;
        height: 38px !important;
        box-sizing: border-box !important;
    }

    .help-btn i {
        font-size: 1.05rem !important;
        color: #ffffff !important;
    }

    .help-btn span {
        color: #ffffff !important;
        line-height: 1.0 !important;
    }

    .help-btn:hover,
    .help-btn:active,
    .help-btn:focus {
        background: var(--primary-color) !important;
        color: #ffffff !important;
        border-color: var(--primary-color) !important;
        filter: brightness(0.9) !important;
        box-shadow: 0 6px 15px rgba({{ $primaryRgb }}, 0.25) !important;
        transform: translateY(-1px) !important;
    }

    .help-btn:hover i,
    .help-btn:hover span,
    .help-btn:active i,
    .help-btn:active span,
    .help-btn:focus i,
    .help-btn:focus span {
        color: #ffffff !important;
    }

    /* Header Cart Button & Reservation Badge */
    .cart-btn-header {
        background: var(--primary-color) !important;
        color: #ffffff !important;
        border: 1px solid var(--primary-color) !important;
        padding: 0.5rem 1.2rem !important;
        border-radius: 9999px !important;
        font-weight: 700 !important;
        font-size: 0.76rem !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 8px !important;
        transition: all 0.2s ease !important;
        cursor: pointer !important;
        box-shadow: 0 4px 12px rgba({{ $primaryRgb }}, 0.2) !important;
        height: 40px !important;
        box-sizing: border-box !important;
        text-decoration: none !important;
        white-space: nowrap !important;
        margin-right: 2px !important;
    }

    .cart-btn-header i,
    .cart-btn-header span,
    .cart-btn-header .cart-label-text {
        color: #ffffff !important;
    }

    .cart-count-badge {
        background: #ffffff !important;
        color: var(--primary-color) !important;
        padding: 2px 7px !important;
        border-radius: 9999px !important;
        font-size: 0.72rem !important;
        font-weight: 800 !important;
        margin-left: 4px !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.15) !important;
        line-height: 1 !important;
        align-items: center;
        justify-content: center;
    }

    .cart-count-badge[style*="display: none"],
    .cart-count-badge:empty {
        display: none !important;
    }

    .cart-btn-header:hover,
    .cart-btn-header:active,
    .cart-btn-header:focus {
        background: var(--primary-color) !important;
        color: #ffffff !important;
        border-color: var(--primary-color) !important;
        filter: brightness(0.9) !important;
        box-shadow: 0 6px 15px rgba({{ $primaryRgb }}, 0.25) !important;
        transform: translateY(-1px) !important;
    }

    .cart-btn-header:hover i,
    .cart-btn-header:hover span,
    .cart-btn-header:hover .cart-label-text {
        color: #ffffff !important;
    }

    /* Premium Header Back Button */
    .btn-header-back {
        background: var(--primary-color) !important;
        color: #ffffff !important;
        border: 1px solid var(--primary-color) !important;
        padding: 0.5rem 1.25rem !important;
        border-radius: 9999px !important;
        font-weight: 700 !important;
        font-size: 0.8rem !important;
        text-transform: uppercase !important;
        letter-spacing: 0.8px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 8px !important;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
        cursor: pointer !important;
        box-shadow: 0 4px 12px rgba({{ $primaryRgb }}, 0.2) !important;
        height: 40px !important;
        box-sizing: border-box !important;
        white-space: nowrap !important;
    }

    .btn-header-back i,
    .btn-header-back span {
        color: #ffffff !important;
    }

    .btn-header-back:hover,
    .btn-header-back:active,
    .btn-header-back:focus,
    .btn-header-back:focus-within {
        background: var(--primary-color) !important;
        color: #ffffff !important;
        border-color: var(--primary-color) !important;
        filter: brightness(0.9) !important;
        box-shadow: 0 4px 15px rgba({{ $primaryRgb }}, 0.25) !important;
    }

    .btn-header-back:hover i,
    .btn-header-back:hover span,
    .btn-header-back:active i,
    .btn-header-back:active span,
    .btn-header-back:focus i,
    .btn-header-back:focus span,
    .btn-header-back:focus-within i,
    .btn-header-back:focus-within span {
        color: #ffffff !important;
    }

    /* Premium Profile Dropdown Button */
    .profile-btn {
        background: transparent !important;
        color: var(--primary-color) !important;
        border: 1.2px solid var(--primary-color) !important;
        width: 38px !important;
        height: 38px !important;
        border-radius: 50% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
        padding: 0 !important;
        box-shadow: none !important;
        box-sizing: border-box !important;
    }

    .profile-btn i {
        font-size: 1.25rem !important;
        color: var(--primary-color) !important;
        transition: transform 0.2s ease !important;
    }

    .profile-btn:hover {
        background: rgba({{ $primaryRgb }}, 0.05) !important;
        border-color: var(--primary-color) !important;
        transform: translateY(-1px) !important;
    }

    .profile-btn:hover i {
        transform: scale(1.05) !important;
    }

    .profile-btn:active {
        transform: translateY(0) !important;
    }

    /* Header center: visible cleanly on wide screens without collision */
    .header-center {
        position: absolute !important;
        left: 50% !important;
        transform: translateX(-50%) !important;
        text-align: center !important;
        display: none !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        pointer-events: none !important;
        white-space: nowrap !important;
        max-width: calc(100% - 620px) !important;
        overflow: hidden !important;
    }

    .logo-text {
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        text-align: center !important;
    }

    /* Fixed alignment for header parts */
    .header-left {
        display: flex !important;
        align-items: center !important;
        gap: 1rem !important;
        z-index: 2 !important;
        flex-shrink: 0 !important;
    }

    .header-right {
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
        z-index: 2 !important;
        margin-left: auto !important;
        flex-shrink: 0 !important;
    }

    @media (max-width: 992px) {
        .header-title {
            display: none !important;
            /* Hide long title on mobile/tablet to avoid overlap */
        }
    }

    /* Show header center text only on desktop screens where width >= 1150px to prevent overlap */
    @media (min-width: 1150px) {
        .header-center {
            display: flex !important;
        }
    }

    /* ── Logo and link base styles (must be before mobile overrides) ── */
    .logo-link {
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
        height: auto !important;
    }

    /* Default (desktop) logo size */
    .header-logo {
        height: 70px !important;
        width: auto !important;
        object-fit: contain !important;
        mix-blend-mode: multiply !important;
        /* Enhances clarity and visual pop - removes "dull" look */
        filter: brightness(1.08) contrast(1.08) saturate(1.1);
    }

    /* Mobile header adjustments */
    @media (max-width: 767px) {
        .header-container {
            padding: 0 1rem !important;
            height: 75px !important;
        }

        .header-logo {
            height: 46px !important;
            mix-blend-mode: multiply !important;
            filter: brightness(1.08) contrast(1.08) saturate(1.1);
        }

        .help-btn,
        .btn-header-back {
            padding: 0 !important;
            width: 36px !important;
            height: 36px !important;
            border-radius: 50% !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            box-sizing: border-box !important;
            flex-shrink: 0 !important;
        }

        .help-btn span,
        .btn-header-back span {
            display: none !important;
        }

        .help-btn i,
        .btn-header-back i {
            font-size: 1.1rem !important;
            margin: 0 !important;
            color: #ffffff !important;
        }

        .profile-btn {
            width: 36px !important;
            height: 36px !important;
        }

        .profile-btn i {
            font-size: 1.35rem !important;
        }

        .header-left,
        .header-right {
            gap: 0.5rem !important;
        }

        .room-nav-btn,
        .hero-prev,
        .hero-next {
            display: none !important;
        }
    }

    /* Tablet header adjustments */
    @media (min-width: 768px) and (max-width: 1279px) {
        .header-container {
            padding: 0 2rem !important;
            height: 85px !important;
        }

        .header-logo {
            height: 72px !important;
            mix-blend-mode: multiply !important;
        }
    }


    .border-primary,
    .room-highlights,
    .form-section-title {
        border-color: var(--primary-color) !important;
    }

    /* Input focus */
    .form-input:focus,
    .form-select:focus,
    input:focus,
    select:focus,
    textarea:focus {
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 3px rgba({{ $primaryRgb }}, 0.15) !important;
    }

    /* Radio/Checkbox */
    input[type="radio"],
    input[type="checkbox"] {
        accent-color: var(--primary-color) !important;
    }



    /* Modal & Popups */
    .modal-close {
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1) !important;
    }

    .modal-close:hover {
        background-color: var(--primary-color) !important;
        color: #fff !important;
        transform: rotate(90deg) scale(1.1) !important;
    }

    .facility-item i {
        color: var(--primary-color) !important;
    }

    .dropdown-option:hover {
        background-color: rgba({{ $primaryRgb }}, 0.08) !important;
        color: var(--primary-color) !important;
    }

    /* Form Fields */
    .form-input:focus,
    .form-select:focus,
    input:focus,
    select:focus,
    textarea:focus {
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 4px rgba({{ $primaryRgb }}, 0.1) !important;
    }

    /* Hover states */
    .btn-outline:hover,
    .btn-outline:active,
    .btn-outline:focus {
        background-color: var(--primary-color) !important;
        border-color: var(--primary-color) !important;
        color: #ffffff !important;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba({{ $primaryRgb }}, 0.3) !important;
    }

    .btn:not(.btn-outline):hover,
    .btn:not(.btn-outline):active,
    .btn:not(.btn-outline):focus,
    .btn-primary:hover,
    .btn-primary:active,
    .btn-primary:focus,
    .submit-btn:hover,
    .submit-btn:active,
    .submit-btn:focus,
    .help-send-btn:hover,
    .help-send-btn:active,
    .help-send-btn:focus,
    .confirm-booking-btn:hover,
    .confirm-booking-btn:active,
    .confirm-booking-btn:focus {
        background-color: var(--primary-color) !important;
        filter: brightness(90%) !important;
        color: #ffffff !important;
    }

    .btn-outline {
        border-color: var(--primary-color) !important;
        color: var(--primary-color) !important;
    }

    /* Sidebar and Navigation */
    .sidebar-menu a.active {
        color: var(--primary-color) !important;
        background: rgba({{ $primaryRgb }}, 0.08) !important;
        border-left: 4px solid var(--primary-color) !important;
    }

    .nav-link:hover,
    .menu-item:hover {
        color: var(--primary-color) !important;
    }

    .hero-dot.active {
        background-color: var(--primary-color) !important;
    }

    /* =============================================================
       STRICT UI UPDATE - HEADER & HERO
       ============================================================= */

    /* 7. Smooth Scroll */
    html {
        scroll-behavior: smooth;
    }

    /* 1. Header Logo Size Fix (CRITICAL VISIBILITY) */
    .header-logo {
        height: 62px !important;
        /* Mobile */
        width: auto !important;
        object-fit: contain !important;
        transition: all 0.3s ease !important;
        mix-blend-mode: multiply !important;
    }
    
    .header-logo:hover {
        transform: scale(1.08) !important;
    }

    @media (min-width: 768px) {
        .header-logo {
            height: 75px !important;
            /* Tablet */
            mix-blend-mode: multiply !important;
        }
    }

    @media (min-width: 1024px) {
        .header-logo {
            height: 85px !important;
            /* Desktop */
            mix-blend-mode: multiply !important;
        }
    }

    /* 2. Compact Hero Banner */
    .main-image-slider {
        height: 600px !important;
        min-height: 600px !important;
        position: relative !important;
        overflow: hidden !important;
    }

    @media (max-width: 767px) {
        .main-image-slider {
            height: 450px !important;
            min-height: 450px !important;
        }
    }

    /* 3. Modern Hero Gradient Overlay (Professional Corporate Style) */
    .hero-slide::before {
        content: '';
        position: absolute;
        inset: 0;
        /* Balanced gradient for centered text: darker in middle/left, clear on right */
        background: linear-gradient(to right, 
            rgba(0, 0, 0, 0.6) 0%, 
            rgba(0, 0, 0, 0.4) 40%, 
            rgba(0, 0, 0, 0.1) 70%, 
            transparent 100%) !important;
        backdrop-filter: blur(1.5px);
        /* Extremely subtle for readability */
        -webkit-backdrop-filter: blur(1.5px);
        z-index: 1;
    }

    /* Ensure the Right side is explicitly clear - we can use a mask or just reliable gradient */
    .hero-slide {
        overflow: hidden;
    }

    /* Ensure text layers are above the overlay */
    .hero-layer {
        z-index: 5 !important;
        background: transparent !important;
        /* Centered alignment for natural balance with the header */
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        text-align: center !important;
        padding: 0 10% !important;
    }

    .hero-slide img {
        z-index: 0;
        object-fit: cover !important;
    }

    /* 4. Text Visibility Improvement */
    .slide-title {
        font-size: clamp(2rem, 8vw, 4rem) !important;
        font-weight: 800 !important;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.7) !important;
        /* Exact request */
        margin-bottom: 0.8rem !important;
        color: #fff !important;
    }

    .slide-subtitle {
        font-size: clamp(1rem, 3vw, 1.3rem) !important;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.7) !important;
        /* Exact request */
        color: #fff !important;
        max-width: 800px;
    }

    @keyframes cta-pulse {
        0% {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25), 0 0 0 0px rgba({{ $primaryRgb }}, 0.85);
            transform: scale(1) translate3d(0, 0, 0);
            filter: brightness(1);
        }
        50% {
            box-shadow: 0 6px 22px rgba(0, 0, 0, 0.2), 0 0 0 14px rgba({{ $primaryRgb }}, 0);
            transform: scale(1.04) translate3d(0, 0, 0);
            filter: brightness(1.25); /* Flashes brighter like a light */
        }
        100% {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25), 0 0 0 0px rgba({{ $primaryRgb }}, 0);
            transform: scale(1) translate3d(0, 0, 0);
            filter: brightness(1);
        }
    }

    .banner-cta {
        margin-top: 2rem !important;
        padding: 0.8rem 2.5rem !important;
        background: var(--primary-color) !important; /* Solid theme color */
        color: #fff !important;
        font-size: 0.95rem !important;
        font-weight: 700 !important;
        border-radius: 50px !important;
        letter-spacing: 1px !important;
        text-transform: uppercase !important;
        position: relative !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        pointer-events: auto !important;
        text-decoration: none !important;
        border: none !important;
        z-index: 1 !important;
        
        /* Active beacon click blinking animation */
        animation: cta-pulse 1.2s infinite ease-in-out !important;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
        
        /* Smooth anti-aliasing */
        transform: translate3d(0, 0, 0) !important;
        -webkit-backface-visibility: hidden !important;
        backface-visibility: hidden !important;
    }

    .banner-cta:hover {
        transform: scale(1.06) translateY(-2px) translate3d(0, 0, 0) !important;
        filter: brightness(1.1) !important;
        animation: none !important; /* Lock glow on hover */
        box-shadow: 0 8px 25px rgba({{ $primaryRgb }}, 0.65) !important;
        color: #fff !important;
    }

    /* =============================================================
       STRICT UI SPACING NORMALIZATION (SPACING REDUCTION)
       ============================================================= */

    /* 1. Global Section Spacing & 5. Section Gap Rule */
    main>section {
        padding-top: 30px !important;
        padding-bottom: 30px !important;
        margin-top: 0 !important;
        margin-bottom: 0 !important;
        width: 100% !important;
        box-sizing: border-box !important;
        /* 2. Container Width & Alignment */
        max-width: 1200px !important;
        margin-left: auto !important;
        margin-right: auto !important;
        padding-left: 16px !important;
        padding-right: 16px !important;
    }

    /* 1 (Special Cases). First and Last Section Padding */
    main>section:first-of-type {
        padding-top: 40px !important;
    }

    main>section:last-of-type {
        padding-bottom: 40px !important;
    }

    /* 3. Heading & Subtext Spacing */
    .title-section h2,
    main h2 {
        margin-bottom: 6px !important;
    }

    .title-section p,
    main p,
    .welcome-subtitle {
        margin-bottom: 20px !important;
        margin-top: 0 !important;
    }

/* Standardized Card Grid Alignment (Parallel Buttons) */
.rooms-grid {
    display: grid !important;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)) !important;
    align-items: stretch !important;
    gap: 24px !important;
}

.card {
    display: flex !important;
    flex-direction: column !important;
    height: 100% !important;
}

.card-content {
    flex: 1 !important;
    display: flex !important;
    flex-direction: column !important;
    height: 100% !important;
}

.card-actions {
    margin-top: auto !important;
}

    /* Slider Specific Width Normalization (Wider) */
    .explore-rooms-section,
    .slider-master-container {
        max-width: 1400px !important;
        width: 100% !important;
        padding-left: 8px !important;
        padding-right: 8px !important;
    }

    .slider-outer-frame {
        padding: 0 10px !important;
    }

    /* 4. Card Inner Details */
    .card-content,
    .slider-card .card-content {
        padding: 16px !important;
    }

    .card-image-wrapper,
    .slider-card .card-image-wrapper {
        margin-bottom: 12px !important;
    }

    .card-btn-wrapper,
    .card-actions {
        margin-top: 12px !important;
    }

    /* 6. About Section Spacing */
    .premium-facility-card {
        padding: 32px !important;
        margin: 0 auto !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }

    .premium-facility-card .desc-content p {
        font-family: 'Inter', sans-serif !important;
        text-align: left !important;
    }

    /* 7. Footer Spacing */
    .main-footer {
        margin-top: 0 !important;
    }

    .main-footer .footer-content {
        padding-top: 3rem !important;
        padding-bottom: 2rem !important;
    }

    /* 8. Removal of Random Large Spacing */
    header,
    .main-header {
        margin-bottom: 0 !important;
    }

    main {
        padding: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
    }

    /* Clean rhythm for room details/descriptions if they appear */
    .description {
        margin-bottom: 16px !important;
    }

    /* =============================================================
       STRICT FIX: BROWSE ALL ROOMS ALIGNMENT & LAYOUT
       ============================================================= */

    .dashboard-rooms-grid {
        display: grid !important;
        grid-template-columns: 1fr !important;
        gap: 20px !important;
        align-items: stretch !important;
        width: 100% !important;
    }

    @media (min-width: 768px) {
        .dashboard-rooms-grid {
            grid-template-columns: repeat(2, 1fr) !important;
        }
    }

    @media (min-width: 1024px) {
        .dashboard-rooms-grid {
            grid-template-columns: repeat(3, 1fr) !important;
            gap: 24px !important;
        }
    }

    .premium-card {
        display: flex !important;
        flex-direction: column !important;
        height: 100% !important;
        background: #ffffff !important;
        border-radius: 12px !important;
        overflow: hidden !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02) !important;
        transition: all 0.3s ease !important;
    }

    .premium-card:hover {
        border-color: var(--primary-color) !important;
        box-shadow: 0 12px 24px rgba({{ $primaryRgb }}, 0.08) !important;
        transform: translateY(-4px) !important;
    }

    .premium-card .card-image-wrapper {
        height: 210px !important;
        width: 100% !important;
        margin-bottom: 0 !important;
        position: relative !important;
        overflow: hidden !important;
    }

    .premium-card .card-image-wrapper img {
        height: 100% !important;
        width: 100% !important;
        object-fit: cover !important;
        transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1) !important;
    }

    .premium-card:hover .card-image-wrapper img {
        transform: scale(1.05) !important;
    }

    .premium-card .card-content {
        flex: 1 !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: flex-start !important;
        padding: 24px !important;
    }

    .premium-card h2,
    .premium-card h3 {
        font-family: 'Outfit', sans-serif !important;
        font-size: 1.35rem !important;
        font-weight: 700 !important;
        color: var(--primary-color) !important;
        margin-bottom: 8px !important;
        margin-top: 0 !important;
        min-height: auto !important;
        display: block !important;
    }

    .premium-card .description {
        font-family: 'Inter', sans-serif !important;
        font-size: 0.88rem !important;
        line-height: 1.6 !important;
        min-height: 72px !important;
        margin-bottom: 16px !important;
        color: #64748b !important;
        flex: 1 !important;
    }

    .premium-card .card-btn-wrapper {
        margin-top: auto !important;
        padding-top: 8px !important;
    }

    .premium-card .view-details-btn {
        width: 100% !important;
        justify-content: center !important;
        border: 1px solid var(--primary-color) !important;
        color: var(--primary-color) !important;
        background: transparent !important;
        font-weight: 700 !important;
        font-size: 0.8rem !important;
        letter-spacing: 0.5px !important;
        text-transform: uppercase !important;
        border-radius: 6px !important;
        height: 42px !important;
        display: inline-flex !important;
        align-items: center !important;
        transition: all 0.2s ease !important;
        box-shadow: none !important;
    }

    .premium-card .view-details-btn:hover {
        background: var(--primary-color) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba({{ $primaryRgb }}, 0.2) !important;
    }

    /* Admin Sidebar Logo Enhancement */
    .sidebar-header {
        padding: 1rem 0.75rem !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        box-sizing: border-box !important;
    }

    .sidebar-logo {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 100% !important;
    }

    .sidebar-logo img {
        height: 110px !important;
        width: auto !important;
        object-fit: contain !important;
        transition: transform 0.3s ease !important;
    }

    .sidebar-logo img:hover {
        transform: scale(1.06) !important;
    }

    /* Premium footer headers style */
    .main-footer .footer-column h4 {
        color: #0f172a !important; /* Darker and more premium slate-900 */
        font-weight: 700 !important;
        margin-bottom: 1.2rem !important; /* Give more breathing space */
        padding-bottom: 0.5rem !important;
        position: relative;
        display: inline-block;
        font-size: 1.15rem !important;
        letter-spacing: -0.2px !important;
    }

    .main-footer .footer-column h4::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 32px !important;
        height: 3px !important;
        background: var(--primary-color) !important;
        border-radius: 4px !important;
        z-index: 2;
    }

    /* Elegant longer backing line for the header border */
    .main-footer .footer-column h4::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100% !important;
        height: 1px !important;
        background: #e2e8f0 !important; /* slate-200 border */
        border-radius: 4px !important;
        z-index: 1;
    }

    /* Premium dynamic footer social hover shadow */
    .footer-social-icons a:hover {
        box-shadow: 0 5px 15px rgba({{ $primaryRgb }}, 0.3) !important;
    }

    /* Premium dynamic theme footer background and border */
    /* Premium dynamic theme footer background and border */
    .main-footer {
        background: var(--primary-color, #850f0f) !important;
        border-top: 3px solid rgba(255, 255, 255, 0.2) !important;
        color: rgba(255, 255, 255, 0.9) !important;
    }

    .main-footer .footer-content {
        background: var(--primary-color, #850f0f) !important;
    }

    .main-footer .footer-column h4 {
        color: #ffffff !important; /* Pure white headers */
    }

    .main-footer .footer-column h4::after {
        background: #ffffff !important; /* White accent bar */
    }

    .main-footer .footer-column h4::before {
        background: rgba(255, 255, 255, 0.2) !important; /* White divider line */
    }

    .main-footer .footer-column ul li a {
        color: rgba(255, 255, 255, 0.9) !important; /* Light white links */
    }

    .main-footer .footer-column ul li a:hover {
        color: #ffffff !important;
        transform: translateX(4px);
    }

    .main-footer .footer-contact-link {
        color: rgba(255, 255, 255, 0.9) !important;
    }

    .main-footer .footer-contact-link:hover {
        color: #ffffff !important;
    }

    .main-footer .footer-column p {
        color: rgba(255, 255, 255, 0.85) !important;
    }

    .main-footer .footer-column i {
        color: #ffffff !important; /* White icons for high contrast */
    }

    .main-footer .footer-bottom {
        background: var(--primary-color, #850f0f) !important;
        border-top: 1px solid rgba(255, 255, 255, 0.15) !important;
        color: rgba(255, 255, 255, 0.85) !important;
    }

    .main-footer .footer-bottom p {
        color: rgba(255, 255, 255, 0.85) !important;
    }

    .main-footer .mcc-text {
        color: #ffffff !important;
    }

    .main-footer .igh-text {
        color: rgba(255, 255, 255, 0.8) !important;
    }

    /* Social icons on themed background */
    .footer-social-icons a {
        background: rgba(255, 255, 255, 0.1) !important;
        color: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.15) !important;
    }

    .footer-social-icons a:hover {
        background: #ffffff !important;
        @if($useSecondary == '1')
            color: var(--secondary-color) !important;
        @else
            color: var(--primary-color) !important;
        @endif
        box-shadow: 0 5px 15px rgba(255, 255, 255, 0.35) !important;
    }

    .footer-social-icons a i {
        color: #ffffff !important;
        transition: color 0.3s ease !important;
    }

    .footer-social-icons a:hover i {
        @if($useSecondary == '1')
            color: var(--secondary-color) !important;
        @else
            color: var(--primary-color) !important;
        @endif
    }

    /* =============================================================
       MOBILE RESPONSIVE OVERRIDES FOR ADMIN INTERFACE
       ============================================================= */
    .admin-main {
        margin-left: var(--sidebar-width, 240px) !important;
        width: calc(100% - var(--sidebar-width, 240px)) !important;
        max-width: calc(100% - var(--sidebar-width, 240px)) !important;
        box-sizing: border-box !important;
        transition: all 0.3s ease !important;
    }

    @media (max-width: 1024px) {
        .admin-main {
            margin-left: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
        }
        .sidebar.open {
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.15) !important;
            transform: translateX(0) !important;
        }
    }

    @media (max-width: 768px) {
        .data-table th, .data-table td,
        .mini-table th, .mini-table td,
        .report-table-container table th, .report-table-container table td,
        table th, table td {
            padding: 0.6rem 0.75rem !important;
            font-size: 0.8rem !important;
            white-space: nowrap !important;
        }
        .admin-body {
            padding: 1rem !important;
        }
    }

    @media (max-width: 640px) {
        /* Hide text inside action links in top navbar, leaving only icon */
        .nav-btn-text {
            display: none !important;
        }

        .top-navbar a[href*="college-guest"],
        .top-navbar a[href*="export"],
        .top-navbar a[href*="reports/download"],
        .top-navbar a[href*="bookings/export"],
        .top-navbar .btn-outline {
            font-size: 0 !important;
            padding: 0 !important;
            width: 38px !important;
            height: 38px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 0 !important;
            border-radius: 10px !important;
            flex-shrink: 0 !important;
        }
        .top-navbar a[href*="college-guest"] i,
        .top-navbar a[href*="export"] i,
        .top-navbar a[href*="reports/download"] i,
        .top-navbar a[href*="bookings/export"] i,
        .top-navbar .btn-outline i {
            font-size: 1.25rem !important;
            margin: 0 !important;
        }
        
        .top-navbar {
            padding: 0 1rem !important;
            gap: 0.5rem !important;
        }
        
        /* Make form elements stack nicely */
        .filter-form, .filter-section {
            flex-direction: column !important;
            align-items: stretch !important;
        }
        .filter-form > div, .filter-section > div {
            width: 100% !important;
        }
    }

    /* GLOBAL MOBILE OVERRIDES FOR STABILITY */
    @media (max-width: 767px) {
        .rooms-grid, .dashboard-rooms-grid {
            grid-template-columns: 1fr !important;
            gap: 1.25rem !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }
        .card {
            width: 100% !important;
            max-width: 100% !important;
            height: auto !important;
            box-sizing: border-box !important;
        }
        .card-content {
            height: auto !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }
        .room-highlights, .room-features-box {
            width: 100% !important;
            box-sizing: border-box !important;
        }
    }

    /* Highlighted GST Badge style */
    .gst-text {
        background-color: #f0fdf4 !important;
        color: #15803d !important;
        border: 1px solid #bbf7d0 !important;
        padding: 4px 10px !important;
        border-radius: 8px !important;
        font-size: 0.74rem !important;
        font-weight: 700 !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
        width: fit-content !important;
        margin-top: 8px !important;
        margin-bottom: 16px !important;
        min-height: auto !important;
        box-shadow: 0 1px 2px rgba(22, 163, 74, 0.05) !important;
    }
</style>

<script>
    window.primaryColor = "{{ $primaryColor }}";
    window.primaryColorRGB = "{{ $primaryRgb }}";

    // "Nuclear" fix: Scan the DOM for hardcoded orange and replace it
    function applyDynamicTheme() {
        const orangeShades = [
            "rgb(133, 15, 15)",   // #850f0f (Main)
            "rgb(255, 106, 0)",   // #ff6a00
            "rgb(230, 109, 0)",   // #e66d00
            "rgb(204, 94, 0)",    // #cc5e00
            "rgb(133, 15, 15)",   // Repeated for safety
            "rgb(255, 121, 0)",   // slight variant
            "rgba(133, 15, 15, 0.85)",
            "rgba(133, 15, 15, 0.3)",
            "rgba(133, 15, 15, 0.15)",
            "rgba(133, 15, 15, 0.1)",
            "rgb(255, 154, 0)",
            "rgb(255, 165, 0)"
        ];
        const allElements = document.getElementsByTagName('*');
        for (let i = 0; i < allElements.length; i++) {
            const el = allElements[i];

            // Skip interactive elements whose states (hover, active, focus) must remain controlled purely by CSS
            if (el.matches('.tab-btn, .btn, [class*="btn-"], button, .submit-btn')) continue;

            const style = window.getComputedStyle(el);

            // Background
            if (orangeShades.includes(style.backgroundColor)) {
                el.style.setProperty('background-color', window.primaryColor, 'important');
            }
            // Transition rgba backgrounds
            if (style.backgroundColor.startsWith('rgba(133, 15, 15')) {
                const alpha = style.backgroundColor.split(',').pop().replace(')', '').trim();
                el.style.setProperty('background-color', `rgba(${window.primaryColorRGB}, ${alpha})`, 'important');
            }

            // Border
            if (orangeShades.includes(style.borderColor)) {
                el.style.setProperty('border-color', window.primaryColor, 'important');
            }
            // Border color starts with rgba
            if (style.borderColor.startsWith('rgba(133, 15, 15')) {
                const alpha = style.borderColor.split(',').pop().replace(')', '').trim();
                el.style.setProperty('border-color', `rgba(${window.primaryColorRGB}, ${alpha})`, 'important');
            }

            // Text Color
            if (orangeShades.includes(style.color)) {
                el.style.setProperty('color', window.primaryColor, 'important');
            }
        }
    }

    function initDynamicThemeObserver() {
        if (!document.body) return;

        let isUpdating = false;
        const observer = new MutationObserver(() => {
            if (isUpdating) return;
            isUpdating = true;
            applyDynamicTheme();
            setTimeout(() => { isUpdating = false; }, 100);
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true,
            attributes: true,
            attributeFilter: ['style', 'class']
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            applyDynamicTheme();
            initDynamicThemeObserver();
        });
    } else {
        applyDynamicTheme();
        initDynamicThemeObserver();
    }
</script>