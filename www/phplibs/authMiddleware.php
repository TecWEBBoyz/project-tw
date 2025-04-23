<?php
require_once 'authHandler.php';

class AuthMiddleware {
    public static function requireUserAuth() {
        $token = Token::validate($_COOKIE['jwt_token'] ?? null);

        if (!$token || $token->role != 'User') {
            header('Location: login.php?error=unauthorized');
            exit;
        }
    }

    public static function requireAdminAuth() {
        $token = Token::validate($_COOKIE['jwt_token'] ?? null);
        
        if (!$token || $token->role != 'Administrator') {
            header('Location: login.php?error=unauthorized');
            exit;
        }
    }
}
?>