<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conference & Glass Rooms - MCC IGH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <style>
        /* =============================================================
           PREMIUM ROOMS PAGE DESIGN OVERRIDES
        ============================================================= */
        
        /* Modern Header Hero Card */
        .page-hero-card {
            position: relative !important;
            margin: 1.5rem 0 3rem 0 !important;
            padding: 3.5rem 3rem !important;
            background: linear-gradient(135deg, rgba(var(--primary-rgb), 0.05) 0%, rgba(var(--primary-rgb), 0.01) 100%) !important;
            border: 1.5px solid rgba(var(--primary-rgb), 0.15) !important;
            border-radius: 24px !important;
            box-shadow: 0 10px 30px rgba(var(--primary-rgb), 0.02) !important;
            overflow: hidden !important;
        }

        .page-hero-card::before {
            content: '' !important;
            position: absolute !important;
            right: -40px !important;
            top: -40px !important;
            width: 200px !important;
            height: 200px !important;
            border-radius: 50% !important;
            border: 2px dashed rgba(var(--primary-rgb), 0.08) !important;
            background: transparent !important;
            left: auto !important;
            bottom: auto !important;
        }

        .page-hero-card::after {
            content: '' !important;
            position: absolute !important;
            right: -70px !important;
            top: -70px !important;
            width: 280px !important;
            height: 280px !important;
            border-radius: 50% !important;
            border: 2px dashed rgba(var(--primary-rgb), 0.08) !important;
            background: transparent !important;
            pointer-events: none !important;
        }

        .page-hero-card-badge {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0.4rem 1rem !important;
            border: 1.5px solid var(--primary-color) !important;
            border-radius: 9999px !important;
            font-size: 0.8rem !important;
            font-weight: 700 !important;
            background: rgba(var(--primary-rgb), 0.05) !important;
            letter-spacing: 0.5px !important;
            text-transform: uppercase !important;
            margin-bottom: 0.8rem !important;
            color: var(--primary-color) !important;
        }

        .page-hero-card h1 {
            font-size: clamp(2rem, 5vw, 2.8rem) !important;
            font-weight: 800 !important;
            margin: 0.2rem 0 0.8rem 0 !important;
            color: #0f172a !important;
            letter-spacing: -1.2px !important;
            line-height: 1.1 !important;
        }

        .page-hero-card p {
            color: #475569 !important;
            font-size: 1rem !important;
            font-weight: 500 !important;
            line-height: 1.65 !important;
            max-width: 850px !important;
            margin: 0 !important;
        }

        /* Modern Room Cards — Equal Height Grid */
        .rooms-grid {
            display: grid !important;
            grid-template-columns: repeat(3, 1fr) !important;
            gap: 24px !important;
            align-items: stretch !important;
        }
        @media (max-width: 1024px) {
            .rooms-grid { grid-template-columns: repeat(2, 1fr) !important; }
        }
        @media (max-width: 767px) {
            .rooms-grid { grid-template-columns: 1fr !important; }
        }

        .card { 
            display: flex !important; 
            flex-direction: column !important; 
            min-height: 0 !important; 
            height: 100% !important; 
            background: #ffffff !important;
            border-radius: 24px !important;
            border: 1px solid rgba(var(--primary-rgb), 0.08) !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02) !important;
            overflow: hidden !important;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1) !important;
        }

        .card:hover {
            transform: translateY(-8px) !important;
            box-shadow: 0 20px 40px rgba(var(--primary-rgb), 0.08) !important;
            border-color: rgba(var(--primary-rgb), 0.25) !important;
        }

        .card-image-wrapper {
            position: relative !important;
            overflow: hidden !important;
            border-radius: 24px 24px 0 0 !important;
        }

        .card-image-wrapper img {
            transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1) !important;
        }

        .card:hover .card-image-wrapper img {
            transform: scale(1.06) !important;
        }

        /* Status & Category Badges */
        .card-image-wrapper .badge {
            position: absolute !important;
            top: 1rem !important;
            left: 1rem;
            z-index: 5 !important;
            border-radius: 9999px !important;
            padding: 0.4rem 1rem !important;
            font-size: 0.78rem !important;
            font-weight: 700 !important;
            letter-spacing: -0.2px !important;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05) !important;
            border: 1px solid transparent !important;
            text-transform: none !important;
        }

        .card-image-wrapper .badge.status-available {
            background: rgba(5, 150, 105, 0.92) !important;
            color: #ffffff !important;
            border-color: rgba(5, 150, 105, 0.3) !important;
            backdrop-filter: blur(8px) !important;
            -webkit-backdrop-filter: blur(8px) !important;
            text-shadow: 0 1px 2px rgba(0,0,0,0.15) !important;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25) !important;
        }

        .card-image-wrapper .badge[style*="background:#dc3545"] {
            background: rgba(220, 53, 69, 0.92) !important;
            color: #ffffff !important;
            border-color: rgba(220, 53, 69, 0.3) !important;
            backdrop-filter: blur(8px) !important;
            -webkit-backdrop-filter: blur(8px) !important;
            text-shadow: 0 1px 2px rgba(0,0,0,0.15) !important;
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.25) !important;
        }

        .card-image-wrapper .badge[style*="background: #e0e0e0"] {
            top: 1rem !important;
            left: auto !important;
            right: 1rem !important;
            background: rgba(255, 255, 255, 0.85) !important;
            backdrop-filter: blur(8px) !important;
            color: #334155 !important;
            border-color: rgba(255, 255, 255, 0.5) !important;
        }

        .card-content { 
            flex: 1 !important; 
            display: flex !important; 
            flex-direction: column !important; 
            padding: 1.5rem 1.5rem 1.25rem !important;
        }

        /* Card Header */
        .card-header { 
            min-height: auto !important; 
            display: flex !important; 
            flex-direction: row !important; 
            justify-content: space-between !important;
            align-items: center !important;
            margin-bottom: 0.5rem !important;
            width: 100% !important;
        }

        .card h2 { 
            color: #0f172a !important; 
            font-weight: 800 !important; 
            font-size: 1.3rem !important; 
            margin: 0 !important; 
            line-height: 1.2 !important; 
            letter-spacing: -0.5px !important;
            font-family: 'Inter', sans-serif !important;
        }

        .card-header .rating {
            display: inline-flex !important;
            align-items: center !important;
            gap: 4px !important;
            font-size: 0.78rem !important;
            font-weight: 700 !important;
            color: var(--primary-color) !important;
            background: rgba(var(--primary-rgb), 0.08) !important;
            padding: 0.25rem 0.65rem !important;
            border-radius: 9999px !important;
            white-space: nowrap !important;
            flex-shrink: 0 !important;
        }

        .card .description { 
            color: #475569 !important; 
            font-weight: 500 !important; 
            margin-bottom: 0.75rem !important;
            line-height: 1.5 !important;
            font-size: 0.88rem !important;
            flex: none !important;
            min-height: 60px !important;
            display: -webkit-box !important;
            -webkit-line-clamp: 3 !important;
            -webkit-box-orient: vertical !important;
            overflow: hidden !important;
        }

        /* Room number badge (Suite) */
        .room-number-badge {
            display: inline-flex !important;
            align-items: center !important;
            gap: 5px !important;
            background: var(--primary-color) !important;
            color: #fff !important;
            font-size: 0.72rem !important;
            font-weight: 700 !important;
            padding: 3px 10px !important;
            border-radius: 20px !important;
            letter-spacing: 0.5px !important;
            margin-bottom: 0.5rem !important;
            width: fit-content !important;
            align-self: flex-start !important;
        }

        /* Pricing Section */
        .price-section {
            min-height: auto !important;
            display: flex !important;
            flex-direction: column !important;
            margin-bottom: 1.25rem !important;
            justify-content: center !important;
            align-items: flex-start !important;
        }

        .price-highlight { 
            min-height: auto !important; 
            display: flex !important; 
            align-items: baseline !important; 
            gap: 6px !important;
            margin-bottom: 0.15rem !important;
            font-size: 1.5rem !important;
            font-weight: 800 !important;
            color: var(--primary-color) !important;
            letter-spacing: -0.5px !important;
        }

        .price-highlight span {
            font-family: 'Inter', sans-serif !important;
            font-weight: 800 !important;
        }

        .price-highlight .rupee-symbol {
            font-size: 1.25rem !important;
            margin-right: 1px !important;
        }

        .price-highlight span + span, 
        .price-highlight .period-label {
            font-size: 0.95rem !important;
            font-weight: 600 !important;
            color: #64748b !important;
        }

        .gst-text {
            font-size: 0.76rem !important;
            font-weight: 700 !important;
            color: #15803d !important;
            background-color: #f0fdf4 !important;
            border: 1px solid #bbf7d0 !important;
            padding: 4px 10px !important;
            border-radius: 8px !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 4px !important;
        }

        .price-section {
            margin-bottom: 0.75rem !important;
        }

        /* Room Highlights Grid — Uniform Styling */
        .room-highlights, .room-features-box {
            margin-bottom: 0.75rem !important;
            padding: 1rem !important;
            background: rgba(var(--primary-rgb), 0.02) !important;
            border-radius: 14px !important;
            border: 1px solid rgba(var(--primary-rgb), 0.08) !important;
            min-height: auto !important;
            display: flex !important;
            flex-direction: column !important;
        }

        .features-title {
            font-size: 0.7rem !important;
            font-weight: 800 !important;
            color: var(--primary-color) !important;
            margin-bottom: 0.8rem !important;
            text-transform: uppercase !important;
            letter-spacing: 0.08em !important;
            font-family: 'Inter', sans-serif !important;
        }

        .features-grid {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            gap: 10px !important;
            font-size: 0.75rem !important;
            color: #666 !important;
            flex-grow: 1 !important;
        }

        .features-grid > div,
        .feature-item {
            display: flex !important;
            align-items: center !important;
            gap: 6px !important;
            padding: 0 !important;
            background: transparent !important;
            border: none !important;
            border-radius: 0 !important;
            font-size: 0.75rem !important;
            font-weight: 500 !important;
            color: #666 !important;
            white-space: nowrap !important;
            transition: all 0.2s ease !important;
            font-family: inherit !important;
        }

        .features-grid > div:hover,
        .feature-item:hover {
            color: var(--primary-color) !important;
        }

        .features-grid > div i,
        .feature-item i {
            color: var(--primary-color) !important;
            font-size: 0.9rem !important;
            flex-shrink: 0 !important;
        }

        .features-footer, .features-footer-text {
            font-size: 0.72rem !important;
            color: #64748b !important;
            background: transparent !important;
            border-left: none !important;
            padding: 0 !important;
            border-radius: 0 !important;
            font-weight: 600 !important;
            margin-top: auto !important;
            padding-top: 0.6rem !important;
            margin-bottom: 0px !important;
            font-style: italic !important;
            opacity: 0.8 !important;
        }

        /* Booking Status Banner */
        .next-available {
            margin-top: 0.25rem !important;
            margin-bottom: 1rem !important;
            padding: 0.75rem !important;
            background: rgba(var(--primary-rgb), 0.05) !important;
            border-radius: 12px !important;
            border: 1px solid rgba(var(--primary-rgb), 0.1) !important;
            text-align: center !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            color: var(--primary-color) !important;
            font-size: 0.82rem !important;
            font-weight: 600 !important;
        }

        .next-available p {
            margin: 0 !important;
            line-height: 1.4 !important;
        }

        .next-available-placeholder {
            height: 0px !important;
            margin: 0 !important;
        }

        /* Card Actions */
        .card-actions { 
            margin-top: auto !important; 
            display: flex !important; 
            gap: 12px !important; 
            width: 100% !important;
            padding-top: 1rem !important;
        }

        .card-actions .btn,
        .card-actions .btn-outline {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            height: 46px !important;
            border-radius: 9999px !important;
            font-weight: 700 !important;
            font-size: 0.88rem !important;
            transition: all 0.3s ease !important;
            flex: 1 !important;
            text-decoration: none !important;
        }

        .card-actions .btn-outline {
            border: 1.5px solid rgba(var(--primary-rgb), 0.25) !important;
            color: var(--primary-color) !important;
            background: transparent !important;
        }

        .card-actions .btn-outline:hover,
        .card-actions .btn-outline:active {
            background: var(--primary-color) !important;
            color: #ffffff !important;
            border-color: var(--primary-color) !important;
            box-shadow: 0 4px 12px rgba(var(--primary-rgb), 0.15) !important;
        }

        .card-actions .btn:not(.btn-outline) {
            background: var(--primary-color) !important;
            border: 1.5px solid var(--primary-color) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(var(--primary-rgb), 0.15) !important;
        }

        .card-actions .btn:not(.btn-outline):hover {
            filter: brightness(0.9) !important;
            box-shadow: 0 6px 16px rgba(var(--primary-rgb), 0.25) !important;
        }

        /* Booked Button Disabled Style Override */
        .card-actions .btn[style*="background: #bc8e8e"],
        .card-actions .btn[style*="background:#bc8e8e"] {
            background: #e2e8f0 !important;
            border-color: #e2e8f0 !important;
            color: #94a3b8 !important;
            cursor: not-allowed !important;
            opacity: 1 !important;
            box-shadow: none !important;
        }

        /* Detail Modal & Help Modal Styles (Local Preserves) */
        .modal-overlay {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0; 
            background: rgba(0,0,0,0.7) !important; backdrop-filter: blur(6px) !important;
            display: none; align-items: center; justify-content: center; z-index: 5000;
            opacity: 0; visibility: hidden; transition: all 0.3s ease;
            padding: 15px;
        }
        .modal-overlay.active { display: flex !important; opacity: 1 !important; visibility: visible !important; }
        .modal-card {
            background: white; border-radius: 20px; width: 100%; max-width: 500px; 
            position: relative; box-shadow: 0 25px 60px rgba(0,0,0,0.3);
            animation: modalPop 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            overflow: hidden; display: flex; flex-direction: column;
            margin: auto; line-height: 1.3; max-height: 95vh;
        }
        @keyframes modalPop {
            from { opacity: 0; transform: scale(0.9) translateY(30px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        .modal-close {
            position: absolute; top: 0.5rem; right: 0.5rem; background: rgba(255,255,255,0.9); border: none; 
            width: 28px; height: 28px; border-radius: 50%; box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; cursor: pointer; color: #333; transition: all 0.3s; z-index: 100;
        }
        .modal-close:hover { color: white; transform: rotate(90deg); }
        .modal-img-container { position: relative; width: 100%; height: 150px; overflow: hidden; flex-shrink: 0; }
        .room-img-modal { width: 100%; height: 100%; object-fit: cover; }
        .modal-body { padding: 1rem 1.25rem; flex: 1; display: flex; flex-direction: column; gap: 0.35rem; overflow: hidden; }
        .modal-title { font-size: 1.4rem; color: #111; margin: 0; font-weight: 800; letter-spacing: -0.5px; }
        .modal-price-line { font-size: 1rem; font-weight: 700; color: var(--primary-color); display: flex; align-items: baseline; gap: 4px; }
        .modal-divider { height: 1px; background: #eee; margin: 0.2rem 0; width: 100%; }
        .facility-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 6px; margin-top: 2px; }
        .facility-item { background: #f8f9fa; color: #444; padding: 6px 4px; border-radius: 6px; font-size: 0.75rem; display: flex; align-items: center; gap: 4px; border: 1px solid #eee; font-weight: 500; text-align: center; justify-content: center; flex-direction: column; }
        .facility-item i { font-size: 0.9rem; color: var(--primary-color); }

        .help-modal-overlay {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.6); backdrop-filter: blur(4px);
            display: none; align-items: center; justify-content: center; z-index: 6000;
            opacity: 0; visibility: hidden; transition: all 0.3s ease;
        }
        .help-modal-overlay.active { display: flex; opacity: 1; visibility: visible; }
        .help-modal-card {
            background: white; border-radius: 16px; width: 100%; max-width: 600px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.2); position: relative;
            padding: 40px; animation: modalSlideUp 0.4s ease; margin: 20px;
        }
        @keyframes modalSlideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .help-modal-close {
            position: absolute !important; top: 20px !important; right: 20px !important; left: auto !important;
            background: none !important; border: none !important; box-shadow: none !important;
            font-size: 1.5rem !important; color: #999 !important; cursor: pointer; transition: color 0.3s;
            display: flex !important; align-items: center !important; justify-content: center !important;
            width: 32px !important; height: 32px !important; padding: 0 !important;
            z-index: 10;
        }
        .help-modal-close:hover { color: #333 !important; background: none !important; box-shadow: none !important; transform: none !important; }
        .help-modal-title { text-align: center; font-size: 1.8rem; font-weight: 700; color: #111; margin-bottom: 25px; margin-top: 0; }
        .help-form { display: flex; flex-direction: column; gap: 15px; }
        .help-form-row { display: flex; flex-direction: column; gap: 15px; }
        .help-input-group { display: flex; flex-direction: column; gap: 6px; width: 100%; }
        .help-input-group.full-width { width: 100%; }
        .help-input-group label { font-size: 0.85rem; font-weight: 700; color: #444; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px; }
        .help-input-group input, .help-input-group select, .help-input-group textarea {
            padding: 14px 16px !important; border: 1px solid #ddd !important; border-radius: 8px !important;
            font-family: inherit; font-size: 1rem !important; transition: all 0.3s; background: #fafafa !important;
            width: 100% !important; display: block !important; box-sizing: border-box !important;
        }
        .help-input-group input:focus, .help-input-group select:focus, .help-input-group textarea:focus {
            border-color: var(--primary-color) !important; outline: none !important; background: #fff !important; box-shadow: 0 0 0 4px rgba(133, 15, 15, 0.1) !important;
        }

        .custom-dropdown { position: relative; width: 100%; }
        .dropdown-selected {
            padding: 14px 16px; border: 1px solid #ddd; border-radius: 8px;
            display: flex; justify-content: space-between; align-items: center;
            cursor: pointer; background: #fafafa; transition: 0.3s;
        }
        .dropdown-selected:hover { border-color: #bbb; }
        .dropdown-selected span { color: #333; font-weight: 500; }
        .dropdown-selected i { color: #999; font-size: 1.2rem; }
        .dropdown-options {
            position: absolute; top: calc(100% + 5px); left: 0; right: 0;
            background: white; border: 1px solid #ddd; border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1); z-index: 10;
            max-height: 250px; overflow-y: auto; display: none;
        }
        .dropdown-options.active { display: block; }
        .dropdown-option {
            padding: 12px 16px; font-size: 0.95rem; color: #444; cursor: pointer;
            transition: background 0.2s; border-bottom: 1px solid #f5f5f5;
        }
        .dropdown-option:last-child { border-bottom: none; }
        .dropdown-option:hover { background: #fff8f3; color: var(--primary-color); }
        .help-form-footer { display: flex; justify-content: flex-end; margin-top: 10px; }
        .help-send-btn {
            background: var(--primary-color) !important;
            color: #ffffff !important;
            border: none !important;
            padding: 0.9rem 1.5rem !important;
            border-radius: 12px !important;
            font-size: 0.95rem !important;
            font-weight: 800 !important;
            letter-spacing: 0.5px !important;
            text-transform: uppercase !important;
            cursor: pointer !important;
            width: 100% !important;
            display: block !important;
            transform: none !important;
            box-shadow: 0 4px 14px rgba(var(--primary-rgb, 133, 15, 15), 0.35) !important;
            transition: background 0.2s ease, box-shadow 0.2s ease !important;
        }
        .help-send-btn:hover {
            filter: brightness(90%) !important;
            box-shadow: 0 4px 18px rgba(var(--primary-rgb, 133, 15, 15), 0.45) !important;
            transform: none !important;
        }
        .help-send-btn:focus,
        .help-send-btn:active {
            background: var(--primary-color) !important;
            transform: none !important;
            box-shadow: 0 4px 14px rgba(var(--primary-rgb, 133, 15, 15), 0.35) !important;
        }
        .help-modal-card button:active {
            transform: none !important;
            scale: 1 !important;
        }

        @media (max-width: 767px) {
            .page-hero-card {
                padding: 2.25rem 1.5rem !important;
                margin-bottom: 2rem !important;
            }
            .card-content {
                padding: 1.25rem !important;
            }
            .card h2 {
                font-size: 1.25rem !important;
            }
            .price-highlight {
                font-size: 1.35rem !important;
            }
            .features-grid {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 8px !important;
            }
            .features-grid > div,
            .feature-item {
                white-space: normal !important;
            }
            .card-actions {
                flex-direction: column !important;
                gap: 10px !important;
            }
            .card-actions .btn, .card-actions .btn-outline {
                height: 44px !important;
                font-size: 0.9rem !important;
                width: 100% !important;
            }
            .card-header {
                display: grid !important;
                grid-template-columns: auto auto !important;
                justify-content: flex-start !important;
                align-items: center !important;
                gap: 6px 10px !important;
                margin-bottom: 0.75rem !important;
            }
            .card-header > div {
                display: contents !important;
            }
            .card-header h2 {
                grid-column: span 2 !important;
                font-size: 1.25rem !important;
                margin-bottom: 2px !important;
            }
            .card-header .room-number-badge,
            .card-header .rating {
                margin: 0 !important;
                font-size: 0.75rem !important;
                padding: 4px 10px !important;
                height: auto !important;
                line-height: 1 !important;
                display: inline-flex !important;
                align-items: center !important;
                gap: 4px !important;
            }
        }
    </style>
    @include('partials.dynamic-styles')
</head>
<body style="background: #fbfbfb;">
    @include('partials.header', ['headerBackBtn' => ['url' => route('home'), 'label' => 'Home'], 'showHelpBtn' => true])

    <main>
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 1.25rem;">
            <!-- Breadcrumbs -->
            <div class="breadcrumb">
                <a href="{{ route('home') }}">
                    <i class="ph ph-house"></i>
                    Home
                </a>
                <span class="breadcrumb-separator">›</span>
                <span class="breadcrumb-current">Conference & Glass Rooms</span>
            </div>

            <div class="page-hero-card">
                <span class="page-hero-card-badge">Events &amp; Meetings</span>
                <h1>Conference & Glass <span class="primary-text">Rooms</span></h1>
                <p>A versatile and professionally equipped venue designed for large-scale gatherings, corporate events, and interactive workshops with HD projection.</p>
            </div>

            @php
                $specialRooms = [
                    [
                        'name' => 'Conference Room', 
                        'capacity' => 60, 
                        'img' => asset('assets/standard/conference.JPG'), 
                        'desc' => 'Professional hall featuring HD projection, high-grade acoustics, and seating for 60 members.',
                        'room_no' => null,
                        'amenities' => [
                            ['name' => 'High-Speed WiFi', 'icon' => 'ph-wifi-high'],
                            ['name' => 'Projector / Screen', 'icon' => 'ph-projector-screen'],
                            ['name' => 'AC Space', 'icon' => 'ph-snowflake'],
                            ['name' => '60 Capacity', 'icon' => 'ph-users'],
                            ['name' => 'Presentation Setup', 'icon' => 'ph-chalkboard'],
                            ['name' => 'Sound System', 'icon' => 'ph-speaker-high'],
                        ],
                        'theme' => ['title' => 'Facility Features']
                    ],
                    [
                        'name' => 'Glass Room', 
                        'capacity' => 15, 
                        'img' => asset('assets/standard/glass.JPG'), 
                        'desc' => 'Modern transparent facility designed for collaborative brainstorming and focused team sessions with natural light.',
                        'room_no' => null,
                        'amenities' => [
                            ['name' => 'Modern Furniture', 'icon' => 'ph-armchair'],
                            ['name' => 'High Speed WiFi', 'icon' => 'ph-wifi-high'],
                            ['name' => 'Charging Ports', 'icon' => 'ph-plug-connected'],
                            ['name' => 'Glass Walls', 'icon' => 'ph-squares-four'],
                            ['name' => 'AC', 'icon' => 'ph-snowflake'],
                            ['name' => 'Presentation Setup', 'icon' => 'ph-presentation-chart'],
                        ],
                        'theme' => ['title' => 'Premium Features']
                    ],
                    [
                        'name' => 'Suite Room', 
                        'capacity' => 2, 
                        'img' => asset('assets/suite.JPG'), 
                        'desc' => 'Our flagship Suite Room offers the pinnacle of luxury, featuring a grand king-size bed and premium toiletries.',
                        'room_no' => '202',
                        'amenities' => [
                            ['name' => 'King Size Bed', 'icon' => 'ph-bed'],
                            ['name' => 'Smart TV', 'icon' => 'ph-television'],
                            ['name' => 'Mini Fridge', 'icon' => 'ph-snowflake'],
                            ['name' => 'Premium Toiletries', 'icon' => 'ph-spray-bottle'],
                            ['name' => 'Spacious Interior', 'icon' => 'ph-arrows-out'],
                            ['name' => 'Luxury Bathroom', 'icon' => 'ph-shower'],
                        ],
                        'theme' => ['title' => 'Luxury Features']
                    ],
                ];
            @endphp

            <!-- Room Grid -->
            <div class="rooms-grid">
                @foreach ($specialRooms as $room)
                @php $roomId = Str::slug($room['name']); @endphp
                <div class="card" data-name="{{ $room['name'] }}">
                    <div class="card-image-wrapper">
                        <img src="{{ $room['img'] }}" alt="{{ $room['name'] }}">
                        @if(isset($bookedRooms[$room['name']]) || isset($bookedRooms[$roomId]))
                            <span class="badge" style="background:#dc3545; color: white;">Booked</span>
                        @else
                            <span class="badge status-available">Available</span>
                        @endif
                        <span class="badge" style="top: 1rem; left: auto; right: 1rem; background: var(--primary-color); color: white;">Premium</span>
                    </div>
                    <div class="card-content">
                        <div class="card-header">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <h2>{{ $room['name'] }}</h2>
                                @if($room['room_no'])
                                    <span class="room-number-badge" style="margin-bottom: 0 !important; background: linear-gradient(135deg, #850f0f, #b91c1c) !important; box-shadow: 0 2px 4px rgba(133, 15, 15, 0.2);"><i class="ph-fill ph-crown" style="color: #fbbf24; font-size: 0.9rem;"></i> Room {{ $room['room_no'] }}</span>
                                @endif
                            </div>
                            <div class="rating"><i class="ph-fill ph-users"></i> {{ $room['capacity'] }} Members</div>
                        </div>
                        
                        <!-- 1. Description -->
                        <p class="description">{{ $room['desc'] }}</p>
                        
                        <!-- 2. Pricing Section -->
                        <div class="price-section">
                            <div class="price-highlight">
                                <span id="price-{{ $roomId }}"><span class="rupee-symbol">₹</span>2000</span> 
                                <span style="font-size: 0.85rem; font-weight: 500; color: var(--text-light);">for </span>
                                <span id="time-text-{{ $roomId }}" style="font-size: 0.85rem; font-weight: 500; color: var(--text-light);">4 hours</span>
                            </div>
                            <p class="gst-text"><i class="ph-bold ph-info" style="font-size: 0.85rem; margin-right: 4px; opacity: 0.85;"></i> + {{ $gstRate }}% GST applicable</p>
                        </div>
                        
                        <!-- 3. Room Features (Uniform) -->
                        <div class="room-features-box">
                            <h3 class="features-title">{{ $room['theme']['title'] }}</h3>
                            <div class="features-grid">
                                @foreach($room['amenities'] as $amenity)
                                <div>
                                    <i class="ph {{ $amenity['icon'] }}"></i> 
                                    {{ $amenity['name'] }}
                                </div>
                                @endforeach
                            </div>
                            <p class="features-footer-text">
                                {{ $room['name'] === 'Suite Room' ? 'Luxury stay with maximum comfort' : ($room['name'] === 'Glass Room' ? 'Designed for transparency and innovation' : 'Perfect for meetings and collaboration') }}
                            </p>
                        </div>

                        @php 
                            $bookedInfo = $bookedRooms[$room['name']] ?? $bookedRooms[$roomId] ?? null;
                        @endphp
                        @if($bookedInfo)
                            <div class="next-available" style="margin-bottom: 0.5rem; padding: 0.5rem; background: #fff1f2; border-radius: 8px; border: 1px solid #fee2e2; text-align: center; min-height: 60px; display: flex; align-items: center; justify-content: center;">
                                <p style="font-size: 0.7rem; font-weight: 700; color: #991b1b; margin: 0;">
                                    <i class="ph-bold ph-clock-countdown"></i> Booked - Next: {{ date('d M, Y', strtotime($bookedInfo['date'])) }}
                                </p>
                            </div>
                        @else
                            <div class="next-available-placeholder" style="display: none;"></div>
                        @endif

                        <div class="card-actions" style="margin-top: auto;">
                            <a href="{{ route('room.details', ['id' => $roomId]) }}" class="btn btn-outline" style="flex: 1; justify-content: center; text-align: center; text-transform: uppercase;">View Details</a>
                            <a href="{{ route('booking.form.full', ['room' => $room['name']]) }}" class="btn" style="flex: 1; justify-content: center; text-transform: uppercase; {{ $bookedInfo ? 'opacity: 0.7; pointer-events: none; background: #bc8e8e; border-color: #bc8e8e;' : '' }}">
                                {{ $bookedInfo ? 'Booked' : 'Book Now' }}
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </main>@include('partials.footer')

    <!-- Modal Modal -->
    <div class="modal-overlay" id="detailsModal">
        <div class="modal-card">
            <button class="modal-close" onclick="closeModal('detailsModal')"><i class="ph-bold ph-x"></i></button>
            <div class="modal-img-container">
                <img src="" alt="Room" class="room-img-modal" id="modalImg">
            </div>
            
            <div class="modal-body">
                <h2 class="modal-title" id="modalRoomTitle">Room Details</h2>
                
                <div class="modal-price-line">
                    <span id="modalRoomPrice">₹0</span> 
                    <span style="font-size: 0.95rem; color: #666; font-weight: 600;" id="modalRoomTime">/ period</span>
                    <span class="gst-text" style="margin-left: 8px;"><i class="ph-bold ph-info" style="font-size: 0.85rem; margin-right: 4px; opacity: 0.85;"></i> + {{ $gstRate }}% GST</span>
                </div>

                <p style="color: #666; line-height: 1.4; font-size: 0.85rem; margin: 0.25rem 0;" id="modalRoomDesc"></p>
                
                <div class="modal-divider"></div>
                
                <h3 style="font-size: 1rem; font-weight: 800; color: #111; margin: 0.25rem 0 0.5rem 0; display: flex; align-items: center; gap: 6px;">
                    Room Facilities
                </h3>
                <div class="facility-grid" id="modalFacilitiesContainer">
                    <!-- Dynamic Facilities -->
                </div>
            </div>

            <div class="modal-footer" style="padding: 1rem 1.25rem; background: #fff; border-top: 1px solid #eee; flex-shrink: 0;">
                <a id="modalBookNowBtn" href="#" class="btn" style="width:100%; text-align: center; border-radius: 10px; font-weight: 700; font-size: 1rem; background: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; padding: 0.75rem;">
                    Proceed to Booking Form <i class="ph-bold ph-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Help Modal -->
    @include('partials.help-modal')


    <script>
        function calcSpecialPrice(id) {
            const h = document.getElementById('hours-' + id).value || 4;
            const price = h * 500;
            document.getElementById('price-' + id).innerHTML = '<span class="rupee-symbol">₹</span>' + price;
            document.getElementById('time-text-' + id).innerText = h + ' hours';
            
            // Update button data if needed, but the button stays fixed at original price for View Details usually
            // or we could dynamically update button data attributes too.
        }

        window.showQuickRoomDetails = function(btn) {
            const data = btn.dataset;
            document.getElementById('modalRoomTitle').innerText = data.name;
            document.getElementById('modalRoomPrice').innerText = data.price;
            document.getElementById('modalRoomTime').innerText = data.time;
            document.getElementById('modalImg').src = data.img;
            document.getElementById('modalRoomDesc').innerText = data.desc;
            
            const isBooked = data.booked === 'true';
            const bookBtn = document.getElementById('modalBookNowBtn');
            if (isBooked) {
                bookBtn.style.opacity = '0.5';
                bookBtn.style.cursor = 'not-allowed';
                bookBtn.style.pointerEvents = 'none';
                bookBtn.innerHTML = 'Currently Booked <i class="ph-bold ph-lock-key"></i>';
                bookBtn.removeAttribute('href');
            } else {
                bookBtn.style.background = 'var(--primary-color)';
                bookBtn.style.cursor = 'pointer';
                bookBtn.style.pointerEvents = 'auto';
                bookBtn.innerHTML = 'Proceed to Booking Form <i class="ph-bold ph-arrow-right"></i>';
                bookBtn.href = "{{ route('booking.form.full') }}?room=" + data.room;
            }
            
            const container = document.getElementById('modalFacilitiesContainer');
            container.innerHTML = '';
            const facilities = data.facilities.split(',');
            facilities.forEach(f => {
                const [name, icon] = f.split(':');
                if (name && icon) {
                    const div = document.createElement('div');
                    div.className = 'facility-item';
                    div.innerHTML = `<i class="ph-bold ${icon}"></i> <span>${name}</span>`;
                    container.appendChild(div);
                }
            });

            document.getElementById('detailsModal').classList.add('active');
        };

        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
        }



        document.addEventListener('DOMContentLoaded', () => {
            const detailsModal = document.getElementById('detailsModal');
            const helpModal = document.getElementById('helpModal');

            window.onclick = function(event) {
                if (event.target == detailsModal) closeModal('detailsModal');
                if (event.target == helpModal) closeHelpModal();
            }
        });
    </script>
</body>
</html>
