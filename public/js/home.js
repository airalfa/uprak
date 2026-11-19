
// MOBILE MENU
document.addEventListener('DOMContentLoaded', function () {
    const menuButton = document.querySelector('.mobile-menu');
    const menu = document.querySelector('.main-nav');

    if (menuButton && menu) {
        menuButton.addEventListener('click', function () {
            const isOpen = menu.classList.toggle('active');

            menuButton.setAttribute('aria-expanded', isOpen);
            menuButton.innerHTML = isOpen
                ? '<i class="bi bi-x-lg"></i>'
                : '<i class="bi bi-list"></i>';
        });
    }
});

// CART
let cartCount = 0;

function updateCart() {
    const smallCount = document.getElementById('cartCount');
    const largeCount = document.getElementById('cartCountLarge');

    if (smallCount) smallCount.textContent = cartCount;
    if (largeCount) largeCount.textContent = cartCount;
}

// TOAST
function showCartToast(message) {
    const toast = document.getElementById('cartToast');

    if (!toast) return;

    toast.innerHTML =
        '<i class="bi bi-cart-check"></i> ' + message;

    toast.classList.add('show');

    clearTimeout(window.toastTimer);

    window.toastTimer = setTimeout(function () {
        toast.classList.remove('show');
    }, 2500);
}

// OPEN CART
function openCart() {
    showCartToast('Keranjang belanja dibuka. Total: ' + cartCount + ' item.');
}

// SEARCH
document.addEventListener('DOMContentLoaded', function () {
    const searchForm = document.getElementById('globalSearch');

    if (searchForm) {
        searchForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const input = document.getElementById('searchInput');
            const keyword = input.value.trim();

            if (!keyword) {
                showCartToast('Masukkan kata pencarian terlebih dahulu.');
                return;
            }

            showCartToast('Pencarian: ' + keyword);
        });
    }
});

// ADD TO CART
function addToCart() {
    cartCount++;
    updateCart();
    showCartToast('Produk berhasil ditambahkan!');
}
