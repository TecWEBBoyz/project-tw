<?php
require_once("phplibs/authManager.php");

setcookie('jwt_token', '', time() - 3600, '/');

header('Location: index.php');
exit;
?>