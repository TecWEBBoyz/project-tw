<?php

/**
 * About page controller
 */

namespace PTW\Controllers;

use PTW\Contracts\ControllerContract;

class ExceptionController implements ControllerContract
{
    public function get(string $error_code = "404"): void
    {
        switch ($error_code) {
            case "404":
                require __DIR__ . "/../Templates/404.html";
                break;
        }
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