<?php


namespace PTW\Controllers;

use PTW\Contracts\ControllerContract;
use PTW\Utility\TemplateUtility;

class ServicesController extends ControllerContract
{
    public function get(): void
    {
        TemplateUtility::getTemplate('services', [
            "title" => \PTW\translation('title-services'),
            "description" => \PTW\translation('description-services'),
            "keywords" => \PTW\translation('keywords-services')
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