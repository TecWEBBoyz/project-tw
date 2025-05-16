<?php

namespace PTW\Services;

use PTW\Models\Token;

define('JWT_SECRET_KEY', 'hfwuerkao&w09q3/%$ur4(32wdxò[e');  // Change this to a secure random string
define('JWT_EXPIRATION', 3600); // Token expiration in seconds (1 hour)

class AuthService {

    public static function authenticate($username, $password) {
        $user = DBService::getUser($username);

        if ($user && $password === $user['password']) { //password_verify($password, $user['password'])
            $token = new Token($user);
            // Set token in HTTP-only cookie
            setcookie('jwt_token', $token->getPayload(), $token->getExp(), '/', '', true, true);
            return $token;
        }
        return false;
    }

    public static function getRedirectUrlForRole($token): string {
        if (!$token) {
            return 'login.php';
        }

        return match ($token->getRole()) {
            'Administrator' => 'adminDashboard.php',
            'User' => 'userDashboard.php',
            default => 'login.php'
        };
    }

    public static function isUserLoggedIn(): bool {
        $token = Token::validate($_COOKIE['jwt_token'] ?? null);
        if ($token && $token->getRole() === 'User') {
            return true;
        }
        return false;
    }

    public static function isAdminLoggedIn(): bool {
        $token = Token::validate($_COOKIE['jwt_token'] ?? null);
        if ($token && $token->getRole() === 'Administrator') {
            return true;
        }
        return false;
    }
}

?>