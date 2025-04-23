<?php
require_once("phplibs/templatingManager.php");
require_once("phplibs/DBManager.php");
require_once("phplibs/authManager.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $token = AuthManager::authenticate($username, $password);
    
    if ($token) {
        $redirectUrl = AuthManager::getRedirectUrlForRole($token);
        header("Location: $redirectUrl");
        exit;
    } else {
        $error = "Invalid credentials";
        echo $error; //TODO: Handle better
    }
}

$currentFile = basename(__FILE__);
$htmlContent = Templating::renderHtml($currentFile);
echo $htmlContent;

?>