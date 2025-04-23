<?php
require_once("phplibs/templatingManager.php");
require_once("phplibs/DBManager.php");


$currentFile = basename(__FILE__);
$indexHtmlContent = Templating::renderHtml($currentFile);
echo $indexHtmlContent;


?>