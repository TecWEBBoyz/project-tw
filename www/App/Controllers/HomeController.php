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

class HomeController implements ControllerContract
{
    public function get(): void
    {
        $template = require __DIR__ . "/../Templates/home.html";
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