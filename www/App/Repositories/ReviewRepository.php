<?php

namespace PTW\Repositories;

use PTW\Models\Review;

class ReviewRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct("review");
        $this->element_class = new Review();
    }
}