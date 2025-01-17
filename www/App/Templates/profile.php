<?php

use PTW\Models\Booking;
use PTW\Models\UserType;

if (!isset($TEMPLATE_DATA)) {
    throw new Exception("La variabile \$TEMPLATE_DATA non esiste");
}

?>

<h2>Accesso effettuato come: <?php echo $TEMPLATE_DATA["user"]["username"] ?></h2>

<h2>Appuntamenti Fissati</h2>


<?php

if (isset($TEMPLATE_DATA["bookings"]) && count($TEMPLATE_DATA["bookings"]) > 0) {
    ?>
    <div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>Servizio</th>
                <th>Data</th>
                <th>Stato</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php
            /** @var Booking $booking */
            foreach ($TEMPLATE_DATA["bookings"] as $booking) {
                ?>

                <tr>
                <td><?php echo $booking->ToArray()[\PTW\Models\BookingType::service->value] ?></td>
                <td><?php echo $booking->ToArray()[\PTW\Models\BookingType::date->value] ?></td>
                <td><?php echo $booking->ToArray()[\PTW\Models\BookingType::status->value] ?></td>
                <td>
                <a href="delete">Delete</a>
                </td>
                </tr>

                <?php
            }
            ?>
        </tbody>
    </table>
    </div>
    <?php
} else {
    ?>

    <p>Non è stato ancora fissato un appuntamento:</p>
    <a href="book-service">Prenota ora un appuntamento</a>

    <?php
}

?>