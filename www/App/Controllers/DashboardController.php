<?php

/**
 * About page controller
 */

namespace PTW\Controllers;

use PTW\Contracts\ControllerContract;
use PTW\Modules\Auth\Role;
use PTW\Modules\Auth\SessionManager;
use PTW\Utility\TemplateUtility;

class DashboardController extends ControllerContract
{
    public function get(): void
    {
        if($this->sessionManager->authorize(Role::admin)) {
            $this->locationReplace('/admin');
            return;
        }
        if(!$this->sessionManager->authorize(Role::user)) {
            $this->locationReplace('/');
            return;
        }
        TemplateUtility::getTemplate('dashboard', ['title' => 'Dashboard']);
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