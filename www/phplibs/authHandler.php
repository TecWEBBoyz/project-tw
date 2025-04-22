<?php
require_once __DIR__ . '/external/vendor/autoload.php';
require_once 'DBManager.php';

define('JWT_SECRET_KEY', 'hfwuerkao&w09q3/%$ur4(32wdxò[e');  // Change this to a secure random string
define('JWT_EXPIRATION', 3600); // Token expiration in seconds (1 hour)

use \Firebase\JWT\JWT;

class Token {
    public $payload;

    public $iat;
    public $exp;
    public $sub;
    public $username;
    public $role;

    public function __construct($user) {
        $issuedAt = time();
        $expire = $issuedAt + JWT_EXPIRATION;

        $this->iat = $issuedAt;
        $this->exp = $expire;
        $this->sub = $user['id'];
        $this->username = $user['name'];
        $this->role = $user['role'];

        $payload = [
            'iat' => $issuedAt,
            'exp' => $expire,
            'sub' => $user['id'],
            'username' => $user['name'],
            'role' => $user['role']
        ];

        $this->payload = JWT::encode($payload, JWT_SECRET_KEY, 'HS256');
        return $payload;
    }

    public static function validate($token) {
        try {
            $decoded = JWT::decode($token, new \Firebase\JWT\Key(JWT_SECRET_KEY, 'HS256'));
            return (array) $decoded;
        } catch (Exception $e) {
            return false;
        }
    }
}

class AuthManager {

    // private function generateToken($user) {
    //     $issuedAt = time();
    //     $expire = $issuedAt + JWT_EXPIRATION;

    //     $payload = [
    //         'iat' => $issuedAt,
    //         'exp' => $expire,
    //         'sub' => $user['id'],
    //         'username' => $user['name'],
    //         'role' => $user['role']
    //     ];

    //     return JWT::encode($payload, JWT_SECRET_KEY, 'HS256');
    // }

    public function authenticate($username, $password) {
        $user = Database::getUser($username);

        if ($user && $password === $user['password']) { //password_verify($password, $user['password'])
            return new Token($user);
        }
        return false;
    }

    public static function validateToken($token) {
        try {
            $decoded = JWT::decode($token, new \Firebase\JWT\Key(JWT_SECRET_KEY, 'HS256'));
            return (array) $decoded;
        } catch (Exception $e) {
            return false;
        }
    }
}

?>