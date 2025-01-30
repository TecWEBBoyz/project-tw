<?php

namespace PTW\Controllers;

use PTW\Contracts\ControllerContract;
use PTW\Models\Booking;
use PTW\Models\BookingType;
use PTW\Modules\Auth\Role;
use PTW\Modules\Repositories\BookingRepository;
use PTW\Utility\TemplateUtility;

class BookingController extends ControllerContract
{

    public function get(): void
    {
        if(!$this->sessionManager->authorize(Role::User)) {
            $this->locationReplace('/login');
        }
        TemplateUtility::getTemplate('booking', [
            "title" => \PTW\translation('title-book-service'),
            "description" => \PTW\translation('description-book-service'),
            "keywords" => \PTW\translation('keywords-book-service')
        ]);
    }

    public function post(): void
    {
        if (!isset($_POST['service']) || !isset($_POST['date'])) {
            TemplateUtility::getTemplate('booking', [
                'error' => \PTW\translation('profile-booking-no-service-date-entered'),
                "title" => \PTW\translation('title-book-service'),
                "description" => \PTW\translation('description-book-service'),
                "keywords" => \PTW\translation('keywords-book-service')
            ]);
            return;
        }

        $repo = new BookingRepository();
        $booking = $repo->CreateInstance([
            BookingType::user->value => $this->sessionManager->getUserId(),
            BookingType::service->value => $_POST['service'],
            BookingType::date->value => $_POST['date'],
            BookingType::notes->value => $_POST['notes'],
        ]);

        $repo->Create($booking);

        $this->locationReplace('/');
    }

    public function put(): void
    {
        // TODO: Implement put() method.
    }

    public function delete(): void
    {
        // TODO: Implement delete() method.
    }
}