<?php
require_once 'vendor/autoload.php';

use PTW\Services\TemplateService;

$currentFile = basename(__FILE__);
$htmlContent = TemplateService::renderHtml($currentFile);
echo $htmlContent;

?>