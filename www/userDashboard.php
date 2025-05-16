<?php
require_once 'init.php';

use PTW\Services\AuthService;
use PTW\Services\TemplateService;

// Check authentication before allowing access
if (!AuthService::isUserLoggedIn()) {
    header('Location: login.php?error=unauthorized');
    exit;
}

$currentFile = basename(__FILE__);
$htmlContent = TemplateService::renderHtml($currentFile);

echo $htmlContent;
?>