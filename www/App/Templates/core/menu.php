<?php

use PTW\Modules\Auth\Role;
use PTW\Modules\Auth\SessionManager;

$menuItems = [
    ['label' => 'Vai al contenuto', 'link' => '#content', 'ariaLabel' => 'Collegamento al contenuto', 'classes' => 'navigation-help'],
    ['label' => 'Vai al footer', 'link' => '#footer', 'ariaLabel' => 'Collegamento al piè di pagina', 'classes' => 'navigation-help'],
    ['label' => 'Home', 'link' => 'home', 'ariaLabel' => 'Collegamento alla pagina Home', 'classes' => ''],
    ['label' => 'About', 'link' => 'about', 'ariaLabel' => 'Collegamento alla pagina About', 'classes' => ''],
    ['label' => 'Services', 'link' => 'services', 'ariaLabel' => 'Collegamento alla pagina Services', 'classes' => ''],
    ['label' => 'Contact', 'link' => 'contact', 'ariaLabel' => 'Collegamento alla pagina Contact', 'classes' => ''],
];

$sessionManager = new SessionManager();
if ($sessionManager->isAuthenticated()) {
    if ($sessionManager->authorize(Role::User)) {
        $menuItems[] = ['label' => 'Dashboard', 'link' => 'dashboard', 'ariaLabel' => ' Collegamento alla pagina Dashboard', 'classes' => ''];
    }
    if($sessionManager->authorize(Role::Administrator)) {
        $menuItems[] = ['label' => 'Admin', 'link' => 'admin', 'ariaLabel' => 'Collegamento alla pagina Admin', 'classes' => ''];
    } else {
        $menuItems[] = ['label' => 'Profile', 'link' => 'profile', 'ariaLabel' => 'Collegamento alla pagina Profile', 'classes' => ''];
        $menuItems[] = ['label' => 'Book Service', 'link' => 'book-service', 'ariaLabel' => 'Book service page link', 'classes' => ''];
    }
    $menuItems[] = ['label' => 'Logout', 'link' => 'logout', 'ariaLabel' => 'Effetua Logout', 'classes' => ''];
} else {
    $menuItems[] = ['label' => 'Login', 'link' => 'login', 'ariaLabel' => 'Collegamento alla pagina Login', 'classes' => ''];
//    $menuItems[] = ['label' => 'Register', 'link' => 'register', 'ariaLabel' => 'Register page link'];
}
function renderMenu($menuItems, $mobile = false) {
    echo '<nav id="menu"><ul><span lang="eng">';
    foreach ($menuItems as $item) {
        echo '<li><a class="nav-link ' . htmlspecialchars($item['classes']) . '"'.($mobile ? ' data-mobile="true" ' : ' ' ).'href="' . htmlspecialchars($item['link']) . '" aria-label="' . htmlspecialchars($item['ariaLabel']) . '">' . htmlspecialchars($item['label']) . '</a></li>' . PHP_EOL;
    }
    echo '</span></ul></nav>';
}
?>

<header class="navbar">
    <h1 class="logo-hide">Filippo Rizzato</h1>
    <div class="links">
        <?php renderMenu($menuItems); ?>
    </div>
    <button class="hamburger" aria-label="Apertura menu di navigazione" tabindex="0" onclick="toggleMenu()">
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


