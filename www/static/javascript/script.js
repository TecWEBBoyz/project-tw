// Open and close menu when clicking the hamburger-button button
document.addEventListener('DOMContentLoaded', function() {
    const menuBtn = document.getElementById('hamburger-button');

    menuBtn.addEventListener('click', () => onClickMenuBtn(menuBtn));
});

function onClickMenuBtn(button) {
    const menu = document.getElementById('menu');
    // const breadcrumb = document.getElementById('breadcrumb');

    if (menu.classList.contains('show-drop-content')) {
        menu.classList.remove('show-drop-content');

        // breadcrumb.classList.remove('show-drop-content');
        button.classList.remove("close-btn");
        button.setAttribute('aria-label', 'Apri menù');
    } else {
        menu.classList.add('show-drop-content');

        // breadcrumb.classList.add('show-drop-content');
        button.classList.add("close-btn");
        button.setAttribute('aria-label', 'Chiudi menù');
    }
}