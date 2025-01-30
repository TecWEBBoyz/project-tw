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
});
