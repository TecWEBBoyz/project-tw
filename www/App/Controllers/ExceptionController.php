<?php

/**
 * About page controller
 */

namespace PTW\Controllers;

use PTW\Contracts\ControllerContract;
use PTW\Utility\TemplateUtility;

class ExceptionController implements ControllerContract
{
    public function get(string $error_code = "404", string $errorstring = ""): void
    {
        switch ($error_code) {
            case "404":
                TemplateUtility::getTemplate("404", [
                    "title" => "Not Found",
                    "description" => "This is the home page description"
                ]);
                break;
            case "500":
                TemplateUtility::getTemplate("500", [
                    "title" => "System Error",
                    "errorstring" => $errorstring,
                ]);
                break;
            default:
                TemplateUtility::getTemplate("500", [
                    "title" => "System Error",
                    "errorstring" => $errorstring,
                ]);
                break;
        }
    }

    public function error_500(): void
    {
        TemplateUtility::getTemplate("500", [
            "title" => "System Error",
            "errorstring" => "",
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