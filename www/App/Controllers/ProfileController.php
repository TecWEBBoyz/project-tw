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
        if(!$this->sessionManager->authorize(Role::User)) {
            $this->locationReplace('/login');
        }

        $repo = new BookingRepository();
        $bookings = $repo->GetElementsByColumn(BookingType::user->value, $this->sessionManager->getUserId());

        TemplateUtility::getTemplate('profile', [
            "user" => $this->sessionManager->getUserData(),
            "bookings" => $bookings,
            "title" => \PTW\translation('title-profile'),
            "description" => \PTW\translation('description-profile'),
            "keywords" => \PTW\translation('keywords-profile')
        ]);
    }

    public function deleteBooking($data)
    {
        try {
            if (!isset($data['id'])) {
                throw new Exception("No booking ID provided.");
            }
            $bookingRepository = new \PTW\Modules\Repositories\BookingRepository();
            $booking = $bookingRepository->GetElementByID($data['id']);
            if($booking == null) {
                throw new Exception("No Booking found.");
            }
            $booking = $booking->ToArray();

            $res = $bookingRepository->Delete($data['id']);

            if ($res) {
                throw new Exception("Error deleting booking.");
            }
            ToastUtility::addToast('success', \PTW\translation('booking-deleted'));
        } catch (Exception $e) {
            ToastUtility::addToast('error', \PTW\translation('booking-delete-error'));
        } finally {
            $this->locationReplace("/profile");
        }
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