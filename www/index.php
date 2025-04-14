<?php
require_once("phplibs/templating.php");
$currentFile = basename(__FILE__);

$indexHtmlContent = Templating::renderHtml($currentFile);
echo $indexHtmlContent;

?>