<?php
namespace PTW\Utility;
use Exception;

class TemplateUtility
{
    public static function getTemplate($template, $data = []): void
    {
        $TEMPLATE_DATA = $data;
        $TEMPLATE_DATA['name'] = $template;
        $TEMPLATE_DATA['title'] = $data['title'] ?? "No Title";
        $TEMPLATE_DATA['templateFileName'] ="$template.php";
        $breadcrumb = BreadCrumbUtility::getBreadCrumbElement($template, $data);
        $headers = getallheaders();
        if(isset($headers['component'])) {
            header("templateName: $template");
            header("templateTitle: ".$TEMPLATE_DATA['title']);
            echo $breadcrumb;
            echo "<h1>".$TEMPLATE_DATA['title']."</h1>";
            $base = require __DIR__ . "/../Templates/$template.php";
        }
        else {
            $base = require __DIR__ . "/../Templates/core/base.php";
        }
    }

}