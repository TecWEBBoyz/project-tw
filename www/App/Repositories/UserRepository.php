<?php

namespace PTW\Repositories;

use PTW\Models\DBItem;
use PTW\Models\User;

class UserRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct('user');
        $this->element_class = new \PTW\Models\User();
    }

    public function GetElementByUsername(string $username): ?DBItem
    {
        return $this->GetElementByUnique("username", $username);
    }
}