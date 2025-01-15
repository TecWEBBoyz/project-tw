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
        if(!$this->sessionManager->authorize(Role::user)) {
            $this->locationReplace('/login');
        }
        TemplateUtility::getTemplate('booking', ['title' => 'Booking']);
    }

    public function post(): void
    {
        if (!isset($_POST['service']) || !isset($_POST['date'])) {
            TemplateUtility::getTemplate('booking', ['title' => 'Login', 'error' => 'You need to enter service and date']);
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