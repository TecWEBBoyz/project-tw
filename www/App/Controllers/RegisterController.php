<?php

namespace PTW\Controllers;

use PTW\Contracts\ControllerContract;
use PTW\Modules\Auth\Role;
use PTW\Modules\Auth\SessionManager;
use PTW\Utility\TemplateUtility;

class RegisterController extends ControllerContract
{
    public function get(): void
    {
        TemplateUtility::getTemplate('register', ['title' => 'Register']);
    }

    public function post(): void
    {
        $errors = [];

        // Validazione campi obbligatori
        if (empty($_POST['username'])) {
            $errors['username'] = 'Username is required.';
        }

        if (empty($_POST['email'])) {
            $errors['email'] = 'Email is required.';
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format.';
        }

        if (empty($_POST['password'])) {
            $errors['password'] = 'Password is required.';
        } elseif (strlen($_POST['password']) < 8) {
            $errors['password'] = 'Password must be at least 8 characters.';
        }

        if (empty($_POST['confirm-password'])) {
            $errors['confirm-password'] = 'Please confirm your password.';
        } elseif ($_POST['password'] !== $_POST['confirm-password']) {
            $errors['confirm-password'] = 'Passwords do not match.';
        }

        if (!isset($_POST['accept-terms'])) {
            $errors['accept-terms'] = 'You must accept the terms and conditions.';
        }

        // Se ci sono errori, reindirizza alla pagina di registrazione con gli errori
        if (!empty($errors)) {
            TemplateUtility::getTemplate('register', [
                'title' => 'Register',
                'errors' => $errors
            ]);
            return;
        }

        // Simula la registrazione utente (aggiungere logica specifica per il salvataggio)
        $userData = [
            'username' => $_POST['username'],
            'email' => $_POST['email']
        ];

        // Salva i dati utente nella sessione
        $this->sessionManager->SaveUserData($userData, Role::User);

        // Reindirizza alla dashboard dopo la registrazione
        $this->locationReplace('/dashboard');
    }

    public function put(): void
    {
        // Non utilizzato
    }

    public function delete(): void
    {
        // Non utilizzato
    }
}
