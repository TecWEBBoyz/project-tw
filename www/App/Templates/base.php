<?php
if (!isset($TEMPLATE_DATA)) {
    echo "No template data found!";
    die();
}
if(!isset($TEMPLATE_DATA['name'])) {
    echo "No template name found!";
    die();
}
if(!isset($TEMPLATE_DATA['templateFileName'])) {
    echo "No template file name found!";
    die();
}

if(!file_exists(__DIR__ . "/../Templates/$TEMPLATE_DATA[templateFileName]")) {
    throw new Exception("Template not found!");
}
isset($TEMPLATE_DATA) ?: $TEMPLATE_DATA = [];
$TEMPLATE_DATA['title'] = $TEMPLATE_DATA['title'] ?? "No Title";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $TEMPLATE_DATA['description'] ?? 'Default description for the page'; ?>">
    <meta name="keywords" content="<?php echo $TEMPLATE_DATA['keywords'] ?? 'default, keywords'; ?>">
    <meta name="author" content="TecWebBoyz">
    <title><?php echo $TEMPLATE_DATA['title']; ?></title>
    <link rel="stylesheet" href="static/css/main.css">
    <link rel="stylesheet" href="static/css/<?php echo $TEMPLATE_DATA['name']; ?>.css">
</head>
<body>
<div class="fullscreen-logo">
    <div class="logo-image"></div>
</div>
<div class="navbar" role="navigation" aria-label="Main navigation">
    <div class="logo">
    </div>
    <div class="links">
        <a href="#" aria-label="Home page link">Home</a>
        <a href="#" aria-label="About us page link">About</a>
        <a href="#" aria-label="Services page link">Services</a>
        <a href="#" aria-label="Contact page link">Contact</a>
    </div>
    <div class="hamburger" role="button" aria-label="Toggle navigation menu" tabindex="0" onclick="toggleMenu()">
        <div></div>
        <div></div>
        <div></div>
    </div>
    <div class="menu hidden" role="menu" aria-hidden="true">
        <span class="close" onclick="toggleMenu()" role="button" tabindex="0" aria-label="Close navigation menu">&times;</span>
        <a href="#" aria-label="Home page link">Home</a>
        <a href="#" aria-label="About us page link">About</a>
        <a href="#" aria-label="Services page link">Services</a>
        <a href="#" aria-label="Contact page link">Contact</a>
    </div>
</div>
<script>
    function toggleMenu() {
        const menu = document.querySelector('.navbar .menu');
        const isHidden = menu.classList.toggle('hidden');
        menu.setAttribute('aria-hidden', isHidden);
    }
</script>

<div class="wrapper">
    <div class="content">
        <div class="container">
            <h1><?php echo $TEMPLATE_DATA['title']; ?></h1>
            <?php require __DIR__ . "/$TEMPLATE_DATA[name].php"; ?>
        </div>
    </div>
    <!-- Footer -->
    <footer class="footer" role="contentinfo">
        <p>© TecWebBoyz - All rights reserved</p>
        <p>Follow us on <a href="https://www.instagram.com/filippor_photography/" aria-label="Instagram profile">Instagram</a> |
            <a href="https://www.facebook.com/filippo.rizzato.716?locale=it_IT" aria-label="Facebook profile">Facebook</a></p>
    </footer>
</div>

<!-- Scripts -->
<script src="static/js/main.js"></script>
<script src="static/js/<?php echo $TEMPLATE_DATA['name']; ?>.js"></script>

<!-- Structured Data for SEO -->
<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebPage",
        "name": "<?php echo $TEMPLATE_DATA['title']; ?>",
    "description": "<?php echo $TEMPLATE_DATA['description'] ?? 'Default description for the page'; ?>",
    "url": "<?php echo $_SERVER['REQUEST_URI']; ?>",
    "author": {
        "@type": "Organization",
        "name": "TecWebBoyz"
    }
}
</script>
</body>
</html>
