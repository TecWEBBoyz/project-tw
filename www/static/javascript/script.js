// Open and close menu when clicking the hamburger-button button
function toggleHamburgerMenu() {
    const menu = document.getElementById('menu');
    const breadcrumb = document.getElementById('breadcrumb');
    const button = document.getElementById('hamburger-button');
    if (menu.classList.contains('show-drop-content') || breadcrumb.classList.contains('show-drop-content')) {
        menu.classList.remove('show-drop-content');
        breadcrumb.classList.remove('show-drop-content');
        button.setAttribute('aria-label', 'Apri menù');
    } else {
        menu.classList.add('show-drop-content');
        breadcrumb.classList.add('show-drop-content');
        button.setAttribute('aria-label', 'Chiudi menù');
    }
    menuAnimate();
}

// Close the menu when clicking outside of it
window.onclick = function(event) {
    const menu = document.getElementById('menu');
    const breadcrumb = document.getElementById('breadcrumb');
    const button = document.getElementById('hamburger-button');
    if (!event.target.matches('#hamburger-button') && !menu.contains(event.target)) {
        menu.classList.remove('show-drop-content');
        breadcrumb.classList.remove('show-drop-content');
        button.setAttribute('aria-label', 'Apri menù');
    }
}
