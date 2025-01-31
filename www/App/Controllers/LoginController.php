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
use function PTW\dd;

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

    private function addError(array &$errors, string $key, string $msg): void
    {
//        if (!isset($errors[$key])) {
//            $errors[$key] = [];
//        }
        $errors[$key] = $msg;
    }

    private function check(array $values, array &$errors): bool
    {
        if (empty($values['username']))
        {
            $this->addError($errors, "username", \PTW\translation('login-form-error-username-required'));
            return false;
        }

        if (empty($values['password']))
        {
            $this->addError($errors, "password", \PTW\translation("login-form-error-password-required"));
            return false;
        }

        $userRepo = new UserRepository();
        $user = $userRepo->GetUserByName($values['username']);

        $sha265 = hash('sha256', $_POST['password']);
        if ($user == null || !($user->ToArray()[UserType::password->value] == $sha265))
        {
            $this->addError($errors, "form", \PTW\translation('login-form-error'));
            return false;
        }

        $role = $user->ToArray()[UserType::role->value];
        $this->sessionManager->SaveUserData(
            ['username' => $user->ToArray()[UserType::name->value], 'id' => $user->ToArray()[UserType::id->value]], Role::fromCaseName($role));

        return true;
    }

    public function post(): void
    {
        $values = [];
        $errors = [];

        $values['username'] = trim($_POST['username']);
        $values['password'] = trim($_POST['password']);

        $fields['username'] = $values['username'];

        if (!$this->check($values, $errors))
        {
            TemplateUtility::getTemplate('login', [
                "error" => $errors,
                "form_fields" => $fields,
                "title"=>\PTW\translation('title-login'),
                "description"=>\PTW\translation('description-login'),
                "keywords"=>\PTW\translation('keywords-login')]);
            return;
        }

        switch ($this->sessionManager->GetUserRole())
        {
            case Role::Administrator:
                $this->locationReplace('/admin');
                break;
            case Role::User:
                $this->locationReplace('/profile');
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