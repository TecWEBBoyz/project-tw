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
        if (!isset($_POST['username']) || !isset($_POST['password'])) {
            TemplateUtility::getTemplate('login', ['title' => 'Login', 'error' => 'Invalid username or password']);
            return;
        }
        if ($_POST['username'] !== 'admin' || $_POST['password'] !== 'admin') {
            TemplateUtility::getTemplate('login', ['title' => 'Login', 'error' => 'Invalid username or password']);
            return;
        }
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