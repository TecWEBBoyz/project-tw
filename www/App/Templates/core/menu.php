<?php

use PTW\Modules\Auth\Role;
use PTW\Modules\Auth\SessionManager;

$menuItems = [
    ['label_translation' => 'nav-navigation-helper-content', 'link' => $_SERVER['REQUEST_URI'].'#content', 'classes' => '', 'navigation-help' => true],
    ['label_translation' => 'nav-navigation-helper-footer', 'link' => $_SERVER['REQUEST_URI'].'#footer', 'classes' => '', 'navigation-help' => true],
    ['label_translation' => 'nav-menu-home', 'link' => 'home', 'classes' => ''],
    ['label_translation' => 'nav-menu-services', 'link' => 'services', 'classes' => ''],
    ['label_translation' => 'nav-menu-about', 'link' => 'about', 'classes' => ''],
];

$sessionManager = new SessionManager();
if ($sessionManager->isAuthenticated()) {
    if($sessionManager->authorize(Role::Administrator)) {
        $menuItems[] = ['label_translation' => 'nav-menu-admin', 'link' => 'admin', 'classes' => ''];
    } else {
        $menuItems[] = ['label_translation' => 'nav-menu-profile', 'link' => 'profile', 'classes' => ''];
        $menuItems[] = ['label_translation' => 'nav-menu-book', 'link' => 'book-service', 'classes' => ''];
    }
    $menuItems[] = ['label_translation' => 'nav-menu-logout', 'link' => 'logout', 'classes' => ''];
} else {
    $menuItems[] = ['label_translation' =>'nav-menu-login', 'link' => 'login', 'classes' => ''];
//    $menuItems[] = ['label_translation' => 'Register', 'link' => 'register'];
}

function renderMenu($menuItems, $mobile = false) {

    $navId = $mobile ? 'menu_mobile' : 'menu_desktop';

    echo '<nav aria-label="' . "Navigazione Principale" . '" id="' . $navId . '"><ul>';
    $firstId = $mobile ? '' : 'desktop-first-item';
    $lastId = $mobile ? 'mobile-last-item' : 'desktop-last-item';
    $itemCount = count($menuItems);

    foreach ($menuItems as $index => $item) {
        $id = '';
        $navigationHelper =
            isset($item['navigation-help']) && $item['navigation-help']
                ? ' class="navigation-help"' : '';
        if ($index === 0) {
            $id = $firstId;
        } elseif ($index === $itemCount - 1) {
            $id = $lastId;
        }
        $href = $item['link'];

        echo '<li' . $navigationHelper . '><a ' . 'class="nav-link link ' . htmlspecialchars($item['classes']) . '"' .
            ($mobile ? ' data-mobile="true" ' : ' ') .
            'href="' . $href . '" '.\PTW\getOriginalLanguageAttribute($item['label_translation']).'>' .
            htmlspecialchars( \PTW\translation($item['label_translation'])) . '</a></li>' . PHP_EOL;
    }
    echo '</ul></nav>';
}

?>

<header class="navbar">
    <a href="home" class="logo">
        <h1 class="logo-hide">Filippo Rizzato</h1>
    </a>

    <?php renderMenu($menuItems); ?>

    <button class="hamburger" aria-label="<?php echo PTW\translation("nav-menu-mobile-open"); ?>" tabindex="0" onclick="toggleMenu(event)">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <div class="menu hidden" aria-hidden="true">
        <!-- Close button -->
        <a href="" class="close" onclick="hideMenu(event)" role="button" tabindex="0" aria-label="<?php echo PTW\translation("nav-menu-mobile-close"); ?>" data-fake="true" id="mobile-first-item">&times;</a>
        <?php renderMenu($menuItems, true); ?>
    </div>
</header>


