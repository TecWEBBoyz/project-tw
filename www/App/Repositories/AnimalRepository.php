<?php

namespace PTW\Repositories;

use PTW\Models\Animal;

class AnimalRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct("animal");
        $this->element_class = new Animal();
    }
}