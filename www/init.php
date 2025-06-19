<?php

session_start();

require_once 'vendor/autoload.php';
require_once 'App/Helpers.php';

setlocale(LC_ALL, "it_IT", "it_IT.utf8", "Italian_Italy.1252", "it_IT.UTF-8");

$currentPage = basename($_SERVER['PHP_SELF']);
if ($currentPage !== 'login.php' && isset($_SESSION['redirect_after_login'])) {
    unset($_SESSION['redirect_after_login']);
}