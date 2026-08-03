<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Space - MCC IGH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    @include('partials.dynamic-styles')
    <style>
        /* Fix ONLY for Proceed to Payment / Confirm Booking button */
        #proceedBtn:not(:disabled) {
            background-color: #ff6a00 !important;
            color: #ffffff !important;
            border-color: #ff6a00 !important;
            transform: none !important;
            scale: 1 !important;
            box-shadow: none !important;
            cursor: pointer !important;
        }
        #proceedBtn:not(:disabled):hover,
        #proceedBtn:not(:disabled):focus {
            background-color: #e65c00 !important;
            color: #ffffff !important;
            border-color: #e65c00 !important;
            transform: none !important;
            scale: 1 !important;
            box-shadow: none !important;
        }
        #proceedBtn:not(:disabled):active {
            background-color: #e65c00 !important;
            color: #ffffff !important;
            border-color: #e65c00 !important;
            transform: none !important;
            scale: 1 !important;
            box-shadow: none !important;
        }
        #proceedBtn:disabled {
            background-color: #ccc !important;
            color: #888 !important;
            border-color: #ccc !important;
            cursor: not-allowed !important;
            transform: none !important;
            scale: 1 !important;
        }
        #proceedBtn,
        .confirm-booking-btn,
        .confirm-booking-btn:active,
        .confirm-booking-btn:focus,
        #proceedBtn:active,
        #proceedBtn:focus {
            transform: none !important;
            scale: 1 !important;
            transition: none !important;
            opacity: 1 !important;
        }
    </style>
</head>

<body>
    <header>
        <div class="header-left">
            <img src="{{ asset('assets/logo_transparent.png') }}" alt="Logo" class="header-logo" style="image-rendering: -webkit-optimize-contrast; image-rendering: crisp-edges;">
        </div>
        <div class="header-center">
            <h1>MCC International Guest House</h1>
        </div>
        <div class="header-right">
            <div class="profile-dropdown">
                <button class="profile-btn" onclick="toggleDropdown(event)">
                    <i class="ph-fill ph-user-circle" style="color: var(--primary-color); font-size:1.75rem;"></i>
                </button>
                <div class="dropdown-menu" id="profileMenu">
                    @auth
                        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="dropdown-item logout" style="width:100%; text-align:left; background:none; border:none; padding:10px 15px; cursor:pointer; font-family:inherit;">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="dropdown-item">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>
    <main>
        <!-- Booking Progress Indicator -->
        <div class="progress-container">
            <div class="step completed" onclick="window.location.href='{{ route('home') }}'" style="cursor:pointer;">
                <div class="step-num"><i class="ph-bold ph-check"></i></div> Select Space
            </div>
            <div class="step-divider"></div>
            <div class="step active">
                <div class="step-num">2</div> Enter Details
            </div>
            <div class="step-divider"></div>
            <div class="step">
                <div class="step-num">3</div> Confirm Payment
            </div>
        </div>

        <div class="booking-layout">
            <!-- Left: Form -->
            <div class="booking-form-container">
                <div class="title-section" style="text-align: left; margin-bottom: 1.5rem;">
                    <h2>Guest & Booking <span class="primary-text">Details</span></h2>
                    <p>Please fill in the information below.</p>
                </div>

                @if($errors->any())
                    <div style="background: #fee2e2; border: 1px solid #ef4444; color: #b91c1c; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                        <ul style="margin: 0; padding-left: 1.5rem;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('error'))
                    <div style="background: #fee2e2; border: 1px solid #ef4444; color: #b91c1c; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                        {{ session('error') }}
                    </div>
                @endif

                <form id="bookingForm" action="{{ route('booking.store') }}" method="POST" onsubmit="proceedToPayment(event)" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="room_name" id="hidden_room_name" value="{{ request('room') }}">
                    <input type="hidden" name="total_price" id="hidden_total_price">

                    <div class="form-row">
                        <div class="form-group half">
                            <label for="fullName">Full Name <span class="required">*</span></label>
                            <input type="text" id="fullName" name="name" required placeholder="John Doe">
                        </div>
                        <div class="form-group half">
                            <label for="email">Email <span class="required">*</span></label>
                            <input type="email" id="email" name="email" required placeholder="john@example.com">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group half">
                            <label for="phone">Phone Number <span class="required">*</span></label>
                            <input type="tel" id="phone" name="phone" required placeholder="+91 98765 43210"
                                pattern="[0-9\+\-\s]{10,15}">
                        </div>
                        <div class="form-group half">
                            <label for="company">GST ID <span>(Optional)</span></label>
                            <input type="text" id="company" name="gst_id" placeholder="Example Corp GST">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group half">
                            <label for="purpose">Purpose of Booking <span class="required">*</span></label>
                            <select id="purpose" name="purpose" required class="form-select">
                                <option value="" disabled selected>Select purpose</option>
                                <option value="Meeting">Meeting</option>
                                <option value="Training">Training</option>
                                <option value="Workshop">Workshop</option>
                                <option value="Interview">Interview</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group half">
                            <label for="guests">Number of Guests <span class="required">*</span></label>
                            <input type="number" id="guests" name="guests" required min="1" placeholder="E.g., 5"
                                oninput="validateGuests()">
                            <small id="guestWarning" class="warning-text" style="display:none;"></small>
                        </div>
                    </div>

                    <h3
                        style="margin: 1.5rem 0 1rem; font-size: 1.1rem; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">
                        Schedule</h3>

                    <div class="form-row">
                        <div class="form-group third">
                            <label for="bookingDate">Date <span class="required">*</span></label>
                            <input type="date" id="bookingDate" name="booking_date" required onchange="calculateTotal()">
                        </div>
                        <div class="form-group third">
                            <label for="startTime">Start Time <span class="required">*</span></label>
                            <input type="time" id="startTime" name="start_time" required onchange="calculateTotal()">
                        </div>
                        <div class="form-group third">
                            <label for="endTime">End Time <span class="required">*</span></label>
                            <input type="time" id="endTime" name="end_time" required onchange="calculateTotal()">
                        </div>
                    </div>

                    <div id="timeWarning" class="modal-warning"
                        style="display:none; margin-top:-0.5rem; margin-bottom: 1.5rem;"></div>

                    <div class="form-group checkbox-group">
                        <label class="checkbox-container">
                            <input type="checkbox" id="terms" required>
                            <span class="checkmark"></span>
                            I agree to the <a href="#" onclick="event.preventDefault()">Terms & Conditions</a> and
                            cancellation policy.
                        </label>
                    </div>

                    <div class="form-group" style="margin-top: 1rem; margin-bottom: 2rem;">
                        <label for="referral_attachment" style="font-weight: 600; color: var(--text-color); margin-bottom: 0.5rem; display: block;">Referral Attachment</label>
                        <input type="file" id="referral_attachment" name="referral_attachment" class="form-input" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        <small class="form-helper" style="color: var(--text-light); margin-top: 0.25rem; display: block;">Upload a referral document if applicable (PDF, Image, etc.)</small>
                    </div>

                    <div class="button-group" style="margin-top: 1.5rem;">
                        <button type="button" class="btn btn-outline" onclick="window.location.href='{{ route('home') }}'"><i
                                class="ph-bold ph-arrow-left"></i> Back to Spaces</button>
                        <button type="submit" class="btn confirm-booking-btn" id="proceedBtn" disabled><i
                                class="ph-bold ph-credit-card"></i> Proceed to Payment</button>
                    </div>
                </form>
            </div>

            <!-- Right: Summary -->
            <div class="booking-summary-sidebar">
                <div class="summary-card">
                    <div class="summary-image">
                        <img id="summaryImage" src="" alt="Room Image">
                    </div>
                    <div class="summary-details">
                        <h3 id="displayRoomName">-</h3>
                        <div class="summary-meta">
                            <span id="displayCapacity"><i class="ph-fill ph-users"></i> -</span>
                        </div>
                        <div class="summary-amenities" id="displayAmenities">
                            <!-- Injected by JS -->
                        </div>

                        <hr class="summary-divider">

                        <div class="billing-details">
                            <h4>Booking Summary</h4>
                            <div class="bill-row">
                                <span>Rate</span>
                                <span><span class="rupee-symbol">₹</span><span id="displayRoomPrice">0</span> / hr</span>
                            </div>
                            <div class="bill-row" id="durationRow" style="display:none;">
                                <span>Duration (<span id="calcHours">0</span> hrs)</span>
                                <span><span class="rupee-symbol">₹</span><span id="calcBaseTotal">0</span></span>
                            </div>
                            <div class="bill-row" id="taxRow" style="display:none;">
                                <span>Taxes & Fees (18%)</span>
                                <span><span class="rupee-symbol">₹</span><span id="calcTax">0</span></span>
                            </div>
                        </div>

                        <div class="total-box">
                            <span>Estimated Total</span>
                            <span class="total-amount"><span class="rupee-symbol">₹</span><span id="displayTotalPrice">0.00</span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="{{ asset('js/script.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', initBookingPage);
    </script>
</body>

</html>