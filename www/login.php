<?php
require_once("phplibs/templatingManager.php");
require_once("phplibs/DBManager.php");
require_once("phplibs/authHandler.php");

$auth = new AuthManager();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $token = $auth->authenticate($username, $password);
    
    if ($token) {
        // Set token in HTTP-only cookie
        setcookie('jwt_token', $token->payload, $token->exp, '/', '', true, true);
        if ($token->role == 'Administrator') {
            header('Location: adminDashboard.php');
        } else if ($token->role == 'User') {
            header('Location: userDashboard.php');
        }
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