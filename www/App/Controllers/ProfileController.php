<?php

/**
 * About page controller
 */

namespace PTW\Controllers;

use Exception;
use PTW\Contracts\ControllerContract;
use PTW\Models\BookingType;
use PTW\Models\Image;
use PTW\Modules\Auth\Role;
use PTW\Modules\Auth\SessionManager;
use PTW\Modules\Repositories\BookingRepository;
use PTW\Utility\CustomException;
use PTW\Utility\ScrollToUtility;
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

    public function editSingleBooking($data, $templateData = [])
    {
        try {
            $bookingRepository = new \PTW\Modules\Repositories\BookingRepository();
            if(!isset($data['id'])) {
                throw new Exception(\PTW\translation('admin-image-no-id'));
            }
            $booking = $bookingRepository->GetElementByID($data['id']);

            if ($booking == null) {
                throw new Exception(\PTW\translation('admin-image-not-found'));
            }
            TemplateUtility::getTemplate('booking-edit', array_merge([
                "title" => \PTW\translation('title-edit-image'),
                "description" => \PTW\translation('description-edit-image'),
                "keywords" => \PTW\translation('keywords-edit-image'),
                "booking" => $booking], $templateData));
        }catch (Exception $e) {
            ScrollToUtility::setScrollTarget($data['id']);
            ToastUtility::addToast('error', \PTW\translation('image-edit-error'));
            $this->previusPage();
        }
    }

    public function editBooking($data)
    {
        try {
            $bookingRepository = new \PTW\Modules\Repositories\BookingRepository();
            if (!isset($data['id'])) {
                throw new Exception(\PTW\translation('booking-no-id'));
            }
            $booking = $bookingRepository->GetElementByID($data['id']);
            if($booking == null) {
                throw new Exception(\PTW\translation('booking-not-found'));
            }

            $booking->SetData($booking->FilterData($data));

            $bookingRepository->Update($_POST['id'], $booking);

        }catch (CustomException $e) {
            ToastUtility::addToast('error', $e->getMessage());
        } catch (Exception $e) {
            ToastUtility::addToast('error', \PTW\translation('booking-edit-error'));

        } finally {
            ScrollToUtility::setScrollTarget($data['id']);
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