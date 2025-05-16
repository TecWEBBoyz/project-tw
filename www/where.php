<?php
require_once 'vendor/autoload.php';

use PTW\Services\TemplateService;

$currentFile = basename(__FILE__);
$indexHtmlContent = TemplateService::renderHtml($currentFile);
echo $indexHtmlContent;

?>