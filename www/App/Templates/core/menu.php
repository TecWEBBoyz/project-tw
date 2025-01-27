<?php

use PTW\Modules\Auth\Role;
use PTW\Modules\Auth\SessionManager;

$menuItems = [
    ['label' => 'Vai al contenuto', 'link' => $_SERVER['REQUEST_URI'] . '#content', 'classes' => '', 'navigation-help' => true],
    ['label' => 'Vai al footer', 'link' => $_SERVER['REQUEST_URI'] . '#footer', 'classes' => '', 'navigation-help' => true],
    ['label' => 'About', 'link' => 'about', 'classes' => ''],
    ['label' => 'Services', 'link' => 'services', 'classes' => ''],
    ['label' => 'Contact', 'link' => 'contact', 'classes' => ''],
];

$sessionManager = new SessionManager();
if ($sessionManager->isAuthenticated()) {
    if ($sessionManager->authorize(Role::User)) {
        $menuItems[] = ['label' => 'Dashboard', 'link' => 'dashboard', 'classes' => ''];
    }
    if($sessionManager->authorize(Role::Administrator)) {
        $menuItems[] = ['label' => 'Admin', 'link' => 'admin', 'classes' => ''];
    } else {
        $menuItems[] = ['label' => 'Profile', 'link' => 'profile', 'classes' => ''];
        $menuItems[] = ['label' => 'Book Service', 'link' => 'book-service', 'classes' => ''];
    }
    $menuItems[] = ['label' => 'Logout', 'link' => 'logout', 'classes' => ''];
} else {
    $menuItems[] = ['label' => 'Login', 'link' => 'login', 'classes' => ''];
//    $menuItems[] = ['label' => 'Register', 'link' => 'register'];
}

function renderMenu($menuItems, $mobile = false) {

    $navId = $mobile ? 'menu_mobile' : 'menu_desktop';

    echo '<nav id="' . $navId . '"><ul><span lang="en">';
    $firstId = $mobile ? '' : 'desktop-first-item';
    $lastId = $mobile ? 'mobile-last-item' : 'desktop-last-item';
    $itemCount = count($menuItems);

    foreach ($menuItems as $index => $item) {
        $id = '';
        $navigationHelper =
            isset($item['navigation-help']) && $item['navigation-help']
                ? 'class="navigation-help"' : '';
        if ($index === 0) {
            $id = $firstId;
        } elseif ($index === $itemCount - 1) {
            $id = $lastId;
        }
        echo '<li ' . $navigationHelper . '><a id="' . $id . '" class="nav-link link ' . htmlspecialchars($item['classes']) . '"' .
            ($mobile ? ' data-mobile="true" ' : ' ') .
            'href="' . htmlspecialchars($item['link']) . '">' .
            htmlspecialchars($item['label']) . '</a></li>' . PHP_EOL;
    }
    echo '</span></ul></nav>';
}

?>

<header class="navbar">
    <a href="home" class="logo">
        <h1 class="logo-hide">Filippo Rizzato</h1>
    </a>

    <?php renderMenu($menuItems); ?>

    <button class="hamburger" aria-label="Apertura menu di navigazione" tabindex="0" onclick="toggleMenu()">
        <div></div>
        <div></div>
        <div></div>
    </button>

    <div class="menu hidden" role="menu" aria-hidden="true">
        <!-- Close button -->
        <a href="close" class="close" onclick="hideMenu()" role="button" tabindex="0" aria-label="Chiusura menu di navigazione" data-fake="true" id="mobile-first-item">&times;</a>
        <?php renderMenu($menuItems, true); ?>
    </div>
</header>


