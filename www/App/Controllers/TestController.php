<?php

/**
 * About page controller
 */

namespace PTW\Controllers;

use PTW\Contracts\ControllerContract;

class TestController implements ControllerContract
{
    public function get(): void
    {
        $title = "This is a custom title from the inside of the controller";
        $description = "This is a custom description from the inside of the controller";
        $template = require __DIR__ . "/../Templates/Test.php";
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