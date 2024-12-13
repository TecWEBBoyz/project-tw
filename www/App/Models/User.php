<?php

namespace PTW\Models;

use function PTW\dd;

class User extends DBItem
{
    /**
     * @var array<UserType, mixed>
     */
    protected array $data;

    /**
     * @param array<UserType, mixed> $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    /**
     * @throws \Exception
     *@var array<UserType, mixed> $data
     */
    public function SetData(array $data): void
    {
        if (!$this->ValidateArray($data))
            throw new \Exception("Invalid data array");

        $this->data = $data;
    }

    public function FilterData(array $data): array
    {
        return $this->FilterDataUtility($data, UserType::cases());
    }

    private function ValidateArray(array $data): bool
    {
        foreach ($data as $key => $_)
        {
            if (!UserType::tryFrom($key))
                return false;
        }

        return true;
    }
}