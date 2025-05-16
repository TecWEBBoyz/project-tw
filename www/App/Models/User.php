<?php

namespace PTW\Models;

use Exception;

class User implements DBItem
{
    protected string $id;
    protected string $username;
    protected string $email;
    protected string $telephone;
    protected string $password;
    protected string $role;

    /**
     * @throws Exception
     */
    public function __construct(array $data = [])
    {
        try {
            $this->setData($data);
        } catch (\Throwable $e) {
            throw new Exception("Error in User constructor: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function setData(array $data): void
    {
        if (!$this->ValidateArray($data))
            throw new Exception("Invalid data array");

        $this->id = $data['id'];
        $this->username = $data['username'];
        $this->email = $data['email'];
        $this->telephone = $data['telephone'];
        $this->password = $data['password'];
        $this->role = $data['role'];
    }

    private function validateArray(array $data): bool
    {
        if (!isset($data['id']) || !isset($data['username']) ||
            !isset($data['email']) || !isset($data['telephone']) ||
            !isset($data['password']) || !isset($data['role'])) {
            return false;
        }
        return true;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'telephone' => $this->telephone,
            'password' => $this->password,
            'role' => $this->role
        ];
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getTelephone(): string
    {
        return $this->telephone;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRole(): string
    {
        return $this->role;
    }
}