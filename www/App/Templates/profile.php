<?php

use PTW\Models\Booking;
use PTW\Models\UserType;

if (!isset($TEMPLATE_DATA)) {
    throw new Exception("La variabile \$TEMPLATE_DATA non esiste");
}

?>

<h2>Welcome <?php echo $TEMPLATE_DATA["user"]["username"] ?></h2>

<article>
    <header>
        <p class="caption">/services</p>
        <h3>Booked Service</h3>
    </header>

    <?php if (isset($TEMPLATE_DATA["bookings"]) && count($TEMPLATE_DATA["bookings"]) > 0) : ?>

        <div class="table-responsive">
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Service</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php
                /** @var Booking $booking */
                foreach ($TEMPLATE_DATA["bookings"] as $index => $booking) {
                    ?>

                    <tr>
                        <td data-label="id"><?php echo $index + 1; ?></td>
                        <td data-label="service"><?php echo $booking->ToArray()[\PTW\Models\BookingType::service->value] ?></td>
                        <td data-label="date"><?php echo $booking->ToArray()[\PTW\Models\BookingType::date->value] ?></td>
                        <td data-label="status" class="status cancelled"><span><?php echo $booking->ToArray()[\PTW\Models\BookingType::status->value] ?></span></td>
                        <td data-label="actions" class="actions">
                            <ul>
                                <li>
                                    <button class="icon-button icon-button-primary">
                                        <?php echo file_get_contents("static/images/edit.svg"); ?>
                                        <span>Edit</span>
                                    </button>
                                </li>
                                <li>
                                    <button class="icon-button icon-button-danger">
                                        <?php echo file_get_contents("static/images/delete.svg"); ?>
                                        <span>Delete</span>
                                    </button>
                                </li>
                            </ul>
                        </td>
                    </tr>

                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>

        <p>Non è stato ancora fissato un appuntamento:</p>
        <a href="book-service">Prenota ora un appuntamento</a>

    <?php endif; ?>


</article>