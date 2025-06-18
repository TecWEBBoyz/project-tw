<?php
require_once 'init.php';

session_start();

use PTW\Services\AuthService;
use PTW\Services\TemplateService;

$errors = [];
$submitted_username = '';
$errorSummaryHtml = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $submitted_username = $username;

    // Validazione base lato server
    if (empty($username) || empty($password)) {
        $errors['form'] = "Entrambi i campi username e password sono obbligatori.";
    } else {
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
            // Errore di autenticazione
            $errors['form'] = "Username o password non validi. Riprova.";
        }
    }
}

// Se ci sono errori (da validazione o autenticazione)
if (!empty($errors)) {
    $errorSummaryHtml = '<div id="error-summary-container" class="error-summary" role="alert" tabindex="-1">';
    $errorSummaryHtml .= '<h2>Attenzione, sono presenti errori nel modulo:</h2>';
    $errorSummaryHtml .= '<ul id="error-summary-list">';
    $errorSummaryHtml .= '<li>' . htmlspecialchars($errors['form']) . '</li>';
    $errorSummaryHtml .= '</ul></div>';
}


$currentFile = basename(__FILE__);
// Passiamo i dati al template per mostrare gli errori e pre-compilare l'username
$htmlContent = TemplateService::renderHtml($currentFile, [
    "[[errorSummary]]" => $errorSummaryHtml,
    "[[usernameValue]]" => htmlspecialchars($submitted_username)
]);
echo $htmlContent;

?>