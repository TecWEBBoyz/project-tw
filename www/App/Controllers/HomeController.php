<?php

/**
 * Home page Controller
 */

namespace PTW\Controllers;

use PTW\Contracts\ControllerContract;
use PTW\Models\User;
use PTW\Models\UserType;
use PTW\Modules\Database\DB;
use PTW\Modules\Repositories\BaseRepository;
use PTW\Models\DBItem;
use PTW\Modules\Repositories\UserRepository;

use function PTW\dd;
use PTW\Utility\TemplateUtility;

class HomeController extends ControllerContract
{
    public function get(): void
    {
        TemplateUtility::getTemplate("home", [
            "title" => "Home Page",
            "description" => "This is the home page description"
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