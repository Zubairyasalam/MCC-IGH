<header class="header-container">
    <div class="header-left">
        <a href="{{ route('home') }}" class="logo-link">
            <img src="{{ asset('assets/logo_transparent.png') }}" alt="MCC Logo" class="header-logo">
        </a>
    </div>

    <div class="header-center">
        <div class="logo-text">
            <span class="mcc-text">MCC</span>
            <span class="igh-text">INTERNATIONAL GUEST HOUSE</span>
        </div>
    </div>
    
    <div class="header-right" style="display: flex; align-items: center; gap: 10px;">
        <button type="button" class="cart-btn-header" onclick="if(window.IGHCart) window.IGHCart.openModal();" title="View Selected Rooms Cart">
            <i class="ph-bold ph-shopping-cart-simple" style="font-size: 1.1rem;"></i>
            <span class="cart-label-text">CART</span>
            <span class="cart-count-badge" style="display: none;">0</span>
        </button>

        @if(isset($showHelpBtn) && $showHelpBtn)
            <button class="help-btn" onclick="openHelpModal()">
                <i class="ph ph-question-circle"></i>
                <span>SUPPORT</span>
            </button>
        @endif

        @if(isset($headerBackBtn))
            <a href="{{ $headerBackBtn['url'] }}" class="btn-header-back" style="text-decoration:none;">
                <i class="ph-bold ph-arrow-left"></i>
                <span>{{ $headerBackBtn['label'] ?? 'Back' }}</span>
            </a>
        @else
            <div class="profile-dropdown">
                <button class="profile-btn" onclick="toggleDropdown(event)">
                    <i class="ph ph-user"></i>
                </button>
                <div class="dropdown-menu" id="profileMenu">
                    @auth
                        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="dropdown-item logout">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="dropdown-item">Login</a>
                    @endauth
                </div>
            </div>
        @endif
    </div>
</header>

<script src="{{ asset('js/cart.js') }}?v={{ time() }}"></script>



<script>
    function toggleDropdown(event) {
        event.stopPropagation();
        document.getElementById('profileMenu').classList.toggle('active');
    }

    document.addEventListener('click', function(event) {
        const profileMenu = document.getElementById('profileMenu');
        if (profileMenu && !profileMenu.contains(event.target)) {
            profileMenu.classList.remove('active');
        }
    });

    function openHelpModal() {
        const modal = document.getElementById('helpModal') || document.getElementById('helpModalOverlay');
        if(modal) modal.classList.add('active');
    }
</script>
