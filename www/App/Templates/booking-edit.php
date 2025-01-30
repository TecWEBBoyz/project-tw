<?php

use PTW\Models\BookingType;

$errors = $TEMPLATE_DATA['error'] ?? [];

if (is_null($TEMPLATE_DATA["booking"])) {
    echo "<p>" . PTW\translation('no-images') . "</p>";
    echo "<a href='admin/upload-form' class='button'>" . PTW\translation('upload-image') . "</a>";
    return;
}

$booking = $TEMPLATE_DATA["booking"];
$bookingArray = $booking->ToArray();

$id = htmlspecialchars($bookingArray[BookingType::id->value] ?? '');
$user = htmlspecialchars($bookingArray[BookingType::user->value] ?? '');
$date = htmlspecialchars($bookingArray[BookingType::date->value] ?? '');
$status = htmlspecialchars($bookingArray[BookingType::status->value] ?? '');
$service = htmlspecialchars($bookingArray[BookingType::service->value] ?? '');
$notes = htmlspecialchars($bookingArray[BookingType::notes->value] ?? '');

?>

<form action="book-service/update-booking" method="POST">

    <div>
        <?php if (array_keys($errors) === ['form']) : ?>
            <div class="error-message" role="alert" aria-live="assertive" aria-atomic="true" tabindex="-1">
                <p><?php echo $errors['form'] ?></p>
            </div>
        <?php endif; ?>

        <input type="hidden" name="id" value="<?php echo $id; ?>">

        <div class="form-group">
            <?php if (key_exists("service", $errors)): ?>
                <div class="error-message" role="alert" aria-live="assertive" aria-atomic="true" tabindex="-1">
                    <p><?php echo $errors['service'] ?></p>
                </div>
            <?php endif; ?>

            <label class="caption" for="service" id="service-label">Servizio</label>
            <select id="service" name="service" aria-labelledby="service-label" required>
                <optgroup label="Services">
                    <option value="events" <?php echo $service == "events" ? "selected" : ""; ?>>Evento / Concerto</option>
                    <option value="other" <?php echo $service == "other" ? "selected" : ""; ?>>Altro</option>
                </optgroup>
            </select>
        </div>
        <div class="form-group">
            <?php if (key_exists("date", $errors)): ?>
                <div class="error-message" role="alert" aria-live="assertive" aria-atomic="true" tabindex="-1">
                    <p><?php echo $errors['date'] ?></p>
                </div>
            <?php endif; ?>

            <label class="caption" for="date">Data</label>
            <input type="date" class="custom-date-input" id="date" name="date" value="<?php echo $date; ?>" required>
        </div>
        <div class="form-group">
            <?php if (key_exists("notes", $errors)): ?>
                <div class="error-message" role="alert" aria-live="assertive" aria-atomic="true" tabindex="-1">
                    <p><?php echo $errors['notes'] ?></p>
                </div>
            <?php endif; ?>

            <label class="caption" for="notes">Notes</label>
            <textarea id="notes" class="no-resize" name="notes" required><?php echo $notes; ?></textarea>
        </div>
    </div>

    <button class='btn-outlined btn-image-edit' type='submit'>
        <span><?php echo PTW\translation('booking-save') ?></span>
        <?php echo file_get_contents("static/images/paper-plane.svg") ?>
    </button>
</form>

<form action='profile/cancel-booking' method='POST'>
    <input type='hidden' name='id' value="<?php echo $id ?>">
    <button class='btn-outlined btn-image-edit btn-danger' type='submit'>
        <span><?php echo PTW\translation('booking-delete') ?></span>
        <?php echo file_get_contents("static/images/delete.svg") ?>
    </button>
</form>

<?php if (empty($booking)) : ?>
    <p><?php echo PTW\translation('booking-edit-no-booking-to-edit') ?></p>
<?php endif; ?>
