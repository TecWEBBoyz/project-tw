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

if (empty($bookings)) {
    $bookingsList .= "<p>Non hai ancora prenotato nessuna delle nostre attività!</p>";
    $bookingsList .= "<a>Scopri le nostre attività</a>";
} else {
    $bookingsList = "<ul class='bookings-list'>";

    foreach ($bookings as $booking) {
        if (!$booking instanceof \PTW\Models\Booking) {
            continue;
        }

        $service = $serviceRepo->GetElementById($booking->getServiceId());

        if (!$service instanceof \PTW\Models\Service) {
            continue;
        }

        $bookingsList .= "<li class='booking-item'><div class='booking-item-info'>";

        $bookingsList .= "<p class='muted'><time datetime='" . $booking->getDate()->format("Y-m-d") . "'>" . $booking->getDate()->format("d M Y") . "</time></p>";
        $bookingsList .= "<h3 class='booking-item-title'>" . $service->getName() . "<h3>";
        $bookingsList .= "<p class='booking-item-people'><span class='bold'>Persone: </span>" . $booking->getNumberOfPeople() . "</p>";

        $bookingsList .= "</div>";

        if ($booking->getDate() >= new DateTime()) {
            $bookingsList .= "<ul class='booking-item-actions'>
                    <li class='edit-action' method='GET'>
                        <form action='editBooking.php'>
                            <input type='hidden' name='id' value='" . $booking->getId() ."'>
                            <button type='submit'>Modifica</button>
                        </form>
                    </li>
                    <li class='delete-action'>
                        <form action='bookingsDelete.php' method='POST'>
                            <input type='hidden' name='booking_id' value='" . $booking->getId() ."'>
                            <button type='submit'>Elimina</button>
                        </form>
                    </li>
                </ul>";
        } else {
            $bookingsList .= "<p>Attività Completata</p>";
        }

        $bookingsList .= "</li>";

    }

    $bookingsList .= "</ul>";
}



/*


            
                    
                </div>

                
            </li>

            <li class="booking-item">
                <div class="booking-item-info">
                    <p class="muted">10 Giugno 2025</p>
                    <h3 class="booking-item-title">Safari con i leoni<h3>
                    <p class="booking-item-people"><span class="bold">Persone: </span>10</p>
                </div>

                <ul class="booking-item-actions">
                    <li class="edit-action">
                        <form action="">
                            <button type="submit">Modifica</button>
                        </form>
                    </li>
                    <li class="delete-action">
                        <form action="">
                            <button type="submit">Elimina</button>
                        </form>
                    </li>
                </ul>
            </li>

            <li class="booking-item">
                <div class="booking-item-info">
                    <p class="muted">10 Giugno 2025</p>
                    <h3 class="booking-item-title">Safari con i leoni<h3>
                    <p class="booking-item-people"><span class="bold">Persone: </span>10</p>
                </div>

                <p>Attività Completata</p>
            </li>
        </ul>

 */


$currentFile = basename(__FILE__);
$htmlContent = TemplateService::renderHtml($currentFile, [
    "[[userName]]" => $currentUser->getName(),
    "[[userBookings]]" => $bookingsList,
]);

echo $htmlContent;
?>