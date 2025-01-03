<?php
    if (!isset($TEMPLATE_DATA)) {
        echo "No template data found!";
        die();
    }
    if(!isset($TEMPLATE_DATA['name'])) {
        echo "No template name found!";
        die();
    }
    if(file_exists(__DIR__ . "/$TEMPLATE_DATA[name].php")) {
        require __DIR__ . "/$TEMPLATE_DATA[name].php";
    } else {
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
    <title><?php echo $TEMPLATE_DATA['title']; ?></title>
    <link rel="stylesheet" href="static/css/main.css">
    <link rel="stylesheet" href="static/css/<?php echo $TEMPLATE_DATA['name']; ?>.css">
</head>
<body>
<div class="fullscreen-logo">
    <img src="static/images/logo.png" alt="Logo">
</div>
<div class="navbar">
    <div class="logo">
        <img src="static/images/logo-slim.png" alt="Logo">
    </div>
    <div class="links">
        <a href="#">Home</a>
        <a href="#">About</a>
        <a href="#">Services</a>
        <a href="#">Contact</a>
    </div>
    <div class="hamburger" onclick="toggleMenu()">
        <div></div>
        <div></div>
        <div></div>
    </div>
    <div class="menu hidden">
        <span class="close" onclick="toggleMenu()">&times;</span>
        <a href="#">Home</a>
        <a href="#">About</a>
        <a href="#">Services</a>
        <a href="#">Contact</a>
    </div>
</div>
<script>
    function toggleMenu() {
        const menu = document.querySelector('.navbar .menu');
        menu.classList.toggle('hidden');
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
    <footer class="footer">
        <p>© TecWebBoyz - Tutti i diritti riservati</p>
        <p>Segui su <a href="https://www.instagram.com/filippor_photography/">Instagram</a> | <a href="https://www.facebook.com/filippo.rizzato.716?locale=it_IT">Facebook</a></p>
    </footer>
</div>

<!-- Scripts -->
<script src="static/js/main.js"></script>
<script src="static/js/<?php echo $TEMPLATE_DATA['name']; ?>.js"></script>

</body>
</html>