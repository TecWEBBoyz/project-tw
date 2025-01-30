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
        $repo = new \PTW\Modules\Repositories\ImageRepository();
        $images = $repo->GetAllVisible();
        TemplateUtility::getTemplate("home", [
            "images" => $images,
            "title" => \PTW\translation('title-home'),
            "description" => \PTW\translation('description-home'),
            "keywords" => \PTW\translation('keywords-home')
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