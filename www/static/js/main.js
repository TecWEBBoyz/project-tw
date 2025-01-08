function toggleMenu() {
    const menu = document.querySelector('.navbar .menu');
    const hamburger = document.querySelector('.navbar .hamburger');
    const isHidden = menu.classList.toggle('hidden');
    hamburger.classList.toggle('active');
    menu.setAttribute('aria-hidden', isHidden);
}
