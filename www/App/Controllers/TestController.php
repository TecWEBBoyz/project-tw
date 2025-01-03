<?php

/**
 * About page controller
 */

namespace PTW\Controllers;

use PTW\Contracts\ControllerContract;
use PTW\Utility\TemplateUtility;

class TestController implements ControllerContract
{
    public function get(): void
    {
        TemplateUtility::getTemplate("home", [
            "title" => "Home Page",
            "description" => "This is the home page description"
        ]);
    }

    public function post(): void
    {
    }

    public function put(): void
    {
    }

    public function delete(): void
    {
    }
}