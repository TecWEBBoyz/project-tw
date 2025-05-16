<?php
require_once 'init.php';

use PTW\Services\TemplateService;

$currentFile = basename(__FILE__);
$htmlContent = TemplateService::renderHtml($currentFile);
echo $htmlContent;

?>