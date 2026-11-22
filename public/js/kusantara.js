document.addEventListener('DOMContentLoaded', () => {
    const mobileToggle = document.getElementById('mobileToggle');
    const navLinks = document.getElementById('navLinks');

    if (mobileToggle && navLinks) {
        mobileToggle.addEventListener('click', () => {
            navLinks.classList.toggle('show');
        });
    }

    const searchBtn = document.getElementById('searchBtn');
    const searchBar = document.getElementById('searchBar');
    const closeSearch = document.getElementById('closeSearch');

    if (searchBtn && searchBar) {
        searchBtn.addEventListener('click', () => {
            searchBar.classList.toggle('active');
            const input = searchBar.querySelector('input');
            if (searchBar.classList.contains('active') && input) {
                input.focus();
            }
        });
    }

    if (closeSearch && searchBar) {
        closeSearch.addEventListener('click', () => {
            searchBar.classList.remove('active');
        });
    }

    const sections = document.querySelectorAll('section[id]');
    const navItems = document.querySelectorAll('.nav-links a[href^="#"]');

    window.addEventListener('scroll', () => {
        let current = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop - 120;
            if (window.scrollY >= sectionTop) {
                current = section.getAttribute('id');
            }
        });

        navItems.forEach(link => {
            link.classList.remove('active');
            if (current && link.getAttribute('href') === `#${current}`) {
                link.classList.add('active');
            }
        });
    });

/* ======================================================================
       UNIFIED CART ENGINE (KATALOG <-> DRAWER <-> BADGE) - PT. KUSANTARA
       ====================================================================== */
    const CART_KEY = 'kusantara_cart';

    function safeParse(raw) {
        if (!raw) return null;
        try {
            return JSON.parse(raw);
        } catch (err) {
            return null;
        }
    }

    function normalizeCart(raw) {
        if (!raw) return {};
        if (Array.isArray(raw)) {
            const obj = {};
            raw.forEach((item, idx) => {
                if (!item) return;
                const key = String(item.id ?? item.slug ?? item.name ?? idx);
                const qty = Number(item.qty ?? item.quantity ?? 0);
                if (qty > 0) {
                    obj[key] = {
                        id: key,
                        name: item.name || item.title || 'Menu Kusantara',
                        price: Number(item.price ?? item.harga ?? 0),
                        qty: qty
                    };
                }
            });
            return obj;
        }
        if (typeof raw === 'object') {
            const clean = {};
            Object.keys(raw).forEach((key) => {
                const it = raw[key];
                if (!it) return;
                const qty = Number(it.qty ?? it.quantity ?? 0);
                if (qty > 0) {
                    clean[String(key)] = {
                        id: String(it.id ?? key),
                        name: it.name || it.title || 'Menu Kusantara',
                        price: Number(it.price ?? it.harga ?? 0),
                        qty: qty
                    };
                }
            });
            return clean;
        }
        return {};
    }

    function readCart() {
        return normalizeCart(safeParse(localStorage.getItem(CART_KEY)));
    }

    function writeCart(nextCart) {
        const clean = normalizeCart(nextCart);
        localStorage.setItem(CART_KEY, JSON.stringify(clean));
        syncCartUI();
        window.dispatchEvent(new CustomEvent('kusantara:cart-updated', { detail: clean }));
    }

    function setItemQty(id, name, price, qty) {
        const cart = readCart();
        const key = String(id);
        const nQty = Math.max(0, Number(qty || 0));
        if (nQty <= 0) {
            delete cart[key];
        } else {
            const prev = cart[key] || {};
            cart[key] = {
                id: key,
                name: name || prev.name || 'Menu Kusantara',
                price: Number(price ?? prev.price ?? 0),
                qty: nQty
            };
        }
        writeCart(cart);
    }

    function addItem(id, name, price, delta = 1) {
        const cart = readCart();
        const key = String(id);
        const currentQty = Number(cart[key]?.qty || 0);
        setItemQty(key, name, price, currentQty + Number(delta || 1));
    }

    function changeItemQty(id, delta) {
        const cart = readCart();
        const key = String(id);
        if (!cart[key]) return;
        setItemQty(key, cart[key].name, cart[key].price, Number(cart[key].qty || 0) + delta);
    }

    function deleteCartItem(id) {
        const cart = readCart();
        const key = String(id);
        if (!cart[key]) return;
        delete cart[key];
        writeCart(cart);
    }

    function toRupiah(val) {
        return 'Rp' + Math.round(Number(val || 0)).toLocaleString('id-ID');
    }

    function openCartDrawer() {
        syncCartUI();
        const backdrop = document.getElementById('kusantaraCartBackdrop');
        const drawer = document.getElementById('kusantaraCartDrawer');
        if (backdrop) backdrop.classList.add('is-open');
        if (drawer) drawer.classList.add('is-open');
    }

    function closeCartDrawer() {
        const backdrop = document.getElementById('kusantaraCartBackdrop');
        const drawer = document.getElementById('kusantaraCartDrawer');
        if (backdrop) backdrop.classList.remove('is-open');
        if (drawer) drawer.classList.remove('is-open');
    }

    function syncCartUI() {
        const cart = readCart();
        const badge = document.getElementById('navCartBadge');
        const body = document.getElementById('kcartBody');
        const subtotalEl = document.getElementById('kcartSubtotalText');
        const checkoutBtn = document.getElementById('kcartCheckoutBtn');

        let totalQty = 0;
        let subtotal = 0;
        const list = [];

        Object.keys(cart).forEach((key) => {
            const item = cart[key];
            const qty = Number(item.qty || 0);
            if (qty > 0) {
                const price = Number(item.price || 0);
                totalQty += qty;
                subtotal += qty * price;
                list.push({
                    id: key,
                    name: item.name,
                    price: price,
                    qty: qty
                });
            }
        });

        // Badge angka di icon keranjang: muncul hanya jika totalQty > 0
        if (badge) {
            if (totalQty > 0) {
                badge.textContent = String(totalQty);
                badge.style.display = 'inline-block';
                badge.classList.add('is-visible');
            } else {
                badge.textContent = '';
                badge.style.display = 'none';
                badge.classList.remove('is-visible');
            }
        }

        if (subtotalEl) {
            subtotalEl.textContent = toRupiah(subtotal);
        }

        if (checkoutBtn) {
            checkoutBtn.classList.toggle('is-disabled', totalQty === 0);
        }

        if (!body) return;

        if (list.length === 0) {
            body.innerHTML =
                '<div class="kcart-empty">' +
                    '<i class="fa-solid fa-basket-shopping"></i>' +
                    '<p>Keranjang masih kosong. Tambahkan menu terlebih dahulu.</p>' +
                    '<a href="/katalog" class="checkout-back-btn">Lihat Katalog Menu</a>' +
                '</div>';
            return;
        }

        body.innerHTML = list.map((it) => {
            return (
                '<div class="kcart-item" data-id="' + it.id + '">' +
                    '<div class="kcart-item-top">' +
                        '<div>' +
                            '<div class="kcart-item-name">' + it.name + '</div>' +
                            '<div class="kcart-item-unit">' + toRupiah(it.price) + ' / porsi</div>' +
                        '</div>' +
                        '<button type="button" class="kcart-remove-btn" data-cart-action="remove" data-id="' + it.id + '" title="Hapus menu">' +
                            '<i class="fa-solid fa-trash"></i>' +
                        '</button>' +
                    '</div>' +
                    '<div class="kcart-item-bottom">' +
                        '<div class="kcart-qty-control">' +
                            '<button type="button" class="kcart-qty-btn" data-cart-action="dec" data-id="' + it.id + '">−</button>' +
                            '<span class="kcart-qty-val">' + it.qty + '</span>' +
                            '<button type="button" class="kcart-qty-btn" data-cart-action="inc" data-id="' + it.id + '">+</button>' +
                        '</div>' +
                        '<div class="kcart-item-subtotal">' + toRupiah(it.price * it.qty) + '</div>' +
                    '</div>' +
                '</div>'
            );
        }).join('');
    }

    document.addEventListener('click', (e) => {
        const triggerBtn = e.target.closest('#openCartDrawerBtn, .nav-cart-trigger');
        if (triggerBtn) {
            e.preventDefault();
            openCartDrawer();
            return;
        }

        if (e.target.closest('#closeCartDrawerBtn') || e.target.id === 'kusantaraCartBackdrop') {
            closeCartDrawer();
            return;
        }

        const actionBtn = e.target.closest('[data-cart-action]');
        if (actionBtn) {
            const act = actionBtn.getAttribute('data-cart-action');
            const id = actionBtn.getAttribute('data-id');
            if (act === 'inc') changeItemQty(id, 1);
            if (act === 'dec') changeItemQty(id, -1);
            if (act === 'remove') deleteCartItem(id);
        }
    });

    window.addEventListener('storage', (e) => {
        if (!e.key || e.key === CART_KEY) syncCartUI();
    });
    window.addEventListener('kusantara:cart-updated', syncCartUI);

    window.KusantaraCart = {
        getCart: readCart,
        setItemQty: setItemQty,
        addItem: addItem,
        removeItem: deleteCartItem,
        open: openCartDrawer,
        close: closeCartDrawer,
        sync: syncCartUI
    };

    syncCartUI();
});