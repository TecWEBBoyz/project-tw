<?php
// Define menu items
$menuItems = [
    ['label' => 'Home', 'link' => '', 'ariaLabel' => 'Home page link'],
    ['label' => 'About', 'link' => 'about', 'ariaLabel' => 'About us page link'],
    ['label' => 'Services', 'link' => 'services', 'ariaLabel' => 'Services page link'],
    ['label' => 'Contact', 'link' => 'contact', 'ariaLabel' => 'Contact page link'],
];

function renderMenu($menuItems) {
    foreach ($menuItems as $item) {
        echo '<a href="' . htmlspecialchars($item['link']) . '" aria-label="' . htmlspecialchars($item['ariaLabel']) . '">' . htmlspecialchars($item['label']) . '</a>';
    }
}
?>

<div class="navbar" role="navigation" aria-label="Main navigation">
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
</div>

