<?php

/**
 * About page controller
 */

namespace PTW\Controllers;

use PTW\Contracts\ControllerContract;
use PTW\Modules\Auth\Role;
use PTW\Modules\Auth\SessionManager;
use PTW\Utility\TemplateUtility;

class AdminController extends ControllerContract
{
    public function get(): void
    {
        if(!$this->sessionManager->authorize(Role::Administrator)) {
            $this->locationReplace('/login');
        }
        TemplateUtility::getTemplate('admin', ['title' => 'Admin Dashboard']);
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