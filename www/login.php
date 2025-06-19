<?php
require_once 'init.php';


use PTW\Services\AuthService;
use PTW\Services\TemplateService;

$errors = [];
$submitted_username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $submitted_username = $username;

    // Validazione base lato server
    if (empty($username)) {
        $errors['username'] = "Il campo username è obbligatorio.";
    }
    if (empty($password)) {
        $errors['password'] = "Il campo password è obbligatorio.";
    }
    
    if (empty($errors)) {
        $token = AuthService::authenticate($username, $password);
        
        if ($token) {
            // Controlla se c'è un URL di redirect salvato
            if (isset($_SESSION['redirect_after_login'])) {
                $redirectUrl = $_SESSION['redirect_after_login'];
                unset($_SESSION['redirect_after_login']);
                header("Location: $redirectUrl");
            } else {
                // Redirect normale basato sul ruolo
                $redirectUrl = AuthService::getRedirectUrlForRole($token);
                header("Location: $redirectUrl");
            }
            exit;
        } else {
            // Errore di autenticazione generico, mostrato nel riepilogo
            $errors['form'] = "Username o password non validi. Riprova.";
        }
    }
}

$errorSummaryListItems = '';
$errorSummaryHidden = 'hidden';

if (!empty($errors)) {
    $errorSummaryHidden = ''; // Rendi visibile il contenitore
    // Popola la lista degli errori
    if (isset($errors['form'])) {
         $errorSummaryListItems .= '<li>' . htmlspecialchars($errors['form']) . '</li>';
    } else {
        if (isset($errors['username'])) $errorSummaryListItems .= '<li><a href="#username">' . htmlspecialchars($errors['username']) . '</a></li>';
        if (isset($errors['password'])) $errorSummaryListItems .= '<li><a href="#password">' . htmlspecialchars($errors['password']) . '</a></li>';
    }
}

$replacements = [
    "[[errorSummaryListItems]]" => $errorSummaryListItems,
    "[[errorSummaryHidden]]" => $errorSummaryHidden,
    "[[usernameValue]]" => htmlspecialchars($submitted_username),

    '[[usernameError]]' => isset($errors['username']) ? htmlspecialchars($errors['username']) : '',
    '[[usernameErrorHidden]]' => isset($errors['username']) ? '' : 'hidden',
    '[[usernameInvalid]]' => isset($errors['username']) || isset($errors['form']) ? 'aria-invalid="true"' : '',

    '[[passwordError]]' => isset($errors['password']) ? htmlspecialchars($errors['password']) : '',
    '[[passwordErrorHidden]]' => isset($errors['password']) ? '' : 'hidden',
    '[[passwordInvalid]]' => isset($errors['password']) || isset($errors['form']) ? 'aria-invalid="true"' : '',
];

$currentFile = basename(__FILE__, '.php') . '.html';
$htmlContent = TemplateService::renderHtml($currentFile, $replacements);
echo $htmlContent;

?>