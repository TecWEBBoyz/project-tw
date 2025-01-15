<?php

/**
 * About page controller
 */

namespace PTW\Controllers;

use Exception;
use PTW\Contracts\ControllerContract;
use PTW\Models\BookingType;
use PTW\Models\Image;
use PTW\Models\ImageType;
use PTW\Modules\Auth\Role;
use PTW\Modules\Auth\SessionManager;
use PTW\Modules\Repositories\BookingRepository;
use PTW\Utility\TemplateUtility;
use PTW\Utility\ToastUtility;

$imageRepository = new \PTW\Modules\Repositories\ImageRepository();

class ProfileController extends ControllerContract
{
    public function get(): void
    {
        if(!$this->sessionManager->authorize(Role::user)) {
            $this->locationReplace('/login');
        }

        $repo = new BookingRepository();
        $bookings = $repo->GetElementsByColumn(BookingType::user->value, $this->sessionManager->getUserId());

        TemplateUtility::getTemplate('profile', ['title' => 'Profile page', "user" => $this->sessionManager->getUserData(), "bookings" => $bookings]);
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