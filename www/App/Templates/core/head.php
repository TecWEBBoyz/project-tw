<?php
    use function PTW\config;
    if (!isset($TEMPLATE_DATA)) {
        $TEMPLATE_DATA = [];
    }
    if(!isset($TEMPLATE_DATA['title'])) {
        throw new Exception('Title is required');
    }
    if(!isset($TEMPLATE_DATA['description'])) {
        throw new Exception('Description is required');
    }
    if(!isset($TEMPLATE_DATA['keywords'])) {
        throw new Exception('Keywords are required');
    }
?>
<!-- Meta tags -->
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Meta tag (Open Graph) -->
<meta property="og:title" content="<?php echo \PTW\translation('meta-title'); ?>" >
<meta property="og:description" content="<?php echo \PTW\translation('meta-description'); ?>" >
<meta property="og:image" content="<?php echo config('url'); ?>/static/images/logo-slim-black-bg.png" >
<meta property="og:url" content="<?php echo config('url'); ?>" />
<meta property="og:type" content="website" />
<meta property="og:locale" content="<?php echo \PTW\translation('meta-locale'); ?>" >
<meta property="og:site_name" content="<?php echo \PTW\translation('meta-site-name'); ?>" >

<meta name="description" content="<?php echo $TEMPLATE_DATA['description']; ?>">
<meta name="keywords" content="<?php echo $TEMPLATE_DATA['keywords']; ?>">
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
<script src="main.js" data-template="<?php echo $TEMPLATE_DATA['name']; ?>"></script>
