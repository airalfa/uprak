document.addEventListener('DOMContentLoaded', () => {
    // Toggle Dropdown Login
    const loginBtn = document.getElementById('loginDropdownBtn');
    const loginDropdown = document.getElementById('loginDropdown');

    if (loginBtn && loginDropdown) {
        loginBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            loginDropdown.classList.toggle('show');
        });

        document.addEventListener('click', (e) => {
            if (!loginDropdown.contains(e.target) && e.target !== loginBtn) {
                loginDropdown.classList.remove('show');
            }
        });
    }

    // Toggle Pencarian Global
    const searchToggle = document.getElementById('searchToggle');
    const globalSearchBar = document.getElementById('globalSearchBar');
    const closeSearch = document.getElementById('closeSearch');

    if (searchToggle && globalSearchBar) {
        searchToggle.addEventListener('click', () => {
            globalSearchBar.classList.toggle('show');
            const input = globalSearchBar.querySelector('input');
            if (globalSearchBar.classList.contains('show') && input) {
                input.focus();
            }
        });
    }

    if (closeSearch && globalSearchBar) {
        closeSearch.addEventListener('click', () => {
            globalSearchBar.classList.remove('show');
        });
    }

    // Toggle Menu Mobile
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const navLinks = document.getElementById('navLinks');

    if (mobileMenuBtn && navLinks) {
        mobileMenuBtn.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });
    }
});