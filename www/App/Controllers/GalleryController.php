<?php

/**
 * Home page Controller
 */

namespace PTW\Controllers;

use PTW\Contracts\ControllerContract;
use PTW\Utility\TemplateUtility;

class GalleryController implements ControllerContract
{
    public function get(): void
    {
        TemplateUtility::getTemplate("gallerydetails", [
            "title" => "Photo Gallery",
            "description" => "This is the Photo Gallery description"
        ]);
    }

    public function post(): void
    {
    }

    public function put(): void
    {
    }

    public function delete(): void
    {
    }
}