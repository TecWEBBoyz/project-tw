document.addEventListener('DOMContentLoaded', () => {
    // Seleziona gli elementi del menu mobile
    const firstMobileItem = document.getElementById('mobile-first-item');
    const lastMobileItem = document.getElementById('mobile-last-item');

    if (firstMobileItem && lastMobileItem) {
        // Quando il focus raggiunge l'ultimo elemento, torna al primo
        lastMobileItem.addEventListener('keydown', function (e) {
            if (e.key === 'Tab' && !e.shiftKey) {
                e.preventDefault();
                firstMobileItem.focus();
            }
        });

        // Quando il focus raggiunge il primo elemento con Shift + Tab, torna all'ultimo
        firstMobileItem.addEventListener('keydown', function (e) {
            if (e.key === 'Tab' && e.shiftKey) {
                e.preventDefault();
                lastMobileItem.focus();
            }
        });
    }
    function toggleMenu(event) {
        event.preventDefault();
        const menu = document.querySelector('.navbar .menu');
        const hamburger = document.querySelector('.navbar .hamburger');
        const isHidden = menu.classList.toggle('hidden');
        hamburger.classList.toggle('active');
        menu.setAttribute('aria-hidden', isHidden);
    }

    window.toggleMenu = toggleMenu;

    function hideMenu(event) {
        event.preventDefault();
        const menu = document.querySelector('.navbar .menu');
        const hamburger = document.querySelector('.navbar .hamburger');
        menu.classList.add('hidden');
        hamburger.classList.remove('active');
        menu.setAttribute('aria-hidden', true);
    }

    window.hideMenu = hideMenu;
});
