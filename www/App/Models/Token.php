<?php

namespace PTW\Models;

use Firebase\JWT\JWT;

class Token {
    private $iat;
    private $exp;
    private $id;
    private $name;
    private $role;
    private $payload;

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
            $decoded_user = new \PTW\Services\Token((array)$decoded);

            if ($decoded_user->exp < time()) {
                return false; // Token expired
            }
            return $decoded_user;
        } catch (Exception $e) {
            return false;
        }
    }
}