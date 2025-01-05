<?php

namespace PTW\Modules\Repositories;

use PTW\Models\Image;

class ImageRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct("image");
        $this->element_class = new Image();
    }
}