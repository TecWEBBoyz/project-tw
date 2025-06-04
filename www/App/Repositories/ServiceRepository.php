<?php

namespace PTW\Repositories;

class ServiceRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct('service');
        $this->element_class = new \PTW\Models\Service();
    }

}