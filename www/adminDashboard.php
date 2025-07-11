<?php
require_once 'init.php';

use PTW\Services\AuthService;
use PTW\Services\TemplateService;
use PTW\Repositories\AnimalRepository;
use PTW\Repositories\BookingRepository;
use PTW\Repositories\ServiceRepository;
use PTW\Repositories\UserRepository;

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
if (!AuthService::isAdminLoggedIn()) {
    header('Location: login.php?error=unauthorized');
    exit;
}

$animalRepo = new AnimalRepository();
$bookingRepo = new BookingRepository();
$serviceRepo = new ServiceRepository();
$userRepo = new UserRepository();

// Bookings
$bookings = $bookingRepo->All();
$bookingsList = "";

$repeated_replacements = [];
if (empty($bookings)) {
    $bookingsList .= "<p>Non hai ancora prenotato nessuna delle nostre attività!</p>";
    $bookingsList .= "<a>Scopri le nostre attività</a>";
} else {
    $counter = 1;
    foreach ($bookings as $booking) {
        if (!$booking instanceof \PTW\Models\Booking) {
            continue;
        }

        $service = $serviceRepo->GetElementById($booking->getServiceId());
        if (!$service instanceof \PTW\Models\Service) {
            continue;
        }

        $bookingUser = $userRepo->GetElementById($booking->getUserId());
        if (!$bookingUser instanceof \PTW\Models\User) {
            continue;
        }

        $single_replacement = [
            '[[booking]]' => $counter,
            '[[service]]' => $service->getName(),
            '[[bookingUserName]]' => $bookingUser->getUsername(),
            '[[numberOfPeople]]' => $booking->getNumberOfPeople(),
            '[[notes]]' => $booking->getNotes() ?: 'Nessuna nota',
            '[[date]]' => "<time datetime='" . $booking->getDate()->format("Y-m-d") . "'>" . formatDateItalian($booking->getDate()) . "</time>",
        ];
        if ($booking->getDate() >= new DateTime("tomorrow")) {
            $single_replacement["[[actions]]"] = "<div class='booking-actions'>
                        <form action='deleteBooking.php' method='POST'>
                            <input type='hidden' name='booking_id' value='" . $booking->getId() ."'>
                            <button class='button' type='submit' aria-label='Elimina prenotazione " . $counter . "'>Elimina</button>
                        </form>
                    </div>";
        } else {
            $single_replacement["[[actions]]"] = "Non è possibile rimuovere questa attività.";
        }

        $repeated_replacements["bookingsBody"][] = $single_replacement;
        $counter++;
    }
}


// Animals
$animals = $animalRepo->All();

if (empty($animals)) {
    $bookingsList .= "<p>Non è ancora stato inserito alcun animale!</p>";
} else {
    $counter = 1;
    foreach ($animals as $animal) {
        if (!$animal instanceof \PTW\Models\Animal) {
            continue;
        }

        $single_replacement = [
            "[[animalId]]" => $counter,
            "[[animalName]]" => $animal->getName(),
            "[[animalDescription]]" => $animal->getDescription(),
            "[[animalSpecies]]" => $animal->getSpecies(),
            "[[animalHabitat]]" => $animal->getHabitat(),
            "[[actions]]" => "
                <div class='booking-actions'>
                    <form action='deleteAnimal.php' method='POST'>
                        <input type='hidden' name='animal_id' value='" . $animal->getId() ."'>
                        <button class='button' type='submit' aria-label='Elimina animale " . $counter . "'>Elimina</button>
                    </form>
                </div>
                <div class='booking-actions'>
                    <form action='editAnimal.php' method='GET'>
                        <input type='hidden' name='animal_id' value='" . $animal->getId() ."'>
                        <button class='button' type='submit' aria-label='Modifica animale " . $counter . "'>Modifica</button>
                    </form>
                </div>
            "
        ];
            $single_replacement[] = "";

        $repeated_replacements["animalsBody"][] = $single_replacement;
        $counter++;
    }
}

$currentFile = basename(__FILE__);
$htmlContent = TemplateService::renderHtml($currentFile, [
    "[[userName]]" => AuthService::getCurrentUser()->getName(),
], $repeated_replacements);

echo $htmlContent;
?>