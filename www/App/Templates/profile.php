<?php

use PTW\Models\Booking;
use PTW\Models\UserType;

if (!isset($TEMPLATE_DATA)) {
    throw new Exception("La variabile \$TEMPLATE_DATA non esiste");
}

?>

<h2><?php echo \PTW\translation("profile-welcome")?> <?php echo $TEMPLATE_DATA["user"]["username"] ?></h2>

<article>
    <header>
        <p class="caption"><?php echo \PTW\translation("profile-caption")?></p>
        <h3><?php echo \PTW\translation("profile-book")?></h3>
    </header>

    <?php if (isset($TEMPLATE_DATA["bookings"]) && count($TEMPLATE_DATA["bookings"]) > 0) : ?>

        <div class="table-responsive">
            <table>
                <thead>
                <tr>
                    <th><?php echo \PTW\translation("service-ID") ?></th>
                    <th><?php echo \PTW\translation("service-service") ?></th>
                    <th><?php echo \PTW\translation("service-date") ?></th>
                    <th><?php echo \PTW\translation("service-status") ?></th>
                    <th><?php echo \PTW\translation("service-actions") ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                /** @var Booking $booking */
                foreach ($TEMPLATE_DATA["bookings"] as $index => $booking) {

                    $id = $booking->ToArray()[\PTW\Models\BookingType::id->value];
                    $service = $booking->ToArray()[\PTW\Models\BookingType::service->value];
                    $status = $booking->ToArray()[\PTW\Models\BookingType::status->value];
                    $date = $booking->ToArray()[\PTW\Models\BookingType::date->value];

                    ?>

                    <tr>
                        <td data-label="<?php echo \PTW\translation("service-ID") ?>"><?php echo $index + 1; ?></td>
                        <td data-label="<?php echo \PTW\translation("service-service") ?>"><?php echo $service; ?></td>
                        <td data-label="<?php echo \PTW\translation("service-date") ?>"><time datetime="<?php echo $date?>"><?php echo \PTW\Utility\DateFormatterUtility::Format($date) ?></time></td>
                        <td data-label="<?php echo \PTW\translation("service-status") ?>" class="status <?php echo $status; ?>"><?php echo \PTW\translation("service-" . $booking->ToArray()[\PTW\Models\BookingType::status->value]) ?></td>
                        <td data-label="<?php echo \PTW\translation("service-actions") ?>" class="actions">
                            <ul>
                                <li>
                                    <form action='profile/edit-booking' method='GET' class='form-inline'>
                                        <input type='hidden' name='id' value='<?php echo $id;?>'>
                                        <button type='submit' aria-label="<?php echo \PTW\translation('booking-edit') ?>"
                                                class="icon-button icon-button-primary">
                                            <?php echo file_get_contents("static/images/edit.svg"); ?>
                                            <?php echo \PTW\translation("profile-booking-table-edit") ?>
                                        </button>
                                    </form>
                                </li>
                                <li>
                                    <form action='profile/cancel-booking' method='POST' class='form-inline confirm-form'
                                          data-action="<?php echo str_replace("{ACTION}", \PTW\translation('booking-delete'), \PTW\translation('confirm-action')) ?>">
                                        <input type='hidden' name='id' value='<?php echo $id;?>'>
                                        <button type='submit' aria-label="<?php echo \PTW\translation('booking-delete') ?>"
                                                class="icon-button icon-button-danger">
                                            <?php echo file_get_contents("static/images/delete.svg"); ?>
                                            <?php echo \PTW\translation("profile-booking-table-delete") ?>
                                        </button>
                                    </form>
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

        <p><?php echo \PTW\translation("profile-booking-table-no-bookings") ?></p>
        <a href="book-service" class="btn-outlined" id="profile-book-button">
            <?php echo \PTW\translation("profile-booking-table-no-bookings-link") ?>
            <?php echo file_get_contents("static/images/right-chevron.svg") ?>
        </a>

    <?php endif; ?>


</article>