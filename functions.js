document.addEventListener('DOMContentLoaded', function () {
    // Address Section Elements
    const toggleAddressBtn = document.getElementById('toggleAddressBtn');
    const closeAddressSectionBtn = document.getElementById('closeAddressSection');
    const addressSection = document.getElementById('addressSection');

    // Restore address section state from localStorage
    if (localStorage.getItem('addressSectionOpen') === 'true') {
        addressSection.style.display = 'block';
    }

    if (toggleAddressBtn && addressSection) {
        toggleAddressBtn.addEventListener('click', function () {
            const isVisible = addressSection.style.display === 'block';
            addressSection.style.display = isVisible ? 'none' : 'block';
            localStorage.setItem('addressSectionOpen', !isVisible);
        });
    }

    if (closeAddressSectionBtn) {
        closeAddressSectionBtn.addEventListener('click', function () {
            addressSection.style.display = 'none';
            localStorage.setItem('addressSectionOpen', false);
        });
    }

    // Login History Section Elements
    const toggleLoginBtn = document.getElementById('toggleLoginHistoryBtn');
    const closeLoginHistorySectionBtn = document.getElementById('closeLoginHistorySection');
    const loginHistorySection = document.getElementById('loginHistorySection');

    // Restore login history section state from localStorage
    if (localStorage.getItem('loginHistorySectionOpen') === 'true') {
        loginHistorySection.style.display = 'block';
    }

    if (toggleLoginBtn && loginHistorySection) {
        toggleLoginBtn.addEventListener('click', function () {
            const isVisible = loginHistorySection.style.display === 'block';
            loginHistorySection.style.display = isVisible ? 'none' : 'block';
            localStorage.setItem('loginHistorySectionOpen', !isVisible);
        });
    }

    if (closeLoginHistorySectionBtn) {
        closeLoginHistorySectionBtn.addEventListener('click', function () {
            loginHistorySection.style.display = 'none';
            localStorage.setItem('loginHistorySectionOpen', false);
        });
    }
});