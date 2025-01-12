<?php

use PTW\Modules\Auth\Role;
use PTW\Modules\Auth\SessionManager;

$menuItems = [
    //['label' => 'Vai al contenuto', 'link' => '#content', 'ariaLabel' => 'Collegamento al contenuto'],
    ['label' => 'Home', 'link' => 'home', 'ariaLabel' => 'Collegamento alla pagina Home'],
    ['label' => 'About', 'link' => 'about', 'ariaLabel' => 'Collegamento alla pagina About'],
    ['label' => 'Services', 'link' => 'services', 'ariaLabel' => 'Collegamento alla pagina Services'],
    ['label' => 'Contact', 'link' => 'contact', 'ariaLabel' => 'Collegamento alla pagina Contact'],
    //['label' => 'Vai al footer', 'link' => '#footer', 'ariaLabel' => 'Collegamento al piè di pagina'],
];

$sessionManager = new SessionManager();
if ($sessionManager->isAuthenticated()) {
    if ($sessionManager->authorize(Role::user)) {
        $menuItems[] = ['label' => 'Dashboard', 'link' => 'dashboard', 'ariaLabel' => ' Collegamento alla pagina Dashboard'];
    }
    if($sessionManager->authorize(Role::admin)) {
        $menuItems[] = ['label' => 'Admin', 'link' => 'admin', 'ariaLabel' => 'Collegamento alla pagina Admin'];
    } else {
        $menuItems[] = ['label' => 'Profile', 'link' => 'profile', 'ariaLabel' => 'Collegamento alla pagina Profile'];
        $menuItems[] = ['label' => 'Book Service', 'link' => 'book-service', 'ariaLabel' => 'Book service page link'];
    }
    $menuItems[] = ['label' => 'Logout', 'link' => 'logout', 'ariaLabel' => 'Effetua Logout'];
} else {
    $menuItems[] = ['label' => 'Login', 'link' => 'login', 'ariaLabel' => 'Collegamento alla pagina Login'];
//    $menuItems[] = ['label' => 'Register', 'link' => 'register', 'ariaLabel' => 'Register page link'];
}
function renderMenu($menuItems, $mobile = false) {
    echo '<nav id="menu"><ul><span lang="eng">';
    foreach ($menuItems as $item) {
        echo '<li><a class="nav-link"'.($mobile ? ' data-mobile="true" ' : ' ' ).'href="' . htmlspecialchars($item['link']) . '" aria-label="' . htmlspecialchars($item['ariaLabel']) . '">' . htmlspecialchars($item['label']) . '</a></li>';
    }
    echo '</span></ul></nav>';
}
?>

<header class="navbar">
    <h1 class="logo-hide">Filippo Rizzato</h1>
    <div class="links">
        <?php renderMenu($menuItems); ?>
    </div>
    <button class="hamburger" role="button" aria-label="Apertura menu di navigazione" tabindex="0" onclick="toggleMenu()">
        <div></div>
        <div></div>
        <div></div>
    </button>
    <div class="menu hidden" role="menu" aria-hidden="true">
        <!-- Close button -->
        <a href="close" class="close" onclick="hideMenu()" role="button" tabindex="0" aria-label="Chiusura menu di navigazione" data-fake="true">&times;</a>
        <?php renderMenu($menuItems, true); ?>
    </div>
</header>


