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

    public function GetImagesByCategory(string $category, int|null $current_page = null, int|null $size = 10 ): array
    {
        $offset = ($current_page - 1) * $size;

        $res = [];

        if (isset($current_page)) {
            $res = $this->database->Query("SELECT * FROM $this->table WHERE category=? ORDER BY order_id LIMIT {$size} OFFSET {$offset}", [$category]);
        } else {
            $res = $this->database->Query("SELECT * FROM $this->table WHERE category=? ORDER BY order_id", [$category]);
        }

        return $this->CreateInstances($res);
    }

    public function GetAllVisible()
    {
        $visible = ImageType::visible->value;
        $res = $this->database->Query("SELECT * FROM $this->table WHERE $visible = 1 ORDER BY order_id");
        return $this->CreateInstances($res);
    }

    public function GetNextImage($order_val, $category)
    {
        $order = ImageType::order->value;
        $res = $this->database->Query("SELECT * FROM $this->table WHERE $order > ? AND category=? ORDER BY $order LIMIT 1", [$order_val, $category]);
        return $this->CreateInstances($res);
    }

    public function GetPreviusImage($order_val, $category)
    {
        $order = ImageType::order->value;
        $res = $this->database->Query("SELECT * FROM $this->table WHERE $order < ? AND category=? ORDER BY $order DESC LIMIT 1", [$order_val, $category]);
        return $this->CreateInstances($res);
    }

}