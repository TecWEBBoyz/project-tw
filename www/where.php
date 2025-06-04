<?php
require_once 'init.php';

use PTW\Services\TemplateService;

$currentFile = basename(__FILE__);
$indexHtmlContent = TemplateService::renderHtml($currentFile);
echo $indexHtmlContent;

?>