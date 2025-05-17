<?php

namespace PTW\Repositories;

class UserRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct('user');
        $this->element_class = new \PTW\Models\User();
    }
}