<?php


namespace PTW\Controllers;

use PTW\Contracts\ControllerContract;
use PTW\Utility\TemplateUtility;

class AboutController extends ControllerContract
{

    public function get(): void
    {
        TemplateUtility::getTemplate('about', [
            "title" => \PTW\translation('title-about'),
            "description" => \PTW\translation('description-about'),
            "keywords" => \PTW\translation('keywords-about')
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