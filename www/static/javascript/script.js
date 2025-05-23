// Open and close menu when clicking the hamburger-button button
document.addEventListener('DOMContentLoaded', function() {
    const menuBtn = document.getElementById('hamburger-button');
    const menu = document.getElementById('menu');

    // Existing click handler
    menuBtn.addEventListener('click', () => onClickMenuBtn(menuBtn));

    // Add focusout handler for the menu container
    menu.addEventListener('focusout', (event) => {
        // Check if the new focus target is outside menu and button
        const isClickInside = menu.contains(event.relatedTarget) || 
                            menuBtn.contains(event.relatedTarget);
        if (!isClickInside) {
            onClickMenuBtn(menuBtn)
        }
    });
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