<?php

/**
 * About page controller
 */

namespace PTW\Controllers;

use PTW\Contracts\ControllerContract;
use PTW\Utility\TemplateUtility;

class TestController extends ControllerContract
{
    public function get(): void
    {
        TemplateUtility::getTemplate("test", [
            "title" => "",
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