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
        $base = require __DIR__ . "/../Templates/base.php";
        echo $base;
    }
}