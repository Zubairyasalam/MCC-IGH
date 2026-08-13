/**
 * MCC-IGH Multi-Room Cart Management Script
 * Persists selected rooms in localStorage and syncs UI across room pages and booking form.
 */

(function () {
    const CART_KEY = 'mcc_igh_cart';

    const CartManager = {
        getItems: function () {
            try {
                const stored = localStorage.getItem(CART_KEY);
                return stored ? JSON.parse(stored) : [];
            } catch (e) {
                console.error('Cart parse error:', e);
                return [];
            }
        },

        saveItems: function (items) {
            try {
                localStorage.setItem(CART_KEY, JSON.stringify(items));
                this.syncUI();
            } catch (e) {
                console.error('Cart save error:', e);
            }
        },

        addItem: function (roomObj) {
            if (!roomObj || !roomObj.id) return false;
            let items = this.getItems();
            // Prevent duplicates
            const exists = items.some(item => item.id.toString().toLowerCase() === roomObj.id.toString().toLowerCase() || item.name.toString().toLowerCase() === roomObj.name.toString().toLowerCase());
            if (!exists) {
                items.push({
                    id: roomObj.id,
                    name: roomObj.name,
                    category: roomObj.category || 'Standard Room',
                    price: roomObj.price || '1400',
                    priceText: roomObj.priceText || '₹1,400',
                    rateType: roomObj.rateType || '12 Hours',
                    capacity: parseInt(roomObj.capacity || 2, 10),
                    image: roomObj.image || ''
                });
                this.saveItems(items);
                return true;
            }
            return false;
        },

        removeItem: function (roomIdOrName) {
            let items = this.getItems();
            items = items.filter(item => 
                item.id.toString().toLowerCase() !== roomIdOrName.toString().toLowerCase() && 
                item.name.toString().toLowerCase() !== roomIdOrName.toString().toLowerCase()
            );
            this.saveItems(items);
        },

        clearCart: function () {
            this.saveItems([]);
        },

        hasItem: function (roomIdOrName) {
            if (!roomIdOrName) return false;
            const items = this.getItems();
            return items.some(item => 
                item.id.toString().toLowerCase() === roomIdOrName.toString().toLowerCase() || 
                item.name.toString().toLowerCase() === roomIdOrName.toString().toLowerCase()
            );
        },

        count: function () {
            return this.getItems().length;
        },

        syncUI: function () {
            const items = this.getItems();

            // Update Header Cart Badge
            const badgeEls = document.querySelectorAll('.cart-count-badge');
            badgeEls.forEach(el => {
                const count = parseInt(items.length, 10) || 0;
                el.textContent = count;
                if (count > 0) {
                    el.style.setProperty('display', 'inline-flex', 'important');
                } else {
                    el.style.setProperty('display', 'none', 'important');
                }
            });

            // Sync Buttons on Room Cards
            const cartBtns = document.querySelectorAll('[data-cart-room]');
            cartBtns.forEach(btn => {
                const roomName = btn.getAttribute('data-cart-room');
                if (this.hasItem(roomName)) {
                    btn.classList.add('in-cart');
                    btn.innerHTML = '<i class="ph-bold ph-check"></i> In Reservation';
                    btn.style.background = '#059669';
                    btn.style.borderColor = '#059669';
                    btn.style.color = '#ffffff';
                } else {
                    btn.classList.remove('in-cart');
                    const isIconOnly = btn.hasAttribute('data-cart-icon-only');
                    if (isIconOnly) {
                        btn.innerHTML = '<i class="ph-bold ph-calendar-plus"></i>';
                    } else {
                        btn.innerHTML = '<i class="ph-bold ph-calendar-plus"></i> Add to Reservation';
                    }
                    btn.style.background = '';
                    btn.style.borderColor = '';
                    btn.style.color = 'var(--primary-color, #850f0f)';
                }
            });

            // Update Floating Cart Bar
            this.renderFloatingCartBar(items);

            // Update Cart Modal if open
            this.renderCartModalContent(items);

            // Trigger custom event for booking form listener if active
            window.dispatchEvent(new CustomEvent('ighCartUpdated', { detail: { items: items } }));
        },

        renderFloatingCartBar: function (items) {
            let bar = document.getElementById('ighFloatingCartBar');
            
            // If on booking form or success/receipt page, don't show floating bar
            const path = window.location.pathname.toLowerCase();
            if (path.includes('/booking') || path.includes('/success') || path.includes('/receipt') || path.includes('/failure')) {
                if (bar) bar.style.display = 'none';
                return;
            }

            if (items.length === 0) {
                if (bar) bar.style.display = 'none';
                return;
            }

            if (!bar) {
                bar = document.createElement('div');
                bar.id = 'ighFloatingCartBar';
                bar.className = 'igh-floating-cart-bar';
                document.body.appendChild(bar);
            }

            const roomNamesStr = items.map(i => i.name).join(', ');
            let totalCap = items.reduce((acc, i) => acc + (parseInt(i.capacity) || 2), 0);

            bar.innerHTML = `
                <div class="cart-bar-content">
                    <div class="cart-bar-info">
                        <div class="cart-bar-title">
                            <span class="cart-badge-icon"><i class="ph-bold ph-shopping-bag-open"></i></span>
                            <strong>${items.length} ${items.length === 1 ? 'Room' : 'Rooms'} Selected</strong>
                            <span class="cart-cap-tag"><i class="ph-bold ph-users"></i> Up to ${totalCap} Guests</span>
                        </div>
                        <div class="cart-bar-rooms" title="${roomNamesStr}">${roomNamesStr}</div>
                    </div>
                    <div class="cart-bar-actions">
                        <button type="button" class="cart-bar-btn outline" onclick="window.IGHCart.openModal()">
                            <i class="ph-bold ph-list-checks"></i> View My Reservations
                        </button>
                        <a href="${this.getBookingFormUrl()}" class="cart-bar-btn primary">
                            Book Now (${items.length}) <i class="ph-bold ph-arrow-right"></i>
                        </a>
                    </div>
                </div>
            `;
            bar.style.display = 'block';
        },

        getBookingFormUrl: function () {
            const items = this.getItems();
            if (items.length === 0) return '/booking-form';
            const roomNames = items.map(i => encodeURIComponent(i.name)).join(',');
            return `/booking-form?rooms=${roomNames}`;
        },

        openModal: function () {
            let modal = document.getElementById('ighCartModal');
            if (!modal) {
                modal = this.createModalElement();
                document.body.appendChild(modal);
            }
            this.renderCartModalContent(this.getItems());
            modal.classList.add('active');
        },

        closeModal: function () {
            const modal = document.getElementById('ighCartModal');
            if (modal) modal.classList.remove('active');
        },

        createModalElement: function () {
            const modal = document.createElement('div');
            modal.id = 'ighCartModal';
            modal.className = 'modal-overlay cart-modal-overlay';
            modal.innerHTML = `
                <div class="modal-card cart-modal-card">
                    <div class="modal-header-custom" style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                        <h3 style="margin: 0; font-size: 1.25rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                            <i class="ph-bold ph-list-checks" style="color: var(--primary-color, #850f0f);"></i> My Reservations
                        </h3>
                        <button type="button" class="modal-close-custom" onclick="window.IGHCart.closeModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #64748b;">
                            <i class="ph-bold ph-x"></i>
                        </button>
                    </div>
                    <div class="modal-body" id="cartModalBody" style="padding: 1.5rem; max-height: 60vh; overflow-y: auto;">
                        <!-- Dynamic items -->
                    </div>
                    <div class="modal-footer-custom" id="cartModalFooter" style="padding: 1.25rem 1.5rem; background: #f8fafc; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                        <!-- Dynamic footer -->
                    </div>
                </div>
            `;
            return modal;
        },

        renderCartModalContent: function (items) {
            const body = document.getElementById('cartModalBody');
            const footer = document.getElementById('cartModalFooter');
            if (!body || !footer) return;

            if (items.length === 0) {
                body.innerHTML = `
                    <div style="text-align: center; padding: 2rem 1rem; color: #64748b;">
                        <i class="ph-bold ph-calendar-blank" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 0.75rem; display: block;"></i>
                        <p style="font-weight: 600; margin-bottom: 0.5rem; font-size: 1.1rem;">My Reservations is currently empty</p>
                        <p style="font-size: 0.9rem; color: #94a3b8;">Browse through our Standard, Advance, or Conference rooms and select rooms to book together.</p>
                    </div>
                `;
                footer.innerHTML = `
                    <button type="button" class="btn btn-outline" onclick="window.IGHCart.closeModal()" style="width: 100%;">Close</button>
                `;
                return;
            }

            let html = '<div class="cart-modal-items-list" style="display: flex; flex-direction: column; gap: 12px;">';
            let totalCap = 0;

            items.forEach((item, index) => {
                totalCap += (parseInt(item.capacity) || 2);
                html += `
                    <div class="cart-modal-item" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 42px; height: 42px; border-radius: 10px; background: rgba(133, 15, 15, 0.08); color: var(--primary-color, #850f0f); display: flex; align-items: center; justify-content: center; font-size: 1.25rem; font-weight: 800;">
                                ${index + 1}
                            </div>
                            <div>
                                <h4 style="margin: 0; font-size: 1rem; font-weight: 700; color: #1e293b;">${item.name}</h4>
                                <span style="font-size: 0.8rem; color: #64748b; font-weight: 500;">
                                    ${item.category} • <i class="ph-bold ph-users"></i> Max ${item.capacity} guests
                                </span>
                            </div>
                        </div>
                        <button type="button" onclick="window.IGHCart.removeItem('${item.name}')" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border: none; width: 34px; height: 34px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" title="Remove Room">
                            <i class="ph-bold ph-trash"></i>
                        </button>
                    </div>
                `;
            });

            html += '</div>';
            body.innerHTML = html;

            footer.innerHTML = `
                <div style="display: flex; align-items: center; justify-content: space-between; width: 100%; gap: 12px; flex-wrap: wrap;">
                    <button type="button" onclick="window.IGHCart.clearCart()" style="background: none; border: none; color: #ef4444; font-weight: 700; font-size: 0.85rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; padding: 6px 10px; border-radius: 8px; white-space: nowrap;">
                        <i class="ph-bold ph-trash" style="font-size: 1rem;"></i> Clear All
                    </button>
                    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                        <button type="button" onclick="window.IGHCart.closeModal()" class="btn btn-outline" style="padding: 10px 18px; font-size: 0.85rem; font-weight: 700; border-radius: 10px; white-space: nowrap; height: auto;">KEEP BROWSING</button>
                        <a href="${this.getBookingFormUrl()}" class="btn" style="padding: 10px 20px; font-size: 0.85rem; background: var(--primary-color, #850f0f); color: #ffffff; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; border-radius: 10px; font-weight: 800; white-space: nowrap; height: auto; box-shadow: 0 4px 12px rgba(133, 15, 15, 0.25);">
                            PROCEED TO FORM (${items.length} ${items.length === 1 ? 'ROOM' : 'ROOMS'}) <i class="ph-bold ph-arrow-right"></i>
                        </a>
                    </div>
                </div>
            `;
        },

        toggleRoom: function (roomObj) {
            if (this.hasItem(roomObj.name)) {
                this.removeItem(roomObj.name);
            } else {
                this.addItem(roomObj);
            }
        },

        bookNowDirect: function (roomObj) {
            this.addItem(roomObj);
            window.location.href = this.getBookingFormUrl();
        }
    };

    window.IGHCart = CartManager;

    document.addEventListener('DOMContentLoaded', function () {
        // Inject Floating Styles dynamically if not added
        if (!document.getElementById('ighCartStyles')) {
            const style = document.createElement('style');
            style.id = 'ighCartStyles';
            style.textContent = `
                .cart-btn-header {
                    position: relative;
                    background: rgba(133, 15, 15, 0.08);
                    color: var(--primary-color, #850f0f);
                    border: 1px solid rgba(133, 15, 15, 0.2);
                    padding: 8px 14px;
                    border-radius: 10px;
                    font-weight: 700;
                    font-size: 0.85rem;
                    cursor: pointer;
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    transition: all 0.2s ease;
                }
                .cart-btn-header:hover {
                    background: var(--primary-color, #850f0f);
                    color: #ffffff;
                }
                .cart-count-badge {
                    background: #ef4444;
                    color: #ffffff;
                    font-size: 11px;
                    font-weight: 800;
                    min-width: 18px;
                    height: 18px;
                    border-radius: 50px;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    padding: 0 4px;
                }
                .igh-floating-cart-bar {
                    position: fixed;
                    bottom: 24px;
                    left: 50%;
                    transform: translateX(-50%);
                    width: 90%;
                    max-width: 720px;
                    background: #0f172a;
                    color: #ffffff;
                    border-radius: 16px;
                    padding: 12px 20px;
                    box-shadow: 0 20px 35px -10px rgba(0,0,0,0.35);
                    z-index: 9999;
                    animation: slideUpCart 0.3s cubic-bezier(0.16, 1, 0.3, 1);
                    border: 1px solid rgba(255,255,255,0.1);
                }
                @keyframes slideUpCart {
                    from { transform: translate(-50%, 40px); opacity: 0; }
                    to { transform: translate(-50%, 0); opacity: 1; }
                }
                .cart-bar-content {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 16px;
                }
                .cart-bar-info {
                    display: flex;
                    flex-direction: column;
                    gap: 2px;
                    min-width: 0;
                }
                .cart-bar-title {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    font-size: 0.95rem;
                }
                .cart-badge-icon {
                    color: #38bdf8;
                    font-size: 1.1rem;
                }
                .cart-cap-tag {
                    font-size: 0.75rem;
                    background: rgba(255,255,255,0.15);
                    padding: 2px 8px;
                    border-radius: 50px;
                    color: #cbd5e1;
                }
                .cart-bar-rooms {
                    font-size: 0.8rem;
                    color: #94a3b8;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }
                .cart-bar-actions {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    flex-shrink: 0;
                }
                .cart-bar-btn {
                    padding: 8px 14px;
                    border-radius: 10px;
                    font-size: 0.85rem;
                    font-weight: 700;
                    cursor: pointer;
                    text-decoration: none;
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    transition: all 0.2s;
                    border: none;
                }
                .cart-bar-btn.outline {
                    background: rgba(255,255,255,0.1);
                    color: #ffffff;
                }
                .cart-bar-btn.outline:hover {
                    background: rgba(255,255,255,0.2);
                }
                .cart-bar-btn.primary {
                    background: var(--primary-color, #850f0f);
                    color: #ffffff;
                }
                .cart-bar-btn.primary:hover {
                    opacity: 0.9;
                }
                .cart-modal-overlay {
                    position: fixed;
                    top: 0; left: 0; right: 0; bottom: 0;
                    background: rgba(15, 23, 42, 0.6);
                    backdrop-filter: blur(4px);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    z-index: 10000;
                    opacity: 0;
                    pointer-events: none;
                    transition: opacity 0.25s ease;
                }
                .cart-modal-overlay.active {
                    opacity: 1;
                    pointer-events: auto;
                }
                .cart-modal-card {
                    background: #ffffff;
                    width: 90%;
                    max-width: 540px;
                    border-radius: 20px;
                    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
                    overflow: hidden;
                    transform: scale(0.95);
                    transition: transform 0.25s ease;
                }
                .cart-modal-overlay.active .cart-modal-card {
                    transform: scale(1);
                }
                @media (max-width: 640px) {
                    .cart-bar-content { flex-direction: column; align-items: stretch; gap: 10px; }
                    .cart-bar-actions { justify-content: space-between; }
                    .cart-bar-btn { flex: 1; justify-content: center; }
                }
            `;
            document.head.appendChild(style);
        }

        // Auto clear cart if user reaches success page
        const path = window.location.pathname.toLowerCase();
        if (path.includes('/success') || path.includes('/receipt')) {
            CartManager.clearCart();
        } else {
            CartManager.syncUI();
        }
    });
})();
