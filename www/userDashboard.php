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
$repeated_replacements = [];

if (empty($bookings)) {
    // Nessuna prenotazione trovata
    $repeated_replacements['bookingsTable'] = [
        [
            '[[booking]]' => 'N/A',
            '[[service]]' => 'Nessuna attività prenotata',
            '[[numberOfPeople]]' => 'N/A',
            '[[date]]' => 'N/A',
            '[[notes]]' => 'N/A',
            '[[bookingId]]' => 'N/A',
        ]
    ];
} else {
    $counter = 1;
    foreach ($bookings as $booking) {
        if (!$booking instanceof \PTW\Models\Booking) {
            continue;
        }
        if ($booking->getDate() < new DateTime()) {
            continue; // Salta le prenotazioni passate
        }

        $service = $serviceRepo->GetElementById($booking->getServiceId());
        if (!$service instanceof \PTW\Models\Service) {
            continue;
        }

        // Aggiungi i dati della prenotazione ai segnaposto
        $repeated_replacements['bookingsTable'][] = [
            '[[booking]]' => $counter,
            '[[service]]' => htmlspecialchars($service->getName()),
            '[[numberOfPeople]]' => htmlspecialchars($booking->getNumberOfPeople()),
            '[[date]]' => "<time datetime='" . $booking->getDate()->format("Y-m-d") . "'>" . $booking->getDate()->format("d M Y") . "</time>",
            '[[notes]]' => htmlspecialchars($booking->getNotes() !== '' ? $booking->getNotes() : 'Nessuna'),
            '[[bookingId]]' => htmlspecialchars($booking->getId()),
        ];
        $counter++;
    }
}

$currentFile = basename(__FILE__);
$htmlContent = TemplateService::renderHtml($currentFile, [
    '[[userName]]' => htmlspecialchars($currentUser->getName()),
], $repeated_replacements);

echo $htmlContent;
?>