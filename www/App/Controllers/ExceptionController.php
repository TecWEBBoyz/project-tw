<?php

/**
 * About page controller
 */

namespace PTW\Controllers;

use PTW\Contracts\ControllerContract;
use PTW\Utility\TemplateUtility;

class ExceptionController extends ControllerContract
{
    public function get(string $error_code = "404", string $errorstring = ""): void
    {
        switch ($error_code) {
            case "404":
                TemplateUtility::getTemplate("404", [
                    "errorstring" => $errorstring,
                    "title" => \PTW\translation('title-404'),
                    "description" => \PTW\translation('description-404'),
                    "keywords" => \PTW\translation('keywords-404')
                ]);
                break;
            case "500":
                TemplateUtility::getTemplate("500", [
                    "errorstring" => $errorstring,
                    "title" => \PTW\translation('title-500'),
                    "description" => \PTW\translation('description-500'),
                    "keywords" => \PTW\translation('keywords-500')
                ]);
                break;
            default:
                TemplateUtility::getTemplate("500", [
                    "errorstring" => $errorstring,
                    "title" => \PTW\translation('title-500'),
                    "description" => \PTW\translation('description-500'),
                    "keywords" => \PTW\translation('keywords-500')
                ]);
                break;
        }
    }

    public function error_500(): void
    {
        TemplateUtility::getTemplate("500", [
            "errorstring" => "",
            "title" => \PTW\translation('title-500'),
            "description" => \PTW\translation('description-500'),
            "keywords" => \PTW\translation('keywords-500')
        ]);
    }

    public function put(): void
    {
    }

    public function delete(): void
    {
    }

    public function post(): void
    {
    }
}