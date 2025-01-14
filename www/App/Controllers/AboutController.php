<?php


namespace PTW\Controllers;

use PTW\Contracts\ControllerContract;
use PTW\Utility\TemplateUtility;

class AboutController extends ControllerContract
{

    public function get(): void
    {
        TemplateUtility::getTemplate('about', ['title' => 'About']);
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