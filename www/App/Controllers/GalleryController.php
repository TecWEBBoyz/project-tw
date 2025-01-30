<?php

/**
 * Home page Controller
 */

namespace PTW\Controllers;

use PTW\Contracts\ControllerContract;
use PTW\Utility\TemplateUtility;

class GalleryController extends ControllerContract
{
    public function get(): void
    {
        TemplateUtility::getTemplate("gallerydetails", [
            "title" => \PTW\translation("title-gallery-details"),
            "description" => \PTW\translation("description-gallery-details"),
            "keywords" => \PTW\translation("keywords-gallery-details")
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