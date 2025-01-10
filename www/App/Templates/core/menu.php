<?php

use PTW\Modules\Auth\Role;
use PTW\Modules\Auth\SessionManager;

$menuItems = [
    ['label' => 'Home', 'link' => 'home', 'ariaLabel' => 'Home page link'],
    ['label' => 'About', 'link' => 'about', 'ariaLabel' => 'About us page link'],
    ['label' => 'Services', 'link' => 'services', 'ariaLabel' => 'Services page link'],
    ['label' => 'Contact', 'link' => 'contact', 'ariaLabel' => 'Contact page link'],
];

$sessionManager = new SessionManager();
if ($sessionManager->isAuthenticated()) {
    if ($sessionManager->authorize(Role::User)) {
        $menuItems[] = ['label' => 'Dashboard', 'link' => 'dashboard', 'ariaLabel' => 'Dashboard page link'];
    }
    if($sessionManager->authorize(Role::Administrator)) {
        $menuItems[] = ['label' => 'Admin', 'link' => 'admin', 'ariaLabel' => 'Admin page link'];
    } else {
        $menuItems[] = ['label' => 'Profile', 'link' => 'profile', 'ariaLabel' => 'Profile page link'];
        $menuItems[] = ['label' => 'Book Service', 'link' => 'book-service', 'ariaLabel' => 'Book service page link'];
    }
    $menuItems[] = ['label' => 'Logout', 'link' => 'logout', 'ariaLabel' => 'Logout link'];
} else {
    $menuItems[] = ['label' => 'Login', 'link' => 'login', 'ariaLabel' => 'Login page link'];
//    $menuItems[] = ['label' => 'Register', 'link' => 'register', 'ariaLabel' => 'Register page link'];
}
function renderMenu($menuItems) {
    echo '<nav id="menu"><ul><span lang="eng">';
    foreach ($menuItems as $item) {
        echo '<li><a class="nav-link" href="' . htmlspecialchars($item['link']) . '" aria-label="' . htmlspecialchars($item['ariaLabel']) . '">' . htmlspecialchars($item['label']) . '</a></li>';
    }
    echo '</span></ul></nav>';
}
?>

<header class="navbar">
    <div class="logo">
        <!-- Add your logo here -->
    </div>
    <div class="links">
        <?php renderMenu($menuItems); ?>
    </div>
    <div class="hamburger" role="button" aria-label="Toggle navigation menu" tabindex="0" onclick="toggleMenu()">
        <div></div>
        <div></div>
        <div></div>
    </div>
    <div class="menu hidden" role="menu" aria-hidden="true">
        <!-- Close button -->
        <a href="#" class="close" onclick="toggleMenu()" role="button" tabindex="0" aria-label="Close navigation menu">&times;</a>
        <?php renderMenu($menuItems); ?>
    </div>
</header>

