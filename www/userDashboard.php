<?php
require_once 'init.php';

use PTW\Repositories\BookingRepository;
use PTW\Repositories\ServiceRepository;
use PTW\Services\AuthService;
use PTW\Services\TemplateService;

// Check authentication before allowing access
if (!AuthService::isUserLoggedIn()) {
    header('Location: login.php?error=unauthorized');
    exit;
}

$currentUser = AuthService::getCurrentUser();
$bookingRepo = new BookingRepository();
$serviceRepo = new ServiceRepository();

$bookings = $bookingRepo->GetByUser($currentUser->getId());
$bookingsList = "";
$repeated_replacements = [];
if (empty($bookings)) {
    $bookingsList .= "<p>Non hai ancora prenotato nessuna delle nostre attività!</p>";
    $bookingsList .= "<a>Scopri le nostre attività</a>";
} else {
    $bookingsList = "";
    $counter = 1;
    foreach ($bookings as $booking) {
        if (!$booking instanceof \PTW\Models\Booking) {
            continue;
        }
        if ($booking->getDate() < new DateTime()) {
            continue;
        }

        $service = $serviceRepo->GetElementById($booking->getServiceId());

        if (!$service instanceof \PTW\Models\Service) {
            continue;
        }
        $single_replacement = [
            '[[booking]]' => $counter,
            '[[service]]' => $service->getName(),
            '[[numberOfPeople]]' => $booking->getNumberOfPeople(),
            '[[date]]' => "<time datetime='" . $booking->getDate()->format("Y-m-d") . "'>" . $booking->getDate()->format("d M Y") . "</time>",
            '[[notes]]' => $booking->getNotes() !== '' ? $booking->getNotes() : 'Nessuna',
            '[[bookingId]]' => $booking->getId(),
        ];

        $repeated_replacements["bookingsTable"][] = $single_replacement;
        $counter++;
    }
}


$currentFile = basename(__FILE__);
$htmlContent = TemplateService::renderHtml($currentFile, [
    "[[userName]]" => $currentUser->getName(),
], $repeated_replacements);

echo $htmlContent;
?>