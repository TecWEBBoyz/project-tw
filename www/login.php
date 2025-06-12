<?php
require_once 'init.php';

session_start();

use PTW\Services\AuthService;
use PTW\Services\TemplateService;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $token = AuthService::authenticate($username, $password);
    
    if ($token) {
        // Controlla se c'è un URL di redirect salvato
        if (isset($_SESSION['redirect_after_login'])) {
            $redirectUrl = $_SESSION['redirect_after_login'];
            unset($_SESSION['redirect_after_login']); // Rimuovi dalla sessione
            header("Location: $redirectUrl");
        } else {
            // Redirect normale basato sul ruolo
            $redirectUrl = AuthService::getRedirectUrlForRole($token);
            header("Location: $redirectUrl");
        }
        exit;
    } else {
        $error = "Invalid credentials";
        echo $error; //TODO: Handle better
    }
}

$currentFile = basename(__FILE__);
$htmlContent = TemplateService::renderHtml($currentFile);
echo $htmlContent;

?>