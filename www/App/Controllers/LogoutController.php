<?php

/**
 * About page controller
 */

namespace PTW\Controllers;

use PTW\Contracts\ControllerContract;
use PTW\Modules\Auth\Role;
use PTW\Modules\Auth\SessionManager;

class LogoutController extends ControllerContract
{
    public function get(): void
    {
        #TODO implement login logic
        $this->sessionManager->logout();
        $this->locationReplace('/');
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