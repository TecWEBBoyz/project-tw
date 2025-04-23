<?php
require_once("phplibs/authManager.php");
require_once("phplibs/templatingManager.php");
require_once("phplibs/DBManager.php");


$currentFile = basename(__FILE__);
$htmlContent = Templating::renderHtml($currentFile);

// Check authentication before allowing access
AuthManager::requireUserAuth();

echo $htmlContent;
?>