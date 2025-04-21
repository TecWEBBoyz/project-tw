function menuAnimate() {
    const bar1 = document.getElementById('bar1');
    const bar2 = document.getElementById('bar2');
    const bar3 = document.getElementById('bar3');
    if(bar1.classList.contains('change') || bar2.classList.contains('change') || bar3.classList.contains('change')) {
        bar1.classList.remove('change');
        bar2.classList.remove('change');
        bar3.classList.remove('change');
    } else {
        bar1.classList.add('change');
        bar2.classList.add('change');
        bar3.classList.add('change');
    }
}

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