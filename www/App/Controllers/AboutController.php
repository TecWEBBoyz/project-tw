<?php

/**
 * About page controller
 */

namespace PTW\Controllers;

use PTW\Contracts\ControllerContract;

class AboutController extends ControllerContract
{
    public function get(): void
    {
        $template = require __DIR__ . "/../Templates/about.html";
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