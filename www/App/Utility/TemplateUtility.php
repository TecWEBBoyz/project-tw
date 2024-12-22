<?php
namespace PTW\Utility;
class TemplateUtility
{
    public static function getTemplate($template, $data = []): void
    {
        $TEMPLATE_DATA = $data;
        $TEMPLATE_DATA['name'] = $template;
        $base = require __DIR__ . "/../Templates/base.php";
        echo $base;
    }
}