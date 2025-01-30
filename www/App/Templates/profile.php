<?php

use PTW\Models\Booking;
use PTW\Models\UserType;

if (!isset($TEMPLATE_DATA)) {
    throw new Exception("La variabile \$TEMPLATE_DATA non esiste");
}

?>

<h2><?php echo \PTW\translationWithSpan("profile-welcome")?> <?php echo $TEMPLATE_DATA["user"]["username"] ?></h2>

<article>
    <header>
        <p class="caption"><?php echo \PTW\translationWithSpan("profile-caption")?></p>
        <h3><?php echo \PTW\translationWithSpan("profile-book")?></h3>
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
                                        <?php echo \PTW\translationWithSpan("profile-booking-table-edit") ?>
                                    </button>
                                </li>
                                <li>
                                    <button class="icon-button icon-button-danger">
                                        <?php echo file_get_contents("static/images/delete.svg"); ?>
                                        <?php echo \PTW\translationWithSpan("profile-booking-table-delete") ?>
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

        <p><?php echo \PTW\translationWithSpan("profile-booking-table-no-bookings") ?></p>
        <a href="book-service" class="btn-outlined" id="profile-book-button">
            <?php echo \PTW\translationWithSpan("profile-booking-table-no-bookings-link") ?>
            <?php echo file_get_contents("static/images/right-chevron.svg") ?>
        </a>

    <?php endif; ?>


</article>