<!-- FOOTER SECTION -->
<footer class="main-footer">
    <div class="footer-content">
        <div class="footer-column brand-column" style="display: flex; flex-direction: column; justify-content: flex-start;">
            <div class="footer-logo" style="margin-bottom: 1.2rem;">
                <img src="{{ asset('assets/logo.png') }}" alt="MCC Logo" style="max-height: 180px; width: 100%; max-width: 280px; object-fit: contain; display: block; filter: grayscale(1) invert(1) brightness(2); mix-blend-mode: screen; transform-origin: left center;">
            </div>
            <p style="margin: 0; line-height: 1.6; color: #64748b; font-size: 0.9rem;">
                Premium hospitality and comfortable accommodation located in the serene surroundings of the Madras Christian College campus.
            </p>
        </div>
        <div class="footer-column">
            <h4>Quick Links</h4>
            <ul>
                <li><a href="javascript:void(0)" onclick="document.querySelector('.main-image-slider').scrollIntoView({behavior:'smooth'})"><i class="ph-bold ph-caret-right" style="font-size: 0.8rem;"></i> Home</a></li>
                <li><a href="javascript:void(0)" onclick="document.querySelector('.explore-rooms-section').scrollIntoView({behavior:'smooth'})"><i class="ph-bold ph-caret-right" style="font-size: 0.8rem;"></i> Spaces</a></li>
                <li><a href="javascript:void(0)" onclick="openHelpModal()"><i class="ph-bold ph-caret-right" style="font-size: 0.8rem;"></i> Contact</a></li>
            </ul>
        </div>
        <div class="footer-column">
            <h4>Contact Info</h4>
            <ul>
                <li><i class="ph-fill ph-envelope-simple" style="color: var(--primary-color);"></i> <a href="mailto:contact@mmip.example.com" class="footer-contact-link">contact@mmip.example.com</a></li>
                <li><i class="ph-fill ph-phone" style="color: var(--primary-color);"></i> <a href="tel:+919876543210" class="footer-contact-link">+91 98765 43210</a></li>
            </ul>
            <div class="footer-social-icons">
                <a href="https://wa.me/919876543210" target="_blank" rel="noopener" title="WhatsApp"><i class="ph-bold ph-whatsapp-logo"></i></a>
                <a href="mailto:contact@mmip.example.com" title="Email"><i class="ph-bold ph-envelope-simple"></i></a>
                <a href="tel:+919876543210" title="Call"><i class="ph-bold ph-phone"></i></a>
            </div>
        </div>
        <div class="footer-column">
            <h4>Address</h4>
            <div style="display: flex; gap: 10px; align-items: flex-start;">
                <i class="ph-fill ph-map-pin" style="color: var(--primary-color); font-size: 1.2rem; margin-top: 2px;"></i>
                <p style="margin: 0; line-height: 1.6; color: #475569; font-size: 0.95rem;">
                    MCC MRF Innovation Park,<br>
                    Madras Christian College,<br>
                    East Tambaram,<br>
                    Chennai - 600059,<br>
                    Tamil Nadu, India
                </p>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; {{ date('Y') }} MCC-MRF Innovation Park. All rights reserved.</p>
    </div>
</footer>
