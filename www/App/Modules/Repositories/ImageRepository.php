<?php

namespace PTW\Modules\Repositories;

use PTW\Models\Image;

class ImageRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct("images");
        $this->element_class = new Image();
    }
}