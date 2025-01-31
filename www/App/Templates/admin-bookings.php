<?php

use PTW\Models\ImageCategory;
use PTW\Utility\DateFormatterUtility;

$total_images = $TEMPLATE_DATA["total_images"] ?? 0;
$page_size = $TEMPLATE_DATA["page_size"] ?? 0;
$current_page = $TEMPLATE_DATA["current_page"] ?? 0;
$left_images = $total_images - ($page_size * $current_page);
$no_category = $TEMPLATE_DATA["no_category"] ?? "";
$bookings = $TEMPLATE_DATA["bookings"] ?? [];
$status_selected = $TEMPLATE_DATA["status"] ?? "confirmed";

?>

<form method="GET" action="" id="category-filter-form">
    <div class="form-group">
        <p class="caption label"><?php echo \PTW\translation('booking-filter-by-status'); ?></p>
        <ul class="category-buttons">
            <?php
            $index = -1;
            foreach (\PTW\Models\BookingStatus::cases() as $status):
                $index++;
                $isSelected = isset($TEMPLATE_DATA['status']) && $TEMPLATE_DATA['status'] === $status->value;
                ?>
                <li>
                    <button type="submit" name="status"<?php echo $isSelected ? ' disabled="disabled" ' : ''; ?> value="<?php echo htmlspecialchars($status->value, ENT_QUOTES, 'UTF-8'); ?>"
                            class="category-button <?php echo $isSelected ? 'selected' : ''; ?>">
                        <?php echo \PTW\translation('booking-status-' . $index); ?>
                    </button>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</form>

<?php if (!empty($bookings)): ?>

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
        foreach ($bookings as $index => $booking) {

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
                        <?php if ($status === "pending"): ?>
                        <li>
                            <form action='booking/update-status' method='POST' class='form-inline'>
                                <input type='hidden' name='id' value='<?php echo $id;?>'>
                                <input type='hidden' name='status' value='confirmed'>
                                <button type='submit' aria-label="<?php echo \PTW\translation('booking-confirm') ?>"
                                        class="icon-button icon-button-primary no-icon">
                                    <?php echo \PTW\translation("booking-confirm") ?>
                                </button>
                            </form>
                        </li>
                        <?php endif; ?>
                        <?php if ($status === "confirmed" || $status === "pending"): ?>
                        <li>
                            <form action='booking/update-status' method='POST' class='form-inline confirm-form'
                                  data-action="<?php echo str_replace("{ACTION}", \PTW\translation('booking-cancel'), \PTW\translation('confirm-action')) ?>">
                                <input type='hidden' name='id' value='<?php echo $id;?>'>
                                <input type='hidden' name='status' value='cancelled'>
                                <button type='submit' aria-label="<?php echo \PTW\translation('booking-cancel') ?>"
                                        class="icon-button icon-button-danger">
                                    <?php echo file_get_contents("static/images/delete.svg"); ?>
                                    <?php echo \PTW\translation("profile-booking-table-delete") ?>
                                </button>
                            </form>
                        </li>
                        <?php endif; ?>
                        <?php if ($status === "cancelled"): ?>
                            <li><p><?php echo \PTW\translation("table-no-action") ?></p></li>
                        <?php endif; ?>
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

<p class="h2"><?php echo \PTW\translation("admin-booking-no-status-" . $status_selected) ?></p>

<?php endif; ?>

<!-- Custom Confirmation Modal -->
<div id="custom-confirm-modal" class="modal confirmation-modal" role="dialog" aria-labelledby="custom-modal-title" aria-describedby="custom-modal-message" aria-hidden="true">
    <div class="modal-content">
        <h2 id="custom-modal-title">Conferma azione</h2>
        <p id="custom-modal-message"></p>
        <div class="modal-actions">
            <button id="cancel-action" type="button" class="btn-outlined no-icon btn-secondary"><?php echo \PTW\translation('cancel'); ?></button>
            <button id="confirm-action" type="button" class="btn-outlined no-icon btn-danger"><?php echo \PTW\translation('confirm'); ?></button>
        </div>
    </div>
</div>
