<?php


namespace PTW\Controllers;

use PTW\Contracts\ControllerContract;
use PTW\Utility\TemplateUtility;

class ServicesController extends ControllerContract
{
    public function get(): void
    {
        TemplateUtility::getTemplate('services', ['title' => 'Services']);
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