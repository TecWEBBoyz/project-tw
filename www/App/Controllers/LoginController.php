<?php

/**
 * About page controller
 */

namespace PTW\Controllers;

use PTW\Contracts\ControllerContract;
use PTW\Modules\Auth\Role;
use PTW\Modules\Auth\SessionManager;
use PTW\Utility\TemplateUtility;

class LoginController extends ControllerContract
{
    public function get(): void
    {
        TemplateUtility::getTemplate('login', ['title' => 'Login']);
    }

    public function post(): void
    {
        #TODO implement login logic
        $this->sessionManager->SaveUserData(['username' => 'admin'], Role::Administrator);
        $this->locationReplace('/dashboard');
    }

    public function put(): void
    {
    }

    public function delete(): void
    {
    }
}