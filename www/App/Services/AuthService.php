<?php

namespace PTW\Services;

use PTW\Models\Token;
use PTW\Models\TokenDTO;
use PTW\Models\User;
use PTW\Repositories\UserRepository;
use function PTW\config;

define('JWT_SECRET_KEY', config("JWT.JWT_SECRET_KEY"));
define('JWT_EXPIRATION', config("JWT.JWT_EXPIRATION"));

class AuthService {

    public static function authenticate($username, $password) {
        $userRepo = new UserRepository();
        $user = $userRepo->GetElementByUsername($username);

        if (!$user instanceof User) {
            return false;
        }

        if ($password === $user->getPassword()) {
            //password_verify($password, $user['password'])

            $token = Token::create($user);

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

    public static function getCurrentUser(): ?TokenDTO {
        $token = Token::validate($_COOKIE['jwt_token'] ?? null);

        if ($token) {
            return TokenDTO::create($token);
        }

        return null;
    }
}

?>