<?php
require_once("phplibs/authMiddleware.php");
require_once("phplibs/templatingManager.php");
require_once("phplibs/DBManager.php");


$currentFile = basename(__FILE__);
$htmlContent = Templating::renderHtml($currentFile);

// Check authentication before allowing access
AuthMiddleware::requireAdminAuth();

echo $htmlContent;
?>