<?php
require_once 'init.php';

use PTW\Repositories\BookingRepository;
use PTW\Repositories\ServiceRepository;
use PTW\Services\AuthService;
use PTW\Services\TemplateService;

function formatDateItalian(DateTime $date): string {
    $months = [
        1 => 'Gen', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
        5 => 'Mag', 6 => 'Giu', 7 => 'Lug', 8 => 'Ago',
        9 => 'Set', 10 => 'Ott', 11 => 'Nov', 12 => 'Dic'
    ];
    
    $day = $date->format('d');
    $month = $months[(int)$date->format('n')];
    $year = $date->format('Y');
    
    return "$day $month $year";
}

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
            '[[date]]' => "<time datetime='" . $booking->getDate()->format("Y-m-d") . "'>" . formatDateItalian($booking->getDate()) . "</time>",
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