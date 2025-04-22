<?php
require_once 'authHandler.php';

class AuthMiddleware {
    public static function requireAuth() {
        $token = $_COOKIE['jwt_token'] ?? null;
        
        if (!$token || !AuthManager::validateToken($token)) {
            header('Location: login.php');
            exit;
        }
    }
}
?>