<?php

namespace PTW\Modules\Repositories;

use PTW\Models\Image;
use PTW\Models\ImageType;

class ImageRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct("image");
        $this->element_class = new Image();
    }
    public function GetJustUploadedImages(): array
    {
        $updated_at = ImageType::updated_at->value;
        $res = $this->database->Query("SELECT * FROM $this->table WHERE $updated_at IS NULL ORDER BY id");
        return $this->CreateInstances($res);
    }

    public function GetAllVisible()
    {
        $visible = ImageType::visible->value;
        $res = $this->database->Query("SELECT * FROM $this->table WHERE $visible = 1 ORDER BY id");
        return $this->CreateInstances($res);
    }
}