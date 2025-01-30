<?php

/**
 * About page controller
 */

namespace PTW\Controllers;

use PTW\Contracts\ControllerContract;
use PTW\Models\UserType;
use PTW\Modules\Auth\Role;
use PTW\Modules\Auth\SessionManager;
use PTW\Modules\Repositories\UserRepository;
use PTW\Utility\TemplateUtility;

class LoginController extends ControllerContract
{
    public function get(): void
    {
        TemplateUtility::getTemplate('login', [
            "title"=>\PTW\translation('title-login'),
            "description"=>\PTW\translation('description-login'),
            "keywords"=>\PTW\translation('keywords-login')
        ]);
    }

    public function post(): void
    {
        if (!isset($_POST['username']) || !isset($_POST['password'])) {
            TemplateUtility::getTemplate('login', [
                'error' => \PTW\translation('login-form-error'),
                "title"=>\PTW\translation('title-login'),
                "description"=>\PTW\translation('description-login'),
                "keywords"=>\PTW\translation('keywords-login')]);
            return;
        }

        # ToDo(Luca) Refactor Login
        $userRepo = new UserRepository();
        $user = $userRepo->GetUserByName($_POST['username']);

        if ($user == null)
        {
            TemplateUtility::getTemplate('login', [
                'error' => \PTW\translation('login-form-error-user-not-exists'),
                "title"=>\PTW\translation('title-login'),
                "description"=>\PTW\translation('description-login'),
                "keywords"=>\PTW\translation('keywords-login')]);
            return;
        }

        if (!($user->ToArray()[UserType::password->value] == $_POST['password']))
        {
            TemplateUtility::getTemplate('login', [
                'error' => \PTW\translation('login-form-error'),
                "title"=>\PTW\translation('title-login'),
                "description"=>\PTW\translation('description-login'),
                "keywords"=>\PTW\translation('keywords-login')]);
            return;
        }

        $role = $user->ToArray()[UserType::role->value];
        $this->sessionManager->SaveUserData(
            ['username' => $user->ToArray()[UserType::name->value], 'id' => $user->ToArray()[UserType::id->value]], Role::fromCaseName($role));

        switch ($this->sessionManager->GetUserRole())
        {
            case Role::Administrator:
                $this->locationReplace('/dashboard');
                break;
            case Role::User:
                $this->locationReplace('/home');
                break;
        }
    }

    public function put(): void
    {
    }

    public function delete(): void
    {
    }
}