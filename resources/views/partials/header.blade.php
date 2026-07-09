<header class="header-container">
    <div class="header-left">
        <a href="{{ route('home') }}" class="logo-link">
            <img src="{{ asset('assets/logo.png') }}" alt="MCC Logo" class="header-logo">
        </a>
    </div>

    <div class="header-center">
        <div class="logo-text">
            <span class="mcc-text">MCC</span>
            <span class="igh-text">INTERNATIONAL GUEST HOUSE</span>
        </div>
    </div>
    
    <div class="header-right">
        @if(isset($showHelpBtn) && $showHelpBtn)
            <button class="help-btn" onclick="openHelpModal()">
                <i class="ph ph-question"></i>
                <span>Support</span>
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
                    <i class="ph-duotone ph-user-circle"></i>
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
