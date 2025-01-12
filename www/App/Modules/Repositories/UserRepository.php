<?php

namespace PTW\Modules\Repositories;

use InvalidArgumentException;
use PTW\Models\User;
use PTW\Models\DBItem;
use PTW\Models\UserType;

class UserRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct("user");
        $this->element_class = new User();
    }

    public function GetUserByName(string $name) : User|null
    {
        return  $this->ProcessUser($this->GetElementByUnique(UserType::name->value, $name));
    }

    private function ProcessUser(DBItem|null $item) : User | null
    {
        if ($item == null)
            return null;

        if (!($item instanceof User))
            throw new InvalidArgumentException("DBItem is not a User");

        return $item;
    }
}