<?php

namespace PTW\Controllers;

use Exception;
use PTW\Contracts\ControllerContract;
use PTW\Models\Booking;
use PTW\Models\BookingType;
use PTW\Utility\ServicesUtility;
use PTW\Modules\Auth\Role;
use PTW\Modules\Repositories\BookingRepository;
use PTW\Utility\CustomException;
use PTW\Utility\ScrollToUtility;
use PTW\Utility\TemplateUtility;
use PTW\Utility\ToastUtility;

class BookingController extends ControllerContract
{

    public function get(): void
    {
        if (!$this->sessionManager->isAuthenticated()) {
            $this->locationReplace('/login');
        }
        if($this->sessionManager->authorize(Role::Administrator)) {
            $this->locationReplace('/admin');
        }
        TemplateUtility::getTemplate('booking', [
            "title" => \PTW\translation('title-book-service'),
            "description" => \PTW\translation('description-book-service'),
            "keywords" => \PTW\translation('keywords-book-service')
        ]);
    }

    public function updateBookingStatus($data) {
        $bookingRepository = new BookingRepository();
        try {
            if (!isset($data['id'])) {
                throw new Exception(\PTW\translation('booking-no-id'));
            }

            if (!\PTW\Utility\BookingStatusUtility::CheckStatus($data['status'])) {
                throw new Exception(\PTW\translation('booking-status-invalid'));
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
            $this->locationReplace("/admin/bookings");
        }
    }
    private function check(array $values, array &$errors): bool
    {
        if (empty($values['service'])) {
            $this->addError($errors, "service", \PTW\translation('booking-form-error-service-required'));
            return false;
        }

        if (!($values['service'] == "events" || $values['service'] == "other")) {
            $this->addError($errors, "service", \PTW\translation('booking-form-error-service-invalid'));
            return false;
        }

        if (empty($values['date'])) {
            $this->addError($errors, "date", \PTW\translation('booking-form-error-date-required'));
            return false;
        }

        if (strtotime($values['date']) < strtotime(date('Y-m-d'))) {
            $this->addError($errors, "date", \PTW\translation('booking-form-error-date-invalid'));
            return false;
        }

        return true;
    }

    public function post(): void
    {
        $values = [];
        $errors = [];

        $values['service'] = trim($_POST['service']);
        $values['date'] = trim($_POST['date']);
        $values['notes'] = trim($_POST['notes']);

        $fields = $values;

        if (!$this->check($values, $errors))
        {
            TemplateUtility::getTemplate('booking', [
                "error" => $errors,
                "form_fields" => $fields,
                "title" => \PTW\translation('title-book-service'),
                "description" => \PTW\translation('description-book-service'),
                "keywords" => \PTW\translation('keywords-book-service')
                ]);
            return;
        }

        $repo = new BookingRepository();
        $booking = $repo->CreateInstance([
            BookingType::user->value => $this->sessionManager->getUserId(),
            BookingType::service->value => $values['service'],
            BookingType::date->value => $values['date'],
            BookingType::notes->value => $values['notes'],
        ]);

        $repo->Create($booking);

        $this->locationReplace('/profile');
    }

    public function editBooking($data)
    {
        $bookingRepository = new \PTW\Modules\Repositories\BookingRepository();

        $values = [];
        $errors = [];

        $values['service'] = trim($_POST['service']);
        $values['date'] = trim($_POST['date']);
        $values['notes'] = trim($_POST['notes']);

        $fields = $values;

        if (!$this->check($values, $errors))
        {
            if (!isset($data['id'])) {
                throw new Exception(\PTW\translation('booking-no-id'));
            }
            $booking = $bookingRepository->GetElementByID($data['id']);

            TemplateUtility::getTemplate('booking-edit', [
                "error" => $errors,
                "form_fields" => $fields,
                "booking" => $booking,
                "title" => \PTW\translation('title-book-service'),
                "description" => \PTW\translation('description-book-service'),
                "keywords" => \PTW\translation('keywords-book-service')
            ]);
            return;
        }

        try {
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

    public function put(): void
    {
        // TODO: Implement put() method.
    }

    public function delete(): void
    {
        // TODO: Implement delete() method.
    }

    private function addError(array &$errors, string $key, string $msg): void
    {
        $errors[$key] = $msg;
    }
}