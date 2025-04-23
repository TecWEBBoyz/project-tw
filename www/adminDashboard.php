<?php
require_once("phplibs/authManager.php");
require_once("phplibs/templatingManager.php");
require_once("phplibs/DBManager.php");


$currentFile = basename(__FILE__);
$htmlContent = Templating::renderHtml($currentFile);

// Check authentication before allowing access
if (!AuthManager::isAdminLoggedIn()) {
    header('Location: login.php?error=unauthorized');
    exit;
}

echo $htmlContent;
?>