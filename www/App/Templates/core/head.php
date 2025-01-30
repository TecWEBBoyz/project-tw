<?php use function PTW\config; ?>
<!-- Meta tags -->
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<!-- Meta tag per WhatsApp (Open Graph) -->
<meta property="og:title" content="<?php echo \PTW\translation('meta-title'); ?>" />
<meta property="og:description" content="<?php echo \PTW\translation('meta-description'); ?>" />
<!--<meta property="og:image" content="URL dell'immagine di anteprima" />-->
<!--<meta property="og:url" content="URL della pagina" />-->

<meta name="description" content="<?php echo \PTW\translation('meta-description'); ?>">
<meta name="keywords" content="<?php echo \PTW\translation('meta-keywords'); ?>">
<meta name="author" content="<?php echo \PTW\translation('meta-author'); ?>">

<base href="<?php echo config('router.baseURL'); ?>/">

<!-- Favicon -->
<link rel="icon" href="static/images/favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="static/images/favicon.ico" type="image/x-icon">
<!-- Page title -->
<title><?php echo $TEMPLATE_DATA['title']; ?></title>
<!-- Styles -->
<!--<link rel="stylesheet" href="style?template=--><?php //echo $TEMPLATE_DATA['name']; ?><!--">-->
<link rel="stylesheet" href="style.css">
<!-- Scripts -->
<?php
$scrollTarget = PTW\Utility\ScrollToUtility::getScrollTarget();
if ($scrollTarget): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var element = document.getElementById("<?php echo htmlspecialchars($scrollTarget); ?>");
            if (element) {
                // Scorrimento fluido fino all'elemento
                element.scrollIntoView({  block: "center" });

                // Aggiunge la classe per evidenziare con effetto glow e scala
                element.classList.add("highlight-effect");

                // Rimuove l'effetto dopo 3 secondi
                setTimeout(function() {
                    element.classList.remove("highlight-effect");
                }, 3000);
            }
        });
    </script>
<?php endif; ?>
<script src="static/js/main.js"></script>
<script src="static/js/menu.js"></script>
<script data-template="<?php echo $TEMPLATE_DATA['name']; ?>" src="static/js/<?php echo $TEMPLATE_DATA['name']; ?>.js"></script>