<?php

use PTW\Modules\Auth\Role;
use PTW\Modules\Auth\SessionManager;

$menuItems = [
    ['label' => \PTW\translation('nav-navigation-helper-content'), 'link' => $_SERVER['REQUEST_URI'] . '#content', 'classes' => '', 'navigation-help' => true],
    ['label' => \PTW\translation('nav-navigation-helper-footer'), 'link' => $_SERVER['REQUEST_URI'] . '#footer', 'classes' => '', 'navigation-help' => true],
    ['label' => \PTW\translation('nav-menu-home'), 'link' => 'home', 'classes' => ''],
    ['label' => \PTW\translation('nav-menu-services'), 'link' => 'services', 'classes' => ''],
    ['label' => \PTW\translation('nav-menu-about'), 'link' => 'about', 'classes' => ''],
];

$sessionManager = new SessionManager();
if ($sessionManager->isAuthenticated()) {
    if($sessionManager->authorize(Role::Administrator)) {
        $menuItems[] = ['label' => \PTW\translation('nav-menu-admin'), 'link' => 'admin', 'classes' => ''];
    } else {
        $menuItems[] = ['label' => \PTW\translation('nav-menu-profile'), 'link' => 'profile', 'classes' => ''];
        $menuItems[] = ['label' => \PTW\translation('nav-menu-book'), 'link' => 'book-service', 'classes' => ''];
    }
    $menuItems[] = ['label' => \PTW\translation('nav-menu-logout'), 'link' => 'logout', 'classes' => ''];
} else {
    $menuItems[] = ['label' => \PTW\translation('nav-menu-login'), 'link' => 'login', 'classes' => ''];
//    $menuItems[] = ['label' => 'Register', 'link' => 'register'];
}

function renderMenu($menuItems, $mobile = false) {

    $navId = $mobile ? 'menu_mobile' : 'menu_desktop';

    echo '<nav id="' . $navId . '"><ul><span '.(\PTW\getLangAttribute()).'>';
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
        $link = $item['link'];
        $parsedLink = parse_url($link);
        
        // If a fragment exists, use only the fragment part; otherwise, keep the original link
        $href = isset($parsedLink['fragment']) ? '#' . $parsedLink['fragment'] : $link;
        
        echo '<li' . $navigationHelper . '><a id="' . $id . '" class="nav-link link ' . htmlspecialchars($item['classes']) . '"' .
            ($mobile ? ' data-mobile="true" ' : ' ') .
            'href="' . htmlspecialchars($href) . '" '.(\PTW\getLangAttribute()).'>' .
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

    <button class="hamburger" aria-label="<?php echo PTW\translation("nav-menu-mobile-open"); ?>" tabindex="0" onclick="toggleMenu()">
        <div></div>
        <div></div>
        <div></div>
    </button>

    <div class="menu hidden" aria-hidden="true">
        <!-- Close button -->
        <a href="close" class="close" onclick="hideMenu()" role="button" tabindex="0" aria-label="<?php echo PTW\translation("nav-menu-mobile-close"); ?>" data-fake="true" id="mobile-first-item">&times;</a>
        <?php renderMenu($menuItems, true); ?>
    </div>
</header>


