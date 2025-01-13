<?php


namespace PTW\Controllers;

use PTW\Contracts\ControllerContract;
use PTW\Utility\TemplateUtility;

class ContactController extends ControllerContract
{
    public function get(): void
    {
        TemplateUtility::getTemplate('contact', ['title' => 'Contact']);
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