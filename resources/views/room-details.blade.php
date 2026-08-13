<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Details - MCC IGH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <style>
        :root {
            --primary-color: #FF6B35;
            --text-dark: #1a1a1a;
            --text-medium: #4a4a4a;
            --text-light: #7a7a7a;
            --border-light: #e0e0e0;
            --bg-light: #fafafa;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #fcfcfc;
            color: var(--text-medium);
            line-height: 1.6;
        }

        /* ===== GLOBAL RESET FOR THIS PAGE ===== */
        *, *::before, *::after { box-sizing: border-box; }
        html, body { width: 100%; overflow-x: hidden; }

        .details-container {
            max-width: 1300px;
            margin: 2rem auto;
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 2.5rem;
            padding: 0 2rem;
            width: 100%;
            box-sizing: border-box;
            align-items: start;
        }

        .main-content {
            min-width: 0; /* Prevent grid blowout */
            width: 100%;
        }

        /* Image Gallery & Actions */
        .gallery-section {
            background: transparent;
            margin-bottom: 2.25rem;
            width: 100%;
        }

        .main-img-wrapper {
            width: 100%;
            height: 480px;
            border-radius: 24px;
            overflow: hidden;
            cursor: pointer;
            box-shadow: 0 12px 35px rgba(0,0,0,0.08);
            border: 1px solid rgba(0,0,0,0.04);
            position: relative;
        }

        .main-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.3s ease, filter 0.3s ease;
        }

        .main-img-wrapper:hover img {
            transform: scale(1.04);
        }

        .main-img-wrapper:hover .img-zoom-hint {
            background: rgba(133, 15, 15, 0.9) !important;
            transform: scale(1.05);
            border-color: rgba(255, 255, 255, 0.4) !important;
        }

        .thumbnail-grid {
            display: flex;
            gap: 14px;
            padding: 18px 4px 8px 4px;
            background: transparent;
            overflow-x: auto;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
        }

        .thumbnail-grid::-webkit-scrollbar {
            height: 6px;
        }
        .thumbnail-grid::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 99px;
        }

        .thumb-item {
            flex: 0 0 135px;
            height: 90px;
            border-radius: 14px;
            overflow: hidden;
            cursor: pointer;
            border: 2.5px solid transparent;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 0.6;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            background: #fff;
        }

        .thumb-item:hover {
            opacity: 0.95;
            transform: translateY(-3px);
            box-shadow: 0 8px 18px rgba(0,0,0,0.12);
        }

        .thumb-item.active { 
            border-color: #850f0f; 
            opacity: 1;
            transform: translateY(-3px);
            box-shadow: 0 8px 22px rgba(133, 15, 15, 0.22);
        }
        .thumb-item img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease; }
        .thumb-item:hover img { transform: scale(1.08); }

        /* Spec Cards Row */
        .gallery-info-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 16px;
            width: 100%;
        }

        .spec-card {
            background: linear-gradient(135deg, #ffffff 0%, #fffefe 100%);
            padding: 20px 16px;
            border-radius: 18px;
            border: 1px solid rgba(226, 232, 240, 0.8);
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            overflow: hidden;
            min-width: 0;
            box-shadow: 0 8px 20px -6px rgba(0, 0, 0, 0.03), 0 1px 3px rgba(0,0,0,0.01);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .spec-card:hover, .spec-card:active, .spec-card:focus, .spec-card:focus-within {
            transform: translateY(-3px);
            border-color: rgba(133, 15, 15, 0.25);
            box-shadow: 0 12px 30px -8px rgba(133, 15, 15, 0.08), 0 2px 4px rgba(133, 15, 15, 0.02);
            outline: none;
        }

        .spec-card i, .spec-card svg {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, rgba(133, 15, 15, 0.05) 0%, rgba(133, 15, 15, 0.12) 100%) !important;
            border: 1px solid rgba(133, 15, 15, 0.15) !important;
            color: #850f0f !important;
            font-size: 20px !important;
            margin-bottom: 10px;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .spec-card:hover i, .spec-card:hover svg,
        .spec-card:active i, .spec-card:active svg,
        .spec-card:focus i, .spec-card:focus svg,
        .spec-card:focus-within i, .spec-card:focus-within svg {
            background: linear-gradient(135deg, rgba(133, 15, 15, 0.08) 0%, rgba(133, 15, 15, 0.18) 100%) !important;
            border-color: rgba(133, 15, 15, 0.25) !important;
            color: #850f0f !important;
            transform: scale(1.08);
            box-shadow: 0 4px 10px rgba(133, 15, 15, 0.04);
        }

        .spec-card .val {
            display: block;
            font-weight: 800;
            color: #0f172a;
            font-size: 15px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            letter-spacing: -0.2px;
        }

        .spec-card .lbl {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.6px;
            margin-top: 2px;
        }


        /* Main Room Info Header Styles */
        .room-title {
            font-size: 2.5rem; /* 40px */
            font-weight: 900;
            color: #0f172a;
            margin-bottom: 0.75rem;
            letter-spacing: -1.2px;
            line-height: 1.15;
            font-family: 'Outfit', sans-serif;
        }

        .room-meta-row {
            display: flex;
            gap: 12px;
            color: #475569;
            font-size: 0.95rem;
            margin-bottom: 2.5rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .room-meta-item {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 50px;
            font-weight: 700;
            font-size: 13.5px;
            transition: all 0.2s ease;
        }

        .room-meta-item:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }

        .room-meta-item i {
            color: #850f0f;
            font-size: 16px;
        }

        /* Luxury Tab Control */
        .tabs-container {
            border-bottom: 2px solid #e2e8f0;
            margin-bottom: 1.5rem;
            display: flex;
            gap: 32px;
            background: transparent;
            padding: 0;
            border-radius: 0;
        }

        .tab-btn {
            padding: 12px 4px;
            font-weight: 700;
            font-size: 14px;
            color: #64748b;
            border-bottom: 3px solid transparent;
            cursor: pointer;
            transition: all 0.3s ease;
            background: transparent;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            white-space: nowrap;
            position: relative;
            bottom: -2px;
        }

        .tab-btn:hover {
            color: #0f172a;
            background: transparent;
        }

        .tab-btn.active {
            color: #850f0f !important;
            border-bottom: 3px solid #850f0f !important;
            background: transparent !important;
            box-shadow: none !important;
            border-radius: 0;
        }

        .tab-pane { display: none; }
        .tab-pane.active { display: block; animation: fadeIn 0.3s ease; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Section Headings */
        .section-title {
            font-size: 1.15rem; /* 18px */
            font-weight: 800;
            color: #0f172a;
            margin: 2.5rem 0 1.25rem 0;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            border-bottom: 2px solid rgba(133, 15, 15, 0.1);
            padding-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-family: 'Outfit', sans-serif;
        }

        .tab-pane .section-title:first-child {
            margin-top: 0.25rem !important;
        }

        .section-title i {
            color: #850f0f;
            font-size: 18px;
            vertical-align: middle;
        }

        /* Stay Rules Layout */
        .stay-rules-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 2.5rem;
        }

        .stay-rule-card {
            background: #ffffff;
            border: 1px solid #f1f5f9;
            border-radius: 16px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.01);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .stay-rule-card:hover {
            transform: translateY(-2px);
            border-color: rgba(133, 15, 15, 0.15);
            box-shadow: 0 8px 20px rgba(133, 15, 15, 0.04);
        }

        .rule-icon-box {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #10b981;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            flex-shrink: 0;
        }

        .rule-text {
            font-size: 14px;
            font-weight: 600;
            color: #334155;
        }

        .description-text {
            color: var(--text-medium);
            line-height: 1.8;
            font-size: 1rem; /* 16px */
        }

        /* Luxury Testimonial/Review Cards */
        .review-card {
            background: #ffffff;
            border: 1px solid #f1f5f9;
            border-left: 4px solid #850f0f;
            padding: 24px 28px;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.015);
            display: flex;
            gap: 20px;
            align-items: flex-start;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
        }

        .review-card:hover {
            transform: translateY(-2px);
            border-color: rgba(133, 15, 15, 0.15);
            box-shadow: 0 12px 30px -5px rgba(133, 15, 15, 0.05);
        }

        .review-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(133, 15, 15, 0.06) 0%, rgba(133, 15, 15, 0.12) 100%);
            border: 1px solid rgba(133, 15, 15, 0.18);
            color: #850f0f;
            font-weight: 800;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .review-content-wrapper {
            flex-grow: 1;
            min-width: 0;
        }

        .review-header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
            flex-wrap: wrap;
        }

        .review-author-info {
            display: flex;
            flex-direction: column;
        }

        .review-author-name {
            font-size: 15px;
            font-weight: 800;
            color: #0f172a;
        }

        .review-date-badge {
            font-size: 11px;
            color: #64748b;
            font-weight: 600;
            margin-top: 2px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .review-rating-stars {
            color: #fbbf24;
            font-size: 13px;
            display: flex;
            gap: 2px;
        }

        .review-quote-text {
            color: #475569;
            font-style: italic;
            font-size: 14.5px;
            line-height: 1.65;
            margin: 0;
            position: relative;
            padding-left: 6px;
        }

        .review-quote-text::before {
            content: '\201C';
            font-family: Georgia, serif;
            font-size: 32px;
            line-height: 0;
            position: absolute;
            left: -8px;
            top: 12px;
            color: rgba(133, 15, 15, 0.15);
        }

        /* Sidebar Standardized Typography & Spacing */
        .sidebar-card {
            background: #ffffff;
            border-radius: 24px;
            padding: 24px;
            border: 1px solid #eef2f6;
            box-shadow: 0 10px 40px rgba(0,0,0,0.04);
            position: relative;
            height: fit-content;
        }

        .luxury-price-box {
            background: linear-gradient(145deg, #ffffff, #fef2f2);
            border-radius: 20px;
            padding: 26px 20px;
            margin-bottom: 22px;
            border: 1.5px solid rgba(133, 15, 15, 0.15);
            text-align: center;
            box-shadow: 0 8px 25px rgba(133, 15, 15, 0.05);
            position: relative;
            overflow: hidden;
        }

        .luxury-price-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #850f0f, #ef4444);
        }

        .book-now-btn {
            background: linear-gradient(135deg, #a51d24 0%, #700b10 100%) !important;
            box-shadow: 0 8px 25px rgba(133, 15, 15, 0.3) !important;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }

        .book-now-btn:hover {
            transform: translateY(-3px) !important;
            box-shadow: 0 12px 30px rgba(133, 15, 15, 0.45) !important;
            background: linear-gradient(135deg, #b91c1c 0%, #850f0f 100%) !important;
        }

        .sidebar-feature-card:hover {
            transform: translateY(-4px) !important;
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.08), 0 0 15px rgba(133, 15, 15, 0.04) !important;
            border-color: rgba(133, 15, 15, 0.25) !important;
        }

        .highlight-item-card:hover, .amenity-mini-item:hover {
            transform: translateY(-3px) !important;
            box-shadow: 0 10px 25px rgba(0,0,0,0.06) !important;
            border-color: rgba(133, 15, 15, 0.2) !important;
        }

        .explore-services-btn:hover {
            transform: translateY(-3px) !important;
            box-shadow: 0 12px 28px rgba(133, 15, 15, 0.4) !important;
            background: linear-gradient(135deg, #b91c1c 0%, #850f0f 100%) !important;
        }

        .sidebar-section-box {
            background: #fafafa;
            border-radius: 4px;
            padding: 12px 16px;
            margin-bottom: 14px; /* Strictly 14px gap */
            border: 1px solid var(--border-light);
        }

        .sidebar-section-title {
            font-size: 0.95rem; /* 15-16px */
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .sidebar-section-title i { font-size: 1.25rem; }

        .highlights-list { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .highlight-item {
            display: flex;
            gap: 8px;
            font-size: 0.85rem; /* 13-14px */
            color: var(--text-medium);
            line-height: 1.5;
            align-items: flex-start;
        }

        .highlight-item i { color: #16a34a; font-size: 0.9rem; margin-top: 3px; }

        .capacity-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
        .capacity-item { background: white; padding: 10px 5px; border-radius: 12px; border: 1px solid var(--border-light); text-align: center; }
        .capacity-item i { font-size: 1.1rem; color: var(--text-light); margin-bottom: 6px; display: block; }
        .cap-val { display: block; font-weight: 800; color: var(--text-dark); font-size: 1.1rem; }
        .cap-label { font-size: 0.7rem; color: var(--text-light); text-transform: uppercase; font-weight: 700; }

        .amenity-mini-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 12px; }
        .amenity-mini-icon {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px;
            background: white;
            border-radius: 10px;
            border: 1px solid var(--border-light);
        }

        .amenity-mini-icon i {
            font-size: 1.25rem;
            color: var(--primary-color);
            background: #fff8f3;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
        }

        .amenity-mini-label { font-size: 0.8rem; font-weight: 600; color: var(--text-medium); }

        .guide-list li {
            font-size: 0.85rem; /* 13-14px */
            color: var(--text-medium);
            margin-bottom: 8px;
            line-height: 1.6;
            padding-left: 20px;
            position: relative;
        }
        .guide-list li::before { content: "•"; position: absolute; left: 0; color: var(--primary-color); font-weight: bold; }

        .collapsible-content { display: none; padding-top: 10px; border-top: 1px solid var(--border-light); margin-top: 10px; }
        .expanded .collapsible-content { display: block; }

        .sidebar-footer { border-top: 1px solid var(--border-light); padding-top: 16px; margin-top: 16px; }

        .amenity-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 16px;
        }

        .amenity-card {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 18px;
            background: #ffffff;
            border: 1px solid #f1f5f9;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.01);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .amenity-card:hover,
        .amenity-card:active,
        .amenity-card:focus,
        .amenity-card:focus-within {
            transform: translateY(-2px);
            border-color: rgba(133, 15, 15, 0.15);
            box-shadow: 0 8px 20px rgba(133, 15, 15, 0.04);
            outline: none;
        }

        .amenity-card .icon-box {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, rgba(133,15,15,0.04) 0%, rgba(133,15,15,0.1) 100%) !important;
            border: 1px solid rgba(133,15,15,0.12) !important;
            color: #850f0f !important;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .amenity-card:hover .icon-box,
        .amenity-card:active .icon-box,
        .amenity-card:focus .icon-box,
        .amenity-card:focus-within .icon-box {
            background: linear-gradient(135deg, rgba(133,15,15,0.08) 0%, rgba(133,15,15,0.18) 100%) !important;
            border-color: rgba(133,15,15,0.2) !important;
            color: #850f0f !important;
            transform: scale(1.08);
        }

        .amenity-card span {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
        }

        /* Interactive State Styles */
        .action-btn { transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); cursor: pointer; }
        .action-btn:hover { transform: scale(1.1); color: var(--primary-color); }
        .action-btn.liked { color: #ef4444 !important; }
        .action-btn.liked i { font-weight: bold; }

        /* Modal Base Styles */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.85); backdrop-filter: blur(5px);
            display: flex; justify-content: center; align-items: center;
            z-index: 2000; opacity: 0; visibility: hidden; transition: opacity 0.3s ease, visibility 0.3s ease;
        }
        .modal-overlay.active { opacity: 1; visibility: visible; }
        .modal-content {
            background: white; border-radius: 20px; padding: 30px;
            width: 90%; max-width: 400px; position: relative;
            transform: scale(0.98); transition: transform 0.3s ease;
        }
        .modal-overlay.active .modal-content { transform: scale(1); }
        .modal-close {
            position: absolute; top: 20px; right: 20px;
            font-size: 1.25rem; cursor: pointer; color: var(--text-light);
            width: 36px; height: 36px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            background: rgba(0,0,0,0.05); transition: all 0.3s ease;
        }

        /* Share Modal */
        .share-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-top: 20px; }
        .share-option {
            display: flex; flex-direction: column; align-items: center; gap: 8px;
            padding: 15px; border-radius: 12px; background: #f8fafc;
            text-decoration: none; color: var(--text-dark); font-weight: 600; font-size: 0.8rem;
            transition: all 0.2s; border: 1px solid transparent;
        }
        .share-option:hover { background: #fff8f3; border-color: var(--primary-color); color: var(--primary-color); }
        .share-option i { font-size: 1.5rem; }

        /* Lightbox */
        .lightbox-content { 
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: auto;
            height: auto;
            transform: scale(0.98); transition: transform 0.3s ease;
        }
        .modal-overlay.active .lightbox-content { transform: scale(1); }
        .lightbox-img { 
            max-width: 95vw; 
            max-height: 90vh; 
            width: auto; 
            height: auto; 
            border-radius: 8px;
            object-fit: contain; 
            box-shadow: 0 0 50px rgba(0,0,0,0.5);
        }
        .lightbox-nav {
            position: absolute; top: 50%; width: 100%; transform: translateY(-50%);
            display: flex; justify-content: space-between; padding: 0 20px; pointer-events: none;
        }
        .lightbox-btn {
            width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 50%;
            display: flex; align-items: center; justify-content: center; color: white;
            cursor: pointer; pointer-events: auto; transition: background 0.2s;
        }
        .lightbox-btn:hover { background: var(--primary-color); }

        /* Toast */
        .toast {
            position: fixed; bottom: 30px; left: 50%; transform: translateX(-50%) translateY(100px);
            background: var(--text-dark); color: white; padding: 12px 24px;
            border-radius: 50px; font-weight: 600; font-size: 0.9rem;
            z-index: 3000; transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex; align-items: center; gap: 10px;
        }
        .toast.active { transform: translateX(-50%) translateY(0); }

        /* FAQ */
        .faq-item-sidebar { border-bottom: 1px solid var(--border-light); padding: 12px 0; }
        .faq-answer { max-height: 0; overflow: hidden; transition: all 0.3s ease; opacity: 0; }
        .expanded .faq-answer { max-height: 200px; opacity: 1; margin-top: 10px; }

        /* ===== RESPONSIVE: TABLET (≤ 1100px) ===== */
        @media (max-width: 1100px) {
            .details-container {
                grid-template-columns: 1fr !important;
                padding: 0 1.5rem !important;
                gap: 1.5rem !important;
            }
            .sidebar-card { position: static !important; }
        }

        /* ===== RESPONSIVE: MOBILE (≤ 768px) ===== */
        @media (max-width: 768px) {
            .details-container {
                padding: 0 1rem !important;
                margin: 0.5rem auto 2rem !important;
                gap: 0.75rem !important;
                display: flex !important;
                flex-direction: column !important;
                width: 100% !important;
            }
            .sidebar-section {
                margin-top: 0 !important;
            }
            .sidebar-card {
                width: 100% !important;
                margin-top: 0 !important;
            }
            .related-section {
                margin-bottom: 0 !important;
            }

            /* Breadcrumbs responsive */
            .breadcrumb {
                padding: 6px 14px 6px 6px !important;
                border-radius: 24px !important;
                font-size: 0.8rem !important;
                gap: 6px 8px !important;
                max-width: 100% !important;
                margin-bottom: 1.25rem !important;
            }
            .breadcrumb a {
                padding: 4px 10px !important;
                font-size: 0.8rem !important;
                border-radius: 20px !important;
            }

            .main-content {
                width: 100% !important;
            }

            .sidebar-card {
                width: 100% !important;
                padding: 16px !important;
                margin-top: 0 !important;
            }

            /* Gallery */
            .main-img-wrapper { height: 240px !important; }
            .gallery-section { border-radius: 14px !important; width: 100% !important; }
            .thumbnail-grid {
                display: grid !important;
                grid-template-columns: repeat(3, 1fr) !important;
                gap: 8px !important;
                padding: 8px 0 !important;
                width: 100% !important;
            }
            .thumb-item { 
                height: 72px !important; 
                flex: none !important; 
                width: 100% !important; 
            }

            /* Spec cards: stack vertically full width */
            .gallery-info-row {
                display: flex !important;
                flex-direction: column !important;
                gap: 8px !important;
                margin-bottom: 12px !important;
                width: 100% !important;
            }
            .spec-card {
                display: grid !important;
                grid-template-columns: auto 1fr !important;
                grid-template-rows: auto auto !important;
                gap: 2px 12px !important;
                align-items: center !important;
                padding: 12px 14px !important;
                width: 100% !important;
                text-align: left !important;
            }
            .spec-card i {
                grid-row: span 2 !important;
                grid-column: 1 !important;
                margin-bottom: 0 !important;
                flex-shrink: 0 !important;
            }
            .spec-card .val {
                grid-column: 2 !important;
                grid-row: 1 !important;
                font-size: 0.95rem !important;
                white-space: normal !important;
                overflow: visible !important;
                text-overflow: unset !important;
                line-height: 1.25 !important;
                font-weight: 800 !important;
            }
            .spec-card .lbl {
                grid-column: 2 !important;
                grid-row: 2 !important;
                font-size: 0.72rem !important;
                margin-top: 0 !important;
                text-align: left !important;
            }

            /* Room header */
            .room-title { font-size: 1.5rem !important; line-height: 1.2 !important; white-space: normal !important; }
            .room-meta-row {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 8px !important;
                margin-bottom: 1.25rem !important;
                width: 100% !important;
            }
            .room-meta-item {
                font-size: 0.85rem !important;
                padding: 8px 12px !important;
                width: 100% !important;
                justify-content: flex-start !important;
            }

            /* Tabs */
            .tabs-container {
                gap: 16px !important;
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch !important;
                padding-bottom: 4px !important;
                scrollbar-width: none !important;
            }
            .tabs-container::-webkit-scrollbar { display: none !important; }
            .tab-btn { font-size: 0.875rem !important; padding: 10px 2px !important; white-space: nowrap !important; }

            /* Section titles */
            .section-title { font-size: 1.1rem !important; margin: 1.5rem 0 1rem !important; }

            /* Amenity & Highlight grids */
            .amenity-grid { grid-template-columns: 1fr !important; gap: 10px !important; }
            .amenity-card { padding: 12px 16px !important; gap: 12px !important; }
            
            .highlights-list { grid-template-columns: 1fr !important; gap: 8px !important; }
            .capacity-grid { grid-template-columns: 1fr 1fr !important; gap: 8px !important; }

            /* Stay Rules */
            .stay-rules-grid { grid-template-columns: 1fr !important; gap: 10px !important; }
            .stay-rule-card { padding: 12px 16px !important; }

            /* Reviews */
            .review-card { padding: 16px !important; gap: 12px !important; flex-direction: column !important; align-items: flex-start !important; }
            .review-avatar { width: 40px !important; height: 40px !important; font-size: 14px !important; }
            
            /* Sidebar responsiveness fixes */
            .price-val { font-size: 1.75rem !important; }
            .amenity-mini-grid { grid-template-columns: 1fr !important; gap: 8px !important; }
            .book-now-btn { width: 100% !important; }
            
            .sidebar-feature-card {
                width: 100% !important;
                max-width: 100% !important;
                min-width: 0 !important;
                padding: 16px !important;
                box-sizing: border-box !important;
            }
            .highlights-list {
                width: 100% !important;
                min-width: 0 !important;
            }
            .highlight-item-card {
                width: 100% !important;
                max-width: 100% !important;
                min-width: 0 !important;
                box-sizing: border-box !important;
                flex-direction: row !important;
                align-items: center !important;
                justify-content: space-between !important;
                padding: 8px 10px !important;
                gap: 8px !important;
            }
            /* Make text wrap properly inside highlights */
            .highlight-item-card span {
                white-space: normal !important;
                word-break: break-word !important;
            }
            .highlight-item-card > div:first-child {
                width: auto !important;
                display: flex !important;
                align-items: center !important;
                gap: 8px !important;
                min-width: 0 !important;
                flex: 1 1 auto !important;
            }
            .highlight-item-card > div:first-child > div:first-child {
                width: 32px !important;
                height: 32px !important;
                font-size: 16px !important;
                border-radius: 8px !important;
            }
            .highlight-item-card span:first-child {
                font-size: 12.5px !important;
            }
            .highlight-item-card span:last-child {
                font-size: 10.5px !important;
                margin-top: 1px !important;
            }
            .highlight-item-card > div:last-child {
                align-self: center !important;
                margin-top: 0 !important;
                padding: 2px 6px !important;
                font-size: 9.5px !important;
            }

            .capacity-box {
                width: 100% !important;
                max-width: 100% !important;
                min-width: 0 !important;
                padding: 16px 14px !important;
                box-sizing: border-box !important;
            }
            .capacity-box > div:first-child {
                flex-wrap: wrap !important;
                gap: 8px !important;
            }
            .capacity-box span {
                white-space: normal !important;
                word-break: break-word !important;
            }
            .capacity-box .cap-val {
                white-space: normal !important;
            }
            
            /* VIP concierge link wrapping */
            .sidebar-feature-card a {
                flex-wrap: wrap !important;
                gap: 8px !important;
            }
            .sidebar-feature-card a span {
                white-space: normal !important;
            }

            /* Modals */
            .modal-content { width: 95% !important; padding: 20px 16px !important; }
            .share-grid { grid-template-columns: 1fr !important; gap: 10px !important; }

            /* Complete Mobile Responsiveness Overrides */
            .sidebar-feature-card > div:first-child {
                display: flex !important;
                flex-wrap: wrap !important;
                justify-content: space-between !important;
                gap: 8px !important;
                width: 100% !important;
                box-sizing: border-box !important;
            }
            .sidebar-feature-card > div:first-child > div {
                flex-shrink: 1 !important;
                min-width: 0 !important;
                display: flex !important;
                align-items: center !important;
                gap: 6px !important;
            }
            .sidebar-feature-card > div:first-child span {
                white-space: normal !important;
                word-break: break-word !important;
            }
            .sidebar-feature-card div[style*="border: 1.5px solid"] {
                padding: 16px 12px !important;
                border-radius: 16px !important;
                box-sizing: border-box !important;
                width: 100% !important;
            }
            .sidebar-feature-card div[style*="border: 1.5px solid"] > div {
                flex-wrap: wrap !important;
                gap: 8px !important;
                width: 100% !important;
                box-sizing: border-box !important;
            }
            .sidebar-feature-card div[style*="border: 1.5px solid"] span {
                white-space: normal !important;
                word-break: break-word !important;
            }
            .luxury-price-box > div:nth-child(2) {
                display: flex !important;
                flex-wrap: wrap !important;
                justify-content: center !important;
                padding: 6px 10px !important;
                border-radius: 20px !important;
                font-size: 11px !important;
                text-align: center !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
            }
            .amenity-mini-grid {
                grid-template-columns: 1fr 1fr !important;
                gap: 8px !important;
            }
            .amenity-mini-item {
                padding: 12px 8px !important;
                gap: 6px !important;
                border-radius: 12px !important;
                box-sizing: border-box !important;
            }
            .amenity-mini-item span {
                font-size: 12px !important;
                white-space: normal !important;
                word-break: break-word !important;
                text-align: center !important;
            }
            .amenity-mini-item span:last-child {
                font-size: 9px !important;
                padding: 2px 6px !important;
                white-space: normal !important;
                word-break: break-word !important;
            }
            .rooms-grid {
                grid-template-columns: 1fr !important;
                gap: 16px !important;
            }
        }

        /* ===== RESPONSIVE: SMALL PHONE (≤ 480px) ===== */
        @media (max-width: 480px) {
            .details-container { padding: 0 12px !important; }
            .main-img-wrapper { height: 210px !important; }
            .thumb-item { height: 60px !important; }
            .room-title { font-size: 1.35rem !important; }
            .capacity-grid { grid-template-columns: 1fr !important; }
        }
    </style>
    @include('partials.dynamic-styles')
</head>
<body style="background: #fbfbfb;">
    @include('partials.header', ['headerBackBtn' => ['url' => url()->previous(), 'label' => 'Back'], 'showHelpBtn' => true])

    @php
        // Hardcoded room data lookup for the specific demonstration
        $roomsData = [
            'conference-hall' => [
                'name' => 'Conference Hall',
                'price' => '₹2,000',
                'time' => '/ 4 Hours',
                'capacity' => '60 Members',
                'size' => '1200 sq.ft',
                'location' => 'Main Wing, 1st Floor',
                'img' => asset('assets/standard/conference.JPG'),
                'desc' => 'A versatile and professionally equipped venue designed for large-scale gatherings, corporate events, and interactive workshops. Featuring high-definition projection systems, professional-grade acoustics, and premium seating for 60 members, our Conference Hall provides an impactful environment for collaborative success.',
                'amenities' => [
                    ['name' => 'Projector', 'icon' => 'ph-projector-screen'],
                    ['name' => 'PA System', 'icon' => 'ph-speaker-hifi'],
                    ['name' => 'Whiteboard', 'icon' => 'ph-chalkboard'],
                    ['name' => 'High Speed WiFi', 'icon' => 'ph-wifi-high'],
                    ['name' => 'Drinking Water', 'icon' => 'ph-drop'],
                    ['name' => 'AC', 'icon' => 'ph-snowflake'],
                ],
                'highlights' => [
                    ['text' => 'High-definition projection systems', 'icon' => 'ph ph-monitor'],
                    ['text' => 'Professional-grade acoustics', 'icon' => 'ph ph-speaker-hifi'],
                    ['text' => 'Flexible seating arrangements', 'icon' => 'ph ph-users-three'],
                    ['text' => 'Dedicated technical support', 'icon' => 'ph ph-headset'],
                ],
                'capacity_breakdown' => [
                    ['title' => 'Standing', 'value' => '80'],
                    ['title' => 'Theater', 'value' => '60'],
                    ['title' => 'Conference', 'value' => '40'],
                    ['title' => 'Boardroom', 'value' => '30'],
                ],
                'tips' => [
                    'Minimum booking: 4 hours',
                    'Book at least 3 days in advance',
                    'Arrive 15 mins early for technical check',
                    'Setup time included in booking duration',
                ],
                'included' => [
                    'Basic stationery and whiteboards',
                    'Bottled drinking water',
                    'Standard room setup and cleanup',
                    'On-site technical representative',
                ],
                'faqs' => [
                    ['q' => 'Can we bring outside catering?', 'a' => 'Yes, however prior approval and a small maintenance fee apply.'],
                    ['q' => 'Is parking available for guests?', 'a' => 'Dedicated visitor parking is available near the Main Wing.'],
                    ['q' => 'Do you provide hybrid meeting tools?', 'a' => 'Yes, we have high-end cameras and mics for hybrid sessions.'],
                ],
                'category' => 'conference'
            ],
            'conference-room' => [
                'name' => 'Conference Room',
                'price' => '₹2,000',
                'time' => '/ 4 Hours',
                'capacity' => '60 Members',
                'size' => '1200 sq.ft',
                'location' => 'Main Wing, 1st Floor',
                'img' => asset('assets/standard/conference.JPG'),
                'desc' => 'An elegant, state-of-the-art corporate room designed for executive meetings, board discussions, and private presentations. Equipped with high-speed WiFi, modern presentation screen, comfortable executive chairs, and dedicated beverage support, the Conference Room is the perfect setting for high-level decision making.',
                'amenities' => [
                    ['name' => 'Projector', 'icon' => 'ph-projector-screen'],
                    ['name' => 'PA System', 'icon' => 'ph-speaker-hifi'],
                    ['name' => 'Whiteboard', 'icon' => 'ph-chalkboard'],
                    ['name' => 'High Speed WiFi', 'icon' => 'ph-wifi-high'],
                    ['name' => 'Drinking Water', 'icon' => 'ph-drop'],
                    ['name' => 'AC', 'icon' => 'ph-snowflake'],
                ],
                'highlights' => [
                    ['text' => 'High-definition projection systems', 'icon' => 'ph ph-monitor'],
                    ['text' => 'Comfortable ergonomic executive chairs', 'icon' => 'ph ph-chair'],
                    ['text' => 'Flexible meeting configurations', 'icon' => 'ph ph-users-three'],
                    ['text' => 'Dedicated technical support', 'icon' => 'ph ph-headset'],
                ],
                'capacity_breakdown' => [
                    ['title' => 'Theater', 'value' => '60'],
                    ['title' => 'Boardroom', 'value' => '40'],
                ],
                'tips' => [
                    'Minimum booking: 4 hours',
                    'Book at least 2 days in advance',
                    'Technical setup support included',
                ],
                'included' => [
                    'Smart TV/Screen access',
                    'Flipcharts and markers',
                    'Bottled drinking water',
                ],
                'faqs' => [
                    ['q' => 'Is audio-conferencing supported?', 'a' => 'Yes, we provide advanced speakerphones for conference calls.'],
                ],
                'category' => 'conference'
            ],
            'glass-room' => [
                'name' => 'Glass Room',
                'price' => '₹1,500',
                'time' => '/ 4 Hours',
                'capacity' => '15 Members',
                'size' => '450 sq.ft',
                'location' => 'East Wing, Ground Floor',
                'img' => asset('assets/standard/glass.JPG'),
                'desc' => 'Inspire creativity in our modern Glass Room, a unique transparent facility designed for collaborative brainstorming and focused team sessions for up to 15 members. Flooded with natural light and equipped with the latest presentation technology, this space fosters an atmosphere of transparency and professional innovation.',
                'amenities' => [
                    ['name' => 'Modern Furniture', 'icon' => 'ph-armchair'],
                    ['name' => 'High Speed WiFi', 'icon' => 'ph-wifi-high'],
                    ['name' => 'Charging Ports', 'icon' => 'ph-lightning'],
                    ['name' => 'Glass Walls', 'icon' => 'ph-squares-four'],
                    ['name' => 'AC', 'icon' => 'ph-snowflake'],
                    ['name' => 'Presentation Support', 'icon' => 'ph-presentation-chart'],
                ],
                'highlights' => [
                    ['text' => 'Ample natural morning light', 'icon' => 'ph ph-sun'],
                    ['text' => 'Sleek transparent glass walls', 'icon' => 'ph ph-bounding-box'],
                    ['text' => 'Ergonomic designer furniture', 'icon' => 'ph ph-chair'],
                    ['text' => 'Privacy blinds available', 'icon' => 'ph ph-eye-slash'],
                ],
                'capacity_breakdown' => [
                    ['title' => 'Standing', 'value' => '20'],
                    ['title' => 'Boardroom', 'value' => '15'],
                    ['title' => 'Informal', 'value' => '15'],
                ],
                'tips' => [
                    'Ideal for brainstorming sessions',
                    'Best light between 9 AM - 12 PM',
                    'Food not allowed inside the glass area',
                ],
                'included' => [
                    'Smart TV for presentations',
                    'Flip charts and markers',
                    'Central air conditioning',
                    'Charging docks at every seat',
                ],
                'faqs' => [
                    ['q' => 'Are the walls soundproof?', 'a' => 'Yes, we use double-glazed glass for enhanced acoustic privacy.'],
                    ['q' => 'Is it completely transparent?', 'a' => 'Yes, but integrated automated blinds can be used for full privacy.'],
                ],
                'category' => 'conference'
            ],
            'suite-room' => [
                'name' => 'Suite Room',
                'price' => '₹4,500',
                'time' => '/ Day',
                'capacity' => '2 Members',
                'size' => '600 sq.ft',
                'location' => 'Executive Wing, 2nd Floor',
                'img' => asset('assets/suite.JPG'),
                'desc' => 'Our flagship Suite Room offers the pinnacle of luxury and spaciousness, specifically curated for guests seeking a grand experience. Featuring a premium king-size bed, sophisticated furnishings, and exclusive amenities, it provides ultimate relaxation and privacy for a restful stay at MCC IGH.',
                'amenities' => [
                    ['name' => 'King Size Bed', 'icon' => 'ph-bed'],
                    ['name' => 'Smart TV', 'icon' => 'ph-television'],
                    ['name' => 'Mini Fridge', 'icon' => 'ph-snowflake'],
                    ['name' => 'Premium Toiletries', 'icon' => 'ph-spray-bottle'],
                    ['name' => 'Spacious Interior', 'icon' => 'ph-arrows-out'],
                    ['name' => 'AC', 'icon' => 'ph-snowflake'],
                    ['name' => 'Room Service', 'icon' => 'ph-bell-ringing'],
                ],
                'highlights' => [
                    ['text' => 'Premium king-size luxury bed', 'icon' => 'ph ph-bed'],
                    ['text' => 'Dedicated executive lounge access', 'icon' => 'ph ph-crown'],
                    ['text' => 'Panoramic campus views', 'icon' => 'ph ph-mountains'],
                    ['text' => 'Priority check-in service', 'icon' => 'ph ph-clock-user'],
                ],
                'capacity_breakdown' => [
                    ['title' => 'Sleeps', 'value' => '4'],
                    ['title' => 'Lounging', 'value' => '6'],
                ],
                'tips' => [
                    'Perfect for delegates and special guests',
                    'Complimentary breakfast included',
                    'Late checkout available on request',
                ],
                'included' => [
                    'Daily housekeeping service',
                    'Laundry and pressing (optional)',
                    'Private workspace setup',
                    '24/7 concierge support',
                ],
                'faqs' => [
                    ['q' => 'Is breakfast included?', 'a' => 'Yes, a choice of Indian or Continental breakfast is delivered to the room.'],
                    ['q' => 'Can we add an extra bed?', 'a' => 'Yes, one extra rollaway bed can be added for an additional fee.'],
                ],
                'category' => 'conference'
            ]
        ];

        // Generic fallback for numbered rooms (Standard/Advance)
        $room = $roomsData[$roomId] ?? [
            'name' => ucwords(str_replace('-', ' ', $roomId)),
            'price' => str_contains($roomId, 'advance') ? '₹2,500' : '₹1,400',
            'time' => str_contains($roomId, 'advance') ? '/ Day' : '/ 12 Hours',
            'capacity' => str_contains($roomId, 'advance') ? '4 Members' : '2 Members',
            'size' => '250 sq.ft',
            'location' => 'Guest Wing',
            'img' => str_contains($roomId, 'standard') ? asset('assets/standard/standardroom.JPG') : (str_contains($roomId, 'advance') ? asset('assets/room1.JPG') : 'https://images.unsplash.com/photo-1590490360182-c33d57733427?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=90'),
            'desc' => str_contains($roomId, 'advance') 
                ? 'Experience elevated hospitality in our Advance Rooms, specifically curated for guests seeking enhanced privacy and premium comfort during longer stays. Each room boasts sophisticated interiors and upgraded bedding for a superior guest experience.'
                : 'Thoughtfully designed for efficiency and comfort, our Standard Rooms provide a restful haven for short-term visitors. Featuring essential modern amenities including a dedicated workspace and high-speed WiFi, these rooms ensure a productive stay at an unmatched value.',
            'amenities' => [
                ['name' => 'AC', 'icon' => 'ph-snowflake'],
                ['name' => 'WiFi', 'icon' => 'ph-wifi-high'],
                ['name' => 'Work Desk', 'icon' => 'ph-desktop'],
                ['name' => 'Clean Bedding', 'icon' => 'ph-bed'],
                ['name' => 'Basic Toiletries', 'icon' => 'ph-spray-bottle'],
            ],
            'category' => str_contains($roomId, 'advance') ? 'Advance' : (str_contains($roomId, 'conference') ? 'Conference' : (str_contains($roomId, 'suite') ? 'Luxury' : 'Standard'))
        ];
    @endphp

    <main>
        <div class="details-container">
            <!-- Left Column -->
            <div class="main-content">

                <!-- Breadcrumbs -->
                <nav class="breadcrumb">
                    <a href="{{ route('home') }}">
                        <i class="ph-fill ph-house"></i> Home
                    </a>
                    <span class="breadcrumb-separator">›</span>
                    @php
                        $cat = strtolower($room['category'] ?? 'standard');
                        if (!in_array($cat, ['standard', 'advance', 'conference'])) {
                            $cat = 'standard';
                        }
                    @endphp
                    <a href="{{ route($cat . '.rooms') }}" style="text-transform: capitalize;">
                        {{ $cat }} Rooms
                    </a>
                    <span class="breadcrumb-separator">›</span>
                    <span class="breadcrumb-current">{{ ucwords($room['name']) }}</span>
                </nav>

                <div class="gallery-section">
                    <div class="main-img-wrapper" onclick="openLightbox()">
                        <img id="mainImage" src="{{ $room['img'] }}" alt="{{ $room['name'] }}" onerror="this.src='https://images.unsplash.com/photo-1590490360182-c33d57733427?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=90'">
                        <div class="img-zoom-hint" style="position: absolute; bottom: 20px; right: 20px; background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(12px); color: #fff; padding: 10px 18px; border-radius: 50px; font-size: 0.85rem; font-weight: 600; display: flex; align-items: center; gap: 8px; pointer-events: none; border: 1px solid rgba(255,255,255,0.2); box-shadow: 0 8px 25px rgba(0,0,0,0.3); transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);">
                            <i class="ph-bold ph-arrows-out-simple" style="color: #fca5a5; font-size: 1rem;"></i> Click to Fullscreen
                        </div>
                    </div>
                    <div class="thumbnail-grid">
                        @if(str_contains($roomId, 'standard'))
                            @for($j=1; $j<=3; $j++)
                                @php
                                    $standardImg = asset('assets/standard/standard'.$j.'.JPG');
                                @endphp
                                <div class="thumb-item {{ $j==1 ? 'active' : '' }}" onclick="changeImage('{{ $standardImg }}', this)">
                                    <img src="{{ $standardImg }}" alt="View {{ $j }}" onerror="this.src='https://images.unsplash.com/photo-1590490360182-c33d57733427?ixlib=rb-1.2.1&auto=format&fit=crop&w=300&q=60'">
                                </div>
                            @endfor
                        @elseif(str_contains($roomId, 'advance'))
                            @for($j=1; $j<=6; $j++)
                                @php
                                    $advanceImg = asset('assets/standard/std'.$j.'.JPG');
                                @endphp
                                <div class="thumb-item {{ $j==1 ? 'active' : '' }}" onclick="changeImage('{{ $advanceImg }}', this)">
                                    <img src="{{ $advanceImg }}" alt="View {{ $j }}" onerror="this.src='https://images.unsplash.com/photo-1590490360182-c33d57733427?ixlib=rb-1.2.1&auto=format&fit=crop&w=300&q=60'">
                                </div>
                            @endfor
                        @elseif($roomId === 'suite-room')
                            @for($j=1; $j<=7; $j++)
                                @php
                                    $suiteImg = asset('assets/standard/suite'.$j.'.JPG');
                                @endphp
                                <div class="thumb-item {{ $j==1 ? 'active' : '' }}" onclick="changeImage('{{ $suiteImg }}', this)">
                                    <img src="{{ $suiteImg }}" alt="Suite View {{ $j }}" onerror="this.src='https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-1.2.1&auto=format&fit=crop&w=300&q=60'">
                                </div>
                            @endfor
                        @elseif($roomId === 'conference-hall' || $roomId === 'conference-room')
                            @for($j=1; $j<=5; $j++)
                                @php
                                    $conImg = asset('assets/standard/con'.$j.'.JPG');
                                @endphp
                                <div class="thumb-item {{ $j==1 ? 'active' : '' }}" onclick="changeImage('{{ $conImg }}', this)">
                                    <img src="{{ $conImg }}" alt="Conference View {{ $j }}" onerror="this.src='https://images.unsplash.com/photo-1517502884422-41eaead166d4?ixlib=rb-1.2.1&auto=format&fit=crop&w=300&q=60'">
                                </div>
                            @endfor
                        @elseif($roomId === 'glass-room')
                            @for($j=1; $j<=5; $j++)
                                @php
                                    $glassImg = asset('assets/standard/glass'.$j.'.JPG');
                                @endphp
                                <div class="thumb-item {{ $j==1 ? 'active' : '' }}" onclick="changeImage('{{ $glassImg }}', this)">
                                    <img src="{{ $glassImg }}" alt="Glass Room View {{ $j }}" onerror="this.src='https://images.unsplash.com/photo-1497366216548-37526070297c?ixlib=rb-1.2.1&auto=format&fit=crop&w=300&q=60'">
                                </div>
                            @endfor
                        @else
                            <div class="thumb-item active" onclick="changeImage('{{ $room['img'] }}', this)">
                                <img src="{{ $room['img'] }}" alt="View 1" onerror="this.src='https://images.unsplash.com/photo-1590490360182-c33d57733427?ixlib=rb-1.2.1&auto=format&fit=crop&w=300&q=60'">
                            </div>
                            <div class="thumb-item" onclick="changeImage('https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-1.2.1&auto=format&fit=crop&w=300&q=60', this)">
                                <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-1.2.1&auto=format&fit=crop&w=300&q=60" alt="View 2">
                            </div>
                            <div class="thumb-item" onclick="changeImage('https://images.unsplash.com/photo-1584132967334-10e028bd69f7?ixlib=rb-1.2.1&auto=format&fit=crop&w=300&q=60', this)">
                                <img src="https://images.unsplash.com/photo-1584132967334-10e028bd69f7?ixlib=rb-1.2.1&auto=format&fit=crop&w=300&q=60" alt="View 3">
                            </div>
                            <div class="thumb-item" onclick="changeImage('https://images.unsplash.com/photo-1590490360182-c33d57733427?ixlib=rb-1.2.1&auto=format&fit=crop&w=300&q=60', this)">
                                <img src="https://images.unsplash.com/photo-1590490360182-c33d57733427?ixlib=rb-1.2.1&auto=format&fit=crop&w=300&q=60" alt="View 4">
                            </div>
                        @endif
                    </div>
                </div>

                <!-- NEW: Information Row & Actions to fill the space -->
                <div class="gallery-info-row">
                    <div class="spec-card">
                        <i class="ph ph-ruler"></i>
                        <span class="val">{{ $room['size'] }}</span>
                        <span class="lbl">Area Size</span>
                    </div>
                    <div class="spec-card">
                        <i class="ph ph-users"></i>
                        <span class="val">{{ $room['capacity'] }}</span>
                        <span class="lbl">Max Capacity</span>
                    </div>
                    <div class="spec-card">
                        <i class="ph ph-map-pin"></i>
                        <span class="val">{{ $room['location'] }}</span>
                        <span class="lbl">Location</span>
                    </div>
                </div>



                <div class="room-info-header">
                    <h1 class="room-title">{{ $room['name'] }}</h1>
                    <div class="room-meta-row">
                        <div class="room-meta-item"><i class="ph-fill ph-map-pin"></i> {{ $room['location'] }}</div>
                        <div class="room-meta-item"><i class="ph-fill ph-calendar"></i> Verified Location</div>
                    </div>
                </div>

                <nav class="tabs-container">
                    <div class="tab-btn active" onclick="switchTab('overview', this)">Overview</div>
                    <div class="tab-btn" onclick="switchTab('amenities', this)">Amenities</div>
                    <div class="tab-btn" onclick="switchTab('location', this)">Location</div>
                    <div class="tab-btn" onclick="switchTab('reviews', this)">Reviews</div>
                    <div class="tab-btn" onclick="switchTab('faq', this)">FAQ</div>
                </nav>

                <div id="overview" class="tab-pane active">
                    <h2 class="section-title"><i class="ph-fill ph-article"></i> About this Space</h2>
                    <p class="description-text" style="font-size: 15.5px; color: #334155; line-height: 1.8; margin-bottom: 25px;">{{ $room['desc'] }}</p>
                    
                    <h2 class="section-title"><i class="ph-fill ph-shield-check"></i> Stay Rules</h2>
                    <div class="stay-rules-grid">
                        <div class="stay-rule-card">
                            <div class="rule-icon-box"><i class="ph ph-check"></i></div>
                            <span class="rule-text">Check-in: Flexible timings subject to availability.</span>
                        </div>
                        <div class="stay-rule-card">
                            <div class="rule-icon-box"><i class="ph ph-check"></i></div>
                            <span class="rule-text">Alcohol is strictly prohibited in the campus.</span>
                        </div>
                        <div class="stay-rule-card">
                            <div class="rule-icon-box"><i class="ph ph-check"></i></div>
                            <span class="rule-text">Valid ID proof is mandatory during check-in.</span>
                        </div>
                        <div class="stay-rule-card">
                            <div class="rule-icon-box"><i class="ph ph-check"></i></div>
                            <span class="rule-text">Guests are responsible for room belongings.</span>
                        </div>
                    </div>

                    <h2 class="section-title"><i class="ph-fill ph-map-pin"></i> Getting There</h2>
                    <p class="description-text" style="margin-bottom: 20px; font-size: 15px; color: #475569;"><i class="ph ph-map-pin" style="color: #850f0f;"></i> Madras Christian College, East Tambaram, Chennai, Tamil Nadu, India</p>
                    <div style="height: 300px; border-radius: 24px; overflow: hidden; border: 1.5px solid rgba(133, 15, 15, 0.15); margin-bottom: 30px; box-shadow: 0 12px 35px -10px rgba(0, 0, 0, 0.06);">
                        <iframe 
                            width="100%" height="100%" style="border:0" loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade"
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3888.37526978187!2d80.1189493758832!3d12.92215268738865!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a525f16422d86e5%3A0xc3b54b6d4793f0b0!2sMadras%20Christian%20College!5e0!3m2!1sen!2sin!4v1711620000000!5m2!1sen!2sin">
                        </iframe>
                    </div>

                    <h2 class="section-title"><i class="ph-fill ph-star"></i> Guest Experiences</h2>
                    <div class="reviews-list" style="display: flex; flex-direction: column; gap: 16px;">
                        @foreach([['name' => 'Rahul S.', 'rating' => 5, 'text' => "Excellent facility and remarkably clean environment."]] as $review)
                        <div class="review-item" style="background: #ffffff; border: 1px solid #f1f5f9; border-left: 4px solid #850f0f; padding: 18px; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.01);">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                <div style="font-weight: 800; color: #0f172a; font-size: 0.95rem;">{{ $review['name'] }}</div>
                                <div class="rating-stars" style="color: #fbbf24; font-size: 13px; display: flex; gap: 2px;">
                                    @for($i=0; $i<$review['rating']; $i++) <i class="ph-fill ph-star"></i> @endfor
                                </div>
                            </div>
                            <p style="color: #475569; font-style: italic; font-size: 14px; line-height: 1.6; margin: 0;">"{{ $review['text'] }}"</p>
                        </div>
                        @endforeach
                        <a href="javascript:void(0)" onclick="switchTab('reviews', document.querySelectorAll('.tab-btn')[3])" style="color: #850f0f; font-weight: 800; text-decoration: none; font-size: 14px; display: inline-flex; align-items: center; gap: 5px; transition: all 0.2s;">
                            <span>View all reviews</span> <i class="ph-bold ph-arrow-right" style="font-size: 14px;"></i>
                        </a>
                    </div>
                </div>                <div id="amenities" class="tab-pane">
                    <h2 class="section-title"><i class="ph-fill ph-grid-four"></i> Available Amenities</h2>
                    
                    <div class="amenity-grid">
                        <!-- Modern Furniture -->
                        <div class="amenity-card">
                            <div class="icon-box">
                                <i class="ph ph-armchair"></i>
                            </div>
                            <div style="display: flex; flex-direction: column; min-width: 0;">
                                <span style="font-size: 11px; font-weight: 800; color: #850f0f; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 2px;">Furniture</span>
                                <span style="font-size: 15px; font-weight: 700; color: #0f172a;">Modern Furniture</span>
                            </div>
                        </div>

                        <!-- High Speed WiFi -->
                        <div class="amenity-card">
                            <div class="icon-box">
                                <i class="ph-bold ph-wifi-high"></i>
                            </div>
                            <div style="display: flex; flex-direction: column; min-width: 0;">
                                <span style="font-size: 11px; font-weight: 800; color: #850f0f; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 2px;">Technology</span>
                                <span style="font-size: 15px; font-weight: 700; color: #0f172a;">High Speed WiFi</span>
                            </div>
                        </div>

                        <!-- Charging Ports -->
                        <div class="amenity-card">
                            <div class="icon-box">
                                <i class="ph-bold ph-lightning"></i>
                            </div>
                            <div style="display: flex; flex-direction: column; min-width: 0;">
                                <span style="font-size: 11px; font-weight: 800; color: #850f0f; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 2px;">Technology</span>
                                <span style="font-size: 15px; font-weight: 700; color: #0f172a;">Charging Ports</span>
                            </div>
                        </div>

                        <!-- Air Conditioning -->
                        <div class="amenity-card">
                            <div class="icon-box">
                                <i class="ph ph-snowflake"></i>
                            </div>
                            <div style="display: flex; flex-direction: column; min-width: 0;">
                                <span style="font-size: 11px; font-weight: 800; color: #850f0f; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 2px;">Comfort</span>
                                <span style="font-size: 15px; font-weight: 700; color: #0f172a;">Air Conditioning</span>
                            </div>
                        </div>

                        <!-- Glass Walls if glass-room -->
                        @if($roomId === 'glass-room')
                        <div class="amenity-card">
                            <div class="icon-box">
                                <i class="ph ph-bounding-box"></i>
                            </div>
                            <div style="display: flex; flex-direction: column; min-width: 0;">
                                <span style="font-size: 11px; font-weight: 800; color: #850f0f; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 2px;">Furniture</span>
                                <span style="font-size: 15px; font-weight: 700; color: #0f172a;">Glass Walls</span>
                            </div>
                        </div>
                        @endif

                        <!-- Presentation Support if conference-hall -->
                        @if($roomId === 'conference-hall')
                        <div class="amenity-card">
                            <div class="icon-box">
                                <i class="ph-bold ph-presentation-chart"></i>
                            </div>
                            <div style="display: flex; flex-direction: column; min-width: 0;">
                                <span style="font-size: 11px; font-weight: 800; color: #850f0f; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 2px;">Technology</span>
                                <span style="font-size: 15px; font-weight: 700; color: #0f172a;">Presentation Support</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>


                <div id="location" class="tab-pane">
                    <h2 class="section-title"><i class="ph-fill ph-map-pin"></i> Getting There</h2>
                    <p class="description-text" style="margin-bottom: 20px;">Madras Christian College, East Tambaram, Chennai, Tamil Nadu, India</p>
                    <div style="height: 450px; border-radius: 20px; overflow: hidden; border: 1px solid var(--border-light); box-shadow: 0 4px 20px rgba(0,0,0,0.05);">
                        <iframe 
                            width="100%" height="100%" style="border:0" loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade"
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3888.37526978187!2d80.1189493758832!3d12.92215268738865!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a525f16422d86e5%3A0xc3b54b6d4793f0b0!2sMadras%20Christian%20College!5e0!3m2!1sen!2sin!4v1711620000000!5m2!1sen!2sin">
                        </iframe>
                    </div>
                </div>

                <div id="reviews" class="tab-pane">
                    <h2 class="section-title"><i class="ph-fill ph-star"></i> Guest Experiences</h2>
                    <div class="reviews-list" style="display: flex; flex-direction: column; gap: 20px; max-width: 800px;">
                        @foreach([
                            ['name' => 'Rahul S.', 'rating' => 5, 'text' => "Excellent facility and remarkably clean environment. The staff was very professional.", 'date' => 'Verified Guest • June 2026'],
                            ['name' => 'Priya K.', 'rating' => 4, 'text' => "Wonderful space for workshops. High speed internet was very helpful.", 'date' => 'Verified Guest • May 2026'],
                            ['name' => 'Arjun M.', 'rating' => 5, 'text' => "Best guest house in the campus. Easy booking process and very convenient location.", 'date' => 'Verified Guest • April 2026']
                        ] as $review)
                        @php
                            $parts = explode(' ', $review['name']);
                            $initials = (count($parts) > 1) ? ($parts[0][0] . $parts[1][0]) : $parts[0][0];
                        @endphp
                        <div class="review-card">
                            <div class="review-avatar">
                                {{ $initials }}
                            </div>
                            <div class="review-content-wrapper">
                                <div class="review-header-row">
                                    <div class="review-author-info">
                                        <span class="review-author-name">{{ $review['name'] }}</span>
                                        <span class="review-date-badge">
                                            <i class="ph-fill ph-shield-check" style="color: #059669; font-size: 13px;"></i>
                                            {{ $review['date'] }}
                                        </span>
                                    </div>
                                    <div class="review-rating-stars">
                                        @for($i=0; $i<$review['rating']; $i++) <i class="ph-fill ph-star"></i> @endfor
                                        @for($i=$review['rating']; $i<5; $i++) <i class="ph ph-star" style="color: #e2e8f0;"></i> @endfor
                                    </div>
                                </div>
                                <p class="review-quote-text">"{{ $review['text'] }}"</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div id="faq" class="tab-pane">
                    <h2 class="section-title"><i class="ph-fill ph-chats-circle"></i> Frequently Asked Questions</h2>
                    <div class="faq-accordion" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 20px;">
                        @php
                            $faqs = [
                                ['q' => 'What is the maximum capacity?', 'a' => 'Up to 20 people standing, 12 in boardroom style, and 15 in informal seating.'],
                                ['q' => 'What amenities are included?', 'a' => 'Modern furniture, high-speed WiFi, charging ports, glass walls, AC, and presentation support.'],
                                ['q' => 'What is the cancellation policy?', 'a' => 'Free cancellation up to 24 hours before booking.'],
                                ['q' => 'Are beverages provided?', 'a' => 'Complimentary tea, coffee, and water are included.'],
                                ['q' => 'Minimum booking duration?', 'a' => 'The minimum duration is 4 hours, with half-day packages available.'],
                                ['q' => 'Is WiFi/Tech support provided?', 'a' => 'Yes, 100 Mbps WiFi and dedicated technical staff are available.']
                            ];
                        @endphp
                        @foreach($faqs as $faq)
                        <div class="faq-item-compact" style="background: white; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; transition: all 0.3s ease;">
                            <h4 style="font-size: 15px; font-weight: 700; color: var(--text-dark); margin-bottom: 10px; display: flex; align-items: start; gap: 10px;">
                                <i class="ph-fill ph-chats-teardrop" style="color: var(--primary-color); margin-top: 2px;"></i>
                                {{ $faq['q'] }}
                            </h4>
                            <p style="font-size: 14px; color: var(--text-medium); line-height: 1.6; margin: 0; padding-left: 28px;">{{ $faq['a'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Related Rooms -->
                <section class="related-section" style="border-top: 1px solid var(--border-light); margin-top: 40px !important;">
                    <h2 style="font-size: 1.8rem; font-weight: 800; margin-bottom: 2rem; color: var(--text-dark);">Related Rooms</h2>
                    
                    @php 
                        $gstRate = \App\Models\Setting::where('key', 'gst_rate')->value('value') ?? 5;
                    @endphp
                    
                    <div class="rooms-grid dashboard-rooms-grid">
                        <!-- Related Standard Room -->
                        @if(!str_contains($roomId, 'standard'))
                        <div class="card premium-card">
                            <div class="card-image-wrapper">
                                <span class="badge standard-badge" style="position: absolute; top: 1rem; left: 1rem; z-index: 5;">Standard</span>
                                <img src="{{ asset('assets/standard/standardroom.JPG') }}" alt="Standard Room">
                            </div>
                            <div class="card-content">
                                <h2>Standard Guest Room</h2>
                                <p class="description">Thoughtfully designed for efficiency and comfort, providing a restful haven for short-term visitors with essential modern amenities.</p>
                                <p class="gst-text" style="margin-top: 0.25rem; margin-bottom: 1rem !important;"><i class="ph-bold ph-info" style="font-size: 0.85rem; margin-right: 4px; opacity: 0.85;"></i> + {{ $gstRate }}% GST applicable</p>
                                <div class="card-btn-wrapper" style="margin-top: auto !important;">
                                    <a href="{{ route('standard.rooms') }}" class="btn btn-outline view-details-btn" style="width: 100%; text-align: center; justify-content: center;">View Details</a>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Related Advance Room -->
                        @if(!str_contains($roomId, 'advance'))
                        <div class="card premium-card">
                            <div class="card-image-wrapper">
                                <span class="badge premium-badge" style="position: absolute; top: 1rem; left: 1rem; z-index: 5;">Premium</span>
                                <img src="{{ asset('assets/room1.JPG') }}" alt="Advance Room">
                            </div>
                            <div class="card-content">
                                <h2>Advance Executive Room</h2>
                                <p class="description">Experience elevated hospitality curated for guests seeking enhanced privacy and premium comfort during longer stays.</p>
                                <p class="gst-text" style="margin-top: 0.25rem; margin-bottom: 1rem !important;"><i class="ph-bold ph-info" style="font-size: 0.85rem; margin-right: 4px; opacity: 0.85;"></i> + {{ $gstRate }}% GST applicable</p>
                                <div class="card-btn-wrapper" style="margin-top: auto !important;">
                                    <a href="{{ route('advance.rooms') }}" class="btn btn-outline view-details-btn" style="width: 100%; text-align: center; justify-content: center;">View Details</a>
                                </div>
                            </div>
                        </div>
                        @endif
        
                        <!-- Related Suite Room -->
                        @if($roomId !== 'suite-room')
                        <div class="card premium-card">
                            <div class="card-image-wrapper">
                                <span class="badge suite-badge" style="position: absolute; top: 1rem; left: 1rem; z-index: 5;">Luxury</span>
                                <img src="{{ asset('assets/suite.JPG') }}" alt="Suite Room">
                            </div>
                            <div class="card-content">
                                <h2>Luxury Suite Room</h2>
                                <p class="description">Our flagship Suite Room offers the pinnacle of luxury, featuring a grand king-size bed and premium toiletries for ultimate relaxation.</p>
                                <p class="gst-text" style="margin-top: 0.25rem; margin-bottom: 1rem !important;"><i class="ph-bold ph-info" style="font-size: 0.85rem; margin-right: 4px; opacity: 0.85;"></i> + {{ $gstRate }}% GST applicable</p>
                                <div class="card-btn-wrapper" style="margin-top: auto !important;">
                                    <a href="{{ route('room.details', ['id' => 'suite-room']) }}" class="btn btn-outline view-details-btn" style="width: 100%; text-align: center; justify-content: center;">View Details</a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </section>
                </div>

            <!-- Right Column (Sidebar) -->
            <aside class="sidebar-section">
                <div class="sidebar-card">
                    <div class="luxury-price-box">
                        <div style="display: flex; align-items: baseline; justify-content: center; gap: 4px;">
                            <span class="price-val" style="font-size: 38px; font-weight: 900; color: #850f0f; letter-spacing: -1px; line-height: 1;">{!! str_replace('₹', '<span class="rupee-symbol" style="font-size: 32px; font-weight: 800;">₹</span>', $room['price']) !!}</span>
                            <span class="price-unit" style="font-size: 15px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">{{ $room['time'] }}</span>
                        </div>
                        <div style="display: inline-flex; align-items: center; justify-content: center; gap: 6px; margin-top: 14px; padding: 6px 14px; background: rgba(133,15,15,0.08); border: 1px solid rgba(133,15,15,0.2); border-radius: 50px; color: #850f0f; font-size: 12px; font-weight: 700;">
                            <i class="ph-fill ph-shield-check" style="color: #850f0f; font-size: 15px;"></i> + {{ $gstRate }}% GST Applicable on Booking
                        </div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 26px;">
                        <button type="button" class="btn btn-outline" data-cart-room="{{ $room['name'] }}"
                            onclick="window.IGHCart.toggleRoom({ id: '{{ $roomId }}', name: '{{ $room['name'] }}', category: '{{ $room['category'] }}', price: '{{ str_replace(['₹', ','], '', $room['price']) }}', priceText: '{{ $room['price'] }}', rateType: '{{ $room['time'] }}', capacity: {{ (int) filter_var($room['capacity'], FILTER_SANITIZE_NUMBER_INT) ?: 2 }} })"
                            style="width: 100%; padding: 14px !important; border-radius: 14px !important; font-size: 15px !important; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 8px;">
                            <i class="ph-bold ph-shopping-cart-simple"></i> Add to Cart
                        </button>
                        <a href="javascript:void(0)" onclick="window.IGHCart.bookNowDirect({ id: '{{ $roomId }}', name: '{{ $room['name'] }}', category: '{{ $room['category'] }}', price: '{{ str_replace(['₹', ','], '', $room['price']) }}', priceText: '{{ $room['price'] }}', rateType: '{{ $room['time'] }}', capacity: {{ (int) filter_var($room['capacity'], FILTER_SANITIZE_NUMBER_INT) ?: 2 }} })" class="btn book-now-btn" style="width: 100%; padding: 16px !important; border-radius: 14px !important; font-size: 15px !important; font-weight: 800; color: #fff; text-transform: uppercase; letter-spacing: 1.5px; display: flex; align-items: center; justify-content: center; gap: 10px; text-decoration: none;">
                            Book Now <i class="ph-bold ph-arrow-right" style="font-size: 18px;"></i>
                        </a>
                    </div>

                    <!-- Sidebar Content with Luxury Styling -->
                    <div class="sidebar-feature-card" style="background: linear-gradient(180deg, #ffffff 0%, #fefcfc 100%); border: 1px solid rgba(226, 232, 240, 0.9); border-radius: 24px; padding: 22px; margin-bottom: 20px; box-shadow: 0 12px 35px -10px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0,0,0,0.02); transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); box-sizing: border-box; width: 100%;">
                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 18px; flex-wrap: wrap; width: 100%;">
                            <div style="display: flex; align-items: center; gap: 10px; min-width: 0; flex: 1 1 180px;">
                                <div style="width: 38px; height: 38px; border-radius: 12px; background: linear-gradient(135deg, rgba(133,15,15,0.08) 0%, rgba(133,15,15,0.15) 100%); border: 1px solid rgba(133,15,15,0.18); display: flex; align-items: center; justify-content: center; color: #850f0f; box-shadow: 0 4px 10px rgba(133,15,15,0.06); flex-shrink: 0;">
                                    <i class="ph-fill ph-sketch-logo" style="font-size: 18px;"></i>
                                </div>
                                <span style="font-size: 13.5px; font-weight: 800; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px; white-space: normal; word-break: break-word;">Key Highlights</span>
                            </div>
                            <span style="font-size: 10.5px; font-weight: 800; color: #850f0f; background: rgba(133,15,15,0.06); border: 1px solid rgba(133,15,15,0.12); padding: 4px 10px; border-radius: 50px; letter-spacing: 0.5px; text-transform: uppercase; white-space: normal; word-break: break-word; text-align: center; margin-left: auto;">Premium</span>
                        </div>
                        <div class="highlights-list" style="display: flex; flex-direction: column; gap: 12px; width: 100%;">
                            @foreach($room['highlights'] ?? [['text' => 'Clean & Modern', 'icon' => 'ph ph-sparkle'], ['text' => 'Prime Campus Spot', 'icon' => 'ph ph-map-pin']] as $highlight)
                                @php
                                    $subtext = 'Guaranteed premium standard';
                                    if(stripos($highlight['text'], 'Clean') !== false) $subtext = 'Sanitized & spotless ambiance';
                                    elseif(stripos($highlight['text'], 'Campus') !== false || stripos($highlight['text'], 'Spot') !== false) $subtext = 'Central walking distance to venues';
                                @endphp
                                <div class="highlight-item-card" style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 10px; padding: 14px 16px; background: #ffffff; border: 1px solid #f1f5f9; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); box-sizing: border-box; width: 100%;">
                                    <div style="display: flex; align-items: center; gap: 12px; min-width: 0; flex: 1 1 150px;">
                                        <div style="width: 40px; height: 40px; border-radius: 12px; background: linear-gradient(135deg, #fef2f2 0%, #fff 100%); border: 1px solid #fee2e2; color: #850f0f; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; box-shadow: 0 4px 10px rgba(133,15,15,0.06);">
                                            <i class="{{ $highlight['icon'] ?? 'ph ph-star' }}"></i>
                                        </div>
                                        <div style="display: flex; flex-direction: column; min-width: 0; flex: 1 1 auto;">
                                            <span style="font-size: 14px; font-weight: 800; color: #0f172a; line-height: 1.2;">{{ $highlight['text'] }}</span>
                                            <span style="font-size: 11.5px; font-weight: 500; color: #64748b; margin-top: 2px;">{{ $subtext }}</span>
                                        </div>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 4px; background: rgba(16, 185, 129, 0.08); border: 1px solid rgba(16, 185, 129, 0.22); color: #059669; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 50px; flex-shrink: 0; white-space: normal; word-break: break-word; text-align: center; margin-left: auto;">
                                        <i class="ph-fill ph-check-circle" style="font-size: 14px;"></i>
                                        <span>Verified</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>                    <div class="sidebar-feature-card" style="background: linear-gradient(180deg, #ffffff 0%, #fefcfc 100%); border: 1px solid rgba(226, 232, 240, 0.9); border-radius: 24px; padding: 22px; margin-bottom: 20px; box-shadow: 0 12px 35px -10px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0,0,0,0.02); transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); box-sizing: border-box; width: 100%;">
                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 18px; flex-wrap: wrap; width: 100%;">
                            <div style="display: flex; align-items: center; gap: 10px; min-width: 0; flex: 1 1 180px;">
                                <div style="width: 38px; height: 38px; border-radius: 12px; background: linear-gradient(135deg, rgba(133,15,15,0.08) 0%, rgba(133,15,15,0.15) 100%); border: 1px solid rgba(133,15,15,0.18); display: flex; align-items: center; justify-content: center; color: #850f0f; box-shadow: 0 4px 10px rgba(133,15,15,0.06); flex-shrink: 0;">
                                    <i class="ph-fill ph-users" style="font-size: 18px;"></i>
                                </div>
                                <span style="font-size: 13.5px; font-weight: 800; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px; white-space: normal; word-break: break-word;">Room Capacity</span>
                            </div>
                            <span style="font-size: 11px; font-weight: 700; color: #059669; background: #ecfdf5; border: 1px solid #a7f3d0; padding: 4px 10px; border-radius: 50px; display: inline-flex; align-items: center; gap: 5px; white-space: normal; word-break: break-word; text-align: center; margin-left: auto;"><span style="width: 6px; height: 6px; border-radius: 50%; background: #10b981; flex-shrink: 0;"></span> Instant Access</span>
                        </div>
                        <div style="background: linear-gradient(135deg, #fffafa 0%, #ffffff 100%); border: 1.5px solid rgba(133,15,15,0.15); border-radius: 20px; padding: 20px; position: relative; overflow: hidden; box-shadow: 0 8px 25px rgba(133,15,15,0.04); box-sizing: border-box; width: 100%;">
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 14px; flex-wrap: wrap; width: 100%;">
                                <span style="background: linear-gradient(135deg, #850f0f, #b91c1c); color: #ffffff; font-size: 10px; font-weight: 800; padding: 5px 12px; border-radius: 50px; text-transform: uppercase; letter-spacing: 0.8px; white-space: normal; word-break: break-word; text-align: center; box-shadow: 0 4px 10px rgba(133,15,15,0.2);">{{ $room['category'] }} SUITE</span>
                                <span style="font-size: 11px; font-weight: 700; color: #475569; display: inline-flex; align-items: center; gap: 4px; white-space: normal; word-break: break-word; margin-left: auto;"><i class="ph-fill ph-shield-check" style="color: #850f0f; font-size: 15px; flex-shrink: 0;"></i> Guaranteed Access</span>
                            </div>
                            <div style="height: 1px; background: linear-gradient(90deg, rgba(133,15,15,0.15) 0%, rgba(133,15,15,0.02) 100%); margin-bottom: 16px; width: 100%;"></div>
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; width: 100%;">
                                <div style="display: flex; align-items: center; gap: 14px; min-width: 0; flex: 1 1 180px;">
                                    <div style="width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg, #850f0f, #b91c1c); color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 900; box-shadow: 0 6px 16px rgba(133,15,15,0.25); flex-shrink: 0;">{{ explode(' ', $room['capacity'])[0] }}</div>
                                    <div style="display: flex; flex-direction: column; min-width: 0; flex: 1 1 auto;">
                                        <span style="font-size: 16px; font-weight: 900; color: #0f172a; line-height: 1.2;">Guests Maximum</span>
                                        <span style="font-size: 12px; font-weight: 500; color: #64748b; margin-top: 2px;">Spacious accommodation suite</span>
                                    </div>
                                </div>
                                <div style="display: flex; align-items: center; color: rgba(133,15,15,0.15); font-size: 28px; flex-shrink: 0; margin-left: auto;">
                                    <i class="ph-fill ph-users"></i>
                                </div>
                            </div>
                        </div>
                    </div>
 
                    <div class="sidebar-feature-card" style="background: linear-gradient(180deg, #ffffff 0%, #fefcfc 100%); border: 1px solid rgba(226, 232, 240, 0.9); border-radius: 24px; padding: 22px; margin-bottom: 20px; box-shadow: 0 12px 35px -10px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0,0,0,0.02); transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); box-sizing: border-box; width: 100%;">
                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 18px; flex-wrap: wrap; width: 100%;">
                            <div style="display: flex; align-items: center; gap: 10px; min-width: 0; flex: 1 1 180px;">
                                <div style="width: 38px; height: 38px; border-radius: 12px; background: linear-gradient(135deg, rgba(133,15,15,0.08) 0%, rgba(133,15,15,0.15) 100%); border: 1px solid rgba(133,15,15,0.18); display: flex; align-items: center; justify-content: center; color: #850f0f; box-shadow: 0 4px 10px rgba(133,15,15,0.06); flex-shrink: 0;">
                                    <i class="ph-fill ph-sparkle" style="font-size: 18px;"></i>
                                </div>
                                <span style="font-size: 13.5px; font-weight: 800; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px; white-space: normal; word-break: break-word;">Core Amenities</span>
                            </div>
                            <span style="font-size: 10.5px; font-weight: 800; color: #64748b; background: #f1f5f9; padding: 4px 10px; border-radius: 50px; letter-spacing: 0.5px; white-space: normal; word-break: break-word; text-align: center; margin-left: auto;">INCLUDED</span>
                        </div>
                        <div class="amenity-mini-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 0; width: 100%;">
                            @foreach(array_slice($room['amenities'], 0, 2) as $amenity)
                                <div class="amenity-mini-item" style="background: #ffffff; border: 1px solid #f1f5f9; border-radius: 16px; padding: 16px 12px; display: flex; flex-direction: column; align-items: center; text-align: center; gap: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); box-sizing: border-box; width: 100%;">
                                    <div style="width: 44px; height: 44px; border-radius: 12px; background: linear-gradient(135deg, #fef2f2 0%, #fff 100%); border: 1px solid #fee2e2; color: #850f0f; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0; box-shadow: 0 4px 10px rgba(133,15,15,0.06);">
                                        <i class="ph {{ $amenity['icon'] }}"></i>
                                    </div>
                                    <span style="font-size: 13.5px; font-weight: 800; color: #0f172a; line-height: 1.2; white-space: normal; word-break: break-word; text-align: center; max-width: 100%;">{{ $amenity['name'] }}</span>
                                    <span style="font-size: 9px; font-weight: 700; color: #059669; background: rgba(16, 185, 129, 0.08); border: 1px solid rgba(16, 185, 129, 0.22); padding: 3px 8px; border-radius: 50px; letter-spacing: 0.5px; white-space: normal; word-break: break-word; text-align: center; max-width: 100%;">COMPLIMENTARY</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
 
 
                    <div class="sidebar-feature-card" style="background: linear-gradient(180deg, #ffffff 0%, #fefcfc 100%); border: 1px solid rgba(226, 232, 240, 0.9); border-radius: 24px; padding: 22px; margin-bottom: 0; box-shadow: 0 12px 35px -10px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0,0,0,0.02); transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); box-sizing: border-box; width: 100%;">
                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 18px; flex-wrap: wrap; width: 100%;">
                            <div style="display: flex; align-items: center; gap: 10px; min-width: 0; flex: 1 1 180px;">
                                <div style="width: 38px; height: 38px; border-radius: 12px; background: linear-gradient(135deg, rgba(133,15,15,0.08) 0%, rgba(133,15,15,0.15) 100%); border: 1px solid rgba(133,15,15,0.18); display: flex; align-items: center; justify-content: center; color: #850f0f; box-shadow: 0 4px 10px rgba(133,15,15,0.06); flex-shrink: 0;">
                                    <i class="ph-fill ph-headset" style="font-size: 18px;"></i>
                                </div>
                                <div style="display: flex; flex-direction: column; min-width: 0; flex: 1 1 auto;">
                                    <span style="font-size: 13.5px; font-weight: 800; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px; white-space: normal; word-break: break-word;">VIP Concierge</span>
                                    <span style="font-size: 11px; color: #64748b; font-weight: 600; white-space: normal; word-break: break-word; margin-top: 2px;">24/7 Dedicated Support</span>
                                </div>
                            </div>
                            <span style="font-size: 10.5px; font-weight: 800; color: #850f0f; background: rgba(133,15,15,0.06); border: 1px solid rgba(133,15,15,0.12); padding: 4px 10px; border-radius: 50px; letter-spacing: 0.5px; text-transform: uppercase; white-space: normal; word-break: break-word; text-align: center; margin-left: auto;">PRIORITY</span>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 16px; width: 100%;">
                            <a href="tel:+919876543210" style="background: #ffffff; border: 1px solid #fee2e2; padding: 12px 14px; border-radius: 14px; color: #0f172a; text-decoration: none; display: flex; align-items: center; justify-content: space-between; gap: 8px; font-size: 13px; font-weight: 800; transition: all 0.2s ease; box-shadow: 0 4px 12px rgba(133,15,15,0.04); flex-wrap: wrap; box-sizing: border-box; width: 100%;">
                                <span style="display: flex; align-items: center; gap: 8px; white-space: normal; word-break: break-word; flex: 1 1 150px;"><i class="ph-fill ph-phone-call" style="color: #850f0f; font-size: 16px; flex-shrink: 0;"></i> +91 98765 43210</span>
                                <span style="font-size: 10px; background: linear-gradient(135deg, #850f0f, #b91c1c); color: #ffffff; padding: 3px 10px; border-radius: 50px; font-weight: 900; letter-spacing: 0.5px; white-space: nowrap; flex-shrink: 0; box-shadow: 0 2px 6px rgba(133,15,15,0.25); margin-left: auto;">CALL NOW</span>
                            </a>
                            <a href="#" style="background: #ffffff; border: 1px solid #f1f5f9; padding: 12px 14px; border-radius: 14px; color: #334155; text-decoration: none; display: flex; align-items: center; justify-content: space-between; gap: 8px; font-size: 13px; font-weight: 700; transition: all 0.2s ease; box-shadow: 0 4px 12px rgba(0,0,0,0.01); flex-wrap: wrap; box-sizing: border-box; width: 100%;">
                                <span style="display: flex; align-items: center; gap: 8px; white-space: normal; word-break: break-word; flex: 1 1 150px;"><i class="ph-fill ph-shield-check" style="color: #850f0f; font-size: 16px; flex-shrink: 0;"></i> Booking Policies</span>
                                <i class="ph-bold ph-arrow-up-right" style="color: #64748b; font-size: 15px; flex-shrink: 0; margin-left: auto;"></i>
                            </a>
                        </div>
                        <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 12px; padding: 12px; display: flex; align-items: center; justify-content: center; gap: 6px; font-size: 12px; font-weight: 800; color: #059669; white-space: normal; word-break: break-word; text-align: center; box-sizing: border-box; width: 100%;">
                            <i class="ph-fill ph-lock-key" style="font-size: 15px; color: #10b981; flex-shrink: 0;"></i> <span>100% Safe & Guaranteed Booking</span>
                        </div>
                    </div>
                </div>
            </aside>   </div>
            </aside>
            </div>
        </div>

    </main>



    <!-- Lightbox Modal -->
    <div id="lightboxModal" class="modal-overlay" onclick="closeModal(event, 'lightboxModal')">
        <div class="lightbox-content">
            <span class="modal-close" style="color: white; background: rgba(0,0,0,0.5);" onclick="document.getElementById('lightboxModal').classList.remove('active')"><i class="ph ph-x"></i></span>
            <img id="lightboxImg" src="" class="lightbox-img">
            <div class="lightbox-nav">
                <div class="lightbox-btn" onclick="prevImage(event)"><i class="ph ph-caret-left"></i></div>
                <div class="lightbox-btn" onclick="nextImage(event)"><i class="ph ph-caret-right"></i></div>
            </div>
        </div>
    </div>

    <!-- Tooltip / Toast -->
    <div id="toast" class="toast">
        <i class="ph-fill ph-check-circle" style="color: #4ade80;"></i>
        <span id="toastMsg">Link copied!</span>
    </div>

    @include('partials.footer')

    <script>
        // Room Data for Gallery
        const galleryImages = @if(str_contains($roomId, 'standard'))
            [
                "{{ asset('assets/standard/standard1.JPG') }}",
                "{{ asset('assets/standard/standard2.JPG') }}",
                "{{ asset('assets/standard/standard3.JPG') }}"
            ]
        @elseif(str_contains($roomId, 'advance'))
            [
                "{{ asset('assets/standard/std1.JPG') }}",
                "{{ asset('assets/standard/std2.JPG') }}",
                "{{ asset('assets/standard/std3.JPG') }}",
                "{{ asset('assets/standard/std4.JPG') }}",
                "{{ asset('assets/standard/std5.JPG') }}",
                "{{ asset('assets/standard/std6.JPG') }}"
            ]
        @elseif($roomId === 'suite-room')
            [
                "{{ asset('assets/standard/suite1.JPG') }}",
                "{{ asset('assets/standard/suite2.JPG') }}",
                "{{ asset('assets/standard/suite3.JPG') }}",
                "{{ asset('assets/standard/suite4.JPG') }}",
                "{{ asset('assets/standard/suite5.JPG') }}",
                "{{ asset('assets/standard/suite6.JPG') }}",
                "{{ asset('assets/standard/suite7.JPG') }}"
            ]
        @elseif($roomId === 'conference-hall')
            [
                "{{ asset('assets/standard/con1.JPG') }}",
                "{{ asset('assets/standard/con2.JPG') }}",
                "{{ asset('assets/standard/con3.JPG') }}",
                "{{ asset('assets/standard/con4.JPG') }}",
                "{{ asset('assets/standard/con5.JPG') }}"
            ]
        @elseif($roomId === 'glass-room')
            [
                "{{ asset('assets/standard/glass1.JPG') }}",
                "{{ asset('assets/standard/glass2.JPG') }}",
                "{{ asset('assets/standard/glass3.JPG') }}",
                "{{ asset('assets/standard/glass4.JPG') }}",
                "{{ asset('assets/standard/glass5.JPG') }}"
            ]
        @else
            [
                "{{ $room['img'] }}",
                "https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80",
                "https://images.unsplash.com/photo-1584132967334-10e028bd69f7?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80",
                "https://images.unsplash.com/photo-1590490360182-c33d57733427?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80"
            ]
        @endif;
        let currentImgIndex = 0;

        function changeImage(src, thumb) {
            const mainImg = document.getElementById('mainImage');
            mainImg.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
            mainImg.style.opacity = '0.1';
            mainImg.style.transform = 'scale(0.97)';
            mainImg.style.filter = 'blur(6px)';
            
            setTimeout(() => {
                mainImg.src = src;
                mainImg.onload = () => {
                    mainImg.style.opacity = '1';
                    mainImg.style.transform = 'scale(1)';
                    mainImg.style.filter = 'blur(0px)';
                    setTimeout(() => {
                        mainImg.style.transition = 'transform 0.6s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.3s ease, filter 0.3s ease';
                    }, 300);
                };
            }, 180);

            document.querySelectorAll('.thumb-item').forEach(t => t.classList.remove('active'));
            thumb.classList.add('active');
            currentImgIndex = galleryImages.indexOf(src);
        }

        function switchTab(tabId, btn) {
            document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            
            const targetPane = document.getElementById(tabId);
            if(targetPane) {
                targetPane.classList.add('active');
            }
            if(btn) {
                btn.classList.add('active');
            }
        }



        // Lightbox
        function openLightbox() {
            const modal = document.getElementById('lightboxModal');
            const img = document.getElementById('lightboxImg');
            img.src = document.getElementById('mainImage').src;
            modal.classList.add('active');
        }

        function nextImage(event) {
            event.stopPropagation();
            currentImgIndex = (currentImgIndex + 1) % galleryImages.length;
            document.getElementById('lightboxImg').src = galleryImages[currentImgIndex];
        }

        function prevImage(event) {
            event.stopPropagation();
            currentImgIndex = (currentImgIndex - 1 + galleryImages.length) % galleryImages.length;
            document.getElementById('lightboxImg').src = galleryImages[currentImgIndex];
        }

        function closeModal(event, modalId) {
            if (event.target.id === modalId) {
                document.getElementById(modalId).classList.remove('active');
            }
        }

        // Toast
        function showToast(msg, iconClass = "ph ph-check-circle") {
            const toast = document.getElementById('toast');
            document.getElementById('toastMsg').innerText = msg;
            toast.classList.add('active');
            setTimeout(() => toast.classList.remove('active'), 3000);
        }

        // Navigation Links
        function scrollToAmenities() {
            const btn = document.querySelectorAll('.tab-btn')[1];
            switchTab('amenities', btn);
            document.getElementById('amenities').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        function scrollToReviews() {
            const btn = document.querySelectorAll('.tab-btn')[3];
            switchTab('reviews', btn);
            document.getElementById('reviews').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        // Handle Escape Key
        document.addEventListener('keydown', (e) => {
            if (e.key === "Escape") {
                document.querySelectorAll('.modal-overlay').forEach(m => m.classList.remove('active'));
            }
            if (document.getElementById('lightboxModal').classList.contains('active')) {
                if (e.key === "ArrowRight") nextImage(new Event('click'));
                if (e.key === "ArrowLeft") prevImage(new Event('click'));
            }
        });



        function openHelpModal() {
            document.getElementById('helpModal').classList.add('active');
        }

        function closeHelpModal() {
            document.getElementById('helpModal').classList.remove('active');
            document.getElementById('helpDropdownOptions').classList.remove('active');
        }

        function toggleHelpDropdown(event) {
            event.stopPropagation();
            document.getElementById('helpDropdownOptions').classList.toggle('active');
        }

        function selectHelpOption(val) {
            document.getElementById('selectedSubject').innerText = val;
            document.getElementById('helpDropdownOptions').classList.remove('active');
        }

        window.onclick = function(event) {
            const helpModal = document.getElementById('helpModal');
            if (event.target == helpModal) {
                closeHelpModal();
            }
        };
    </script>
    @include('partials.help-modal')

</body>
</html>
