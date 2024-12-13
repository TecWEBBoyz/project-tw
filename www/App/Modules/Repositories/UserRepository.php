<?php

namespace PTW\Modules\Repositories;

use PTW\Models\User;

class UserRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct("user");
        $this->element_class = new User();
    }
}