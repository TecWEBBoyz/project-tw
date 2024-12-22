<?php

/**
 * Home page Controller
 */

namespace PTW\Controllers;

use PTW\Contracts\ControllerContract;
use PTW\Utility\TemplateUtility;

class HomeController implements ControllerContract
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