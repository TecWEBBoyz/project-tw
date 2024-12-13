<?php

/**
 * Home page Controller
 */

namespace PTW\Controllers;

use PTW\Contracts\ControllerContract;

class HomeController implements ControllerContract
{
    public function get(): void
    {
        $template = require __DIR__ . "/../Templates/home.html";
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