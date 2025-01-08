<!-- Meta tags -->
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="<?php use function PTW\config;

echo $TEMPLATE_DATA['description'] ?? 'Default description for the page'; ?>">
<meta name="keywords" content="<?php echo $TEMPLATE_DATA['keywords'] ?? 'default, keywords'; ?>">
<meta name="author" content="TecWebBoyz">
<!-- Favicon -->
<link rel="icon" href="static/images/favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="static/images/favicon.ico" type="image/x-icon">
<!-- Page title -->
<title><?php echo $TEMPLATE_DATA['title']; ?></title>
<!-- Styles -->
<link rel="stylesheet" href="static/css/main.css">
<link rel="stylesheet" href="static/css/<?php echo $TEMPLATE_DATA['name']; ?>.css">
<!-- Fonts -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap">
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
<base href="<?php echo config('router.baseURL'); ?>/">