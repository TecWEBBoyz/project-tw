<?php

namespace PTW\Models;

use Firebase\JWT\JWT;

class TokenDTO { 

    private $exp;
    private $id;
    private $name;
    private $role;

    private function __construct(Token $token) {
        $this->exp = $token->getExp();
        $this->id = $token->getId();
        $this->name = $token->getName();
        $this->role = $token->getRole();
    }

    public static function create(Token $token): TokenDTO {
        return new TokenDTO($token);
    }

    public function getExp() {
        return $this->exp;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getRole() {
        return $this->role;
    }
}