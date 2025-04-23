<?php
require_once __DIR__ . '/external/vendor/autoload.php';
require_once 'DBManager.php';

define('JWT_SECRET_KEY', 'hfwuerkao&w09q3/%$ur4(32wdxò[e');  // Change this to a secure random string
define('JWT_EXPIRATION', 3600); // Token expiration in seconds (1 hour)

use \Firebase\JWT\JWT;

class Token {
    public $iat;
    public $exp;
    public $id;
    public $name;
    public $role;
    public $payload;

    public function __construct($user) {
        if (isset($user['iat']) && isset($user['exp'])) {
            $this->iat = $user['iat'];
            $this->exp = $user['exp'];
        } else {
            $this->iat = time();
            $this->exp = $this->iat + JWT_EXPIRATION;
        }

        $this->id = $user['id'];
        $this->name = $user['name'];
        $this->role = $user['role'];

        $payload = [
            'iat' => $this->iat,
            'exp' => $this->exp,
            'id' => $user['id'],
            'name' => $user['name'],
            'role' => $user['role']
        ];

        $this->payload = JWT::encode($payload, JWT_SECRET_KEY, 'HS256');
        return $payload;
    }

    // Getters
    public function getIat(): int {
        return $this->iat;
    }

    public function getExp(): int {
        return $this->exp;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getRole(): string {
        return $this->role;
    }

    public function getPayload(): string {
        return $this->payload;
    }

    public static function validate($token) {
        if ($token === null) {
            return false; // Token not provided
        }
        try {
            $decoded = JWT::decode($token, new \Firebase\JWT\Key(JWT_SECRET_KEY, 'HS256'));
            $decoded_user = new Token((array)$decoded);

            if ($decoded_user->exp < time()) {
                return false; // Token expired
            }
            return $decoded_user;
        } catch (Exception $e) {
            return false;
        }
    }
}

class AuthManager {

    public static function authenticate($username, $password) {
        $user = Database::getUser($username);

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

    public static function requireAuth($role) {
        $token = Token::validate($_COOKIE['jwt_token'] ?? null);

        if (!$token || $token->getRole() != $role) {
            header('Location: login.php?error=unauthorized');
            exit;
        }
    }

    public static function requireUserAuth() {
        AuthManager::requireAuth('User');
    }

    public static function requireAdminAuth() {
        AuthManager::requireAuth('Administrator');
    }
}

?>