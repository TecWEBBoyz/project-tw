<?php
$errors = $TEMPLATE_DATA['error'] ?? [];
$fields = $TEMPLATE_DATA['form_fields'] ?? [];


$service = $_GET['service'] ?? "";

$service = $fields["service"] ?? $service;
$date = $fields["date"] ?? "";
$notes = $fields["notes"] ?? "";

?>

<form action="book-service" method="POST">
    <div>
        <?php if (array_keys($errors) === ['form']) : ?>
            <div class="error-message" role="alert" aria-live="assertive" aria-atomic="true" tabindex="-1">
                <p><?php echo $errors['form'] ?></p>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <?php if (key_exists("service", $errors)): ?>
                <div class="error-message" role="alert" aria-live="assertive" aria-atomic="true" tabindex="-1">
                    <p><?php echo $errors['service'] ?></p>
                </div>
            <?php endif; ?>

            <label class="caption" for="service" id="service-label"><?php echo PTW\translation("booking-service")?></label>
            <select id="service" name="service" required>
                <option value="" selected disabled><?php echo PTW\translation("booking-option")?></option>
                <optgroup label="<?php echo PTW\translation('booking-services')?>"> 
                    <option value="events" <?php echo ($service == "events" ? "selected" : "")?>><?php echo PTW\translation("booking-event")?></option>
                    <option value="other"  <?php echo ($service == "other" ? "selected" : "")?>><?php echo PTW\translation("booking-more")?></option>
                </optgroup>
            </select>
        </div>
        <div class="form-group">
            <?php if (key_exists("date", $errors)): ?>
                <div class="error-message" role="alert" aria-live="assertive" aria-atomic="true" tabindex="-1">
                    <p><?php echo $errors['date'] ?></p>
                </div>
            <?php endif; ?>

            <label class="caption" for="date"><?php echo PTW\translation("booking-date")?></label>
            <input type="date" class="custom-date-input" id="date" min="<?php echo date("Y-m-d"); ?>" value="<?php echo $date; ?>" name="date" required>
        </div>
        <div class="form-group">
            <?php if (key_exists("notes", $errors)): ?>
                <div class="error-message" role="alert" aria-live="assertive" aria-atomic="true" tabindex="-1">
                    <p><?php echo $errors['notes'] ?></p>
                </div>
            <?php endif; ?>

            <label class="caption" for="notes"><?php echo PTW\translation("booking-notes")?></label>
            <textarea id="notes" class="no-resize" name="notes"><?php echo $notes; ?></textarea>
        </div>

    </div>
    <button class="btn-outlined" type="submit">
        <span><?php echo PTW\translation("booking-book")?></span>
        <?php echo file_get_contents("static/images/paper-plane.svg"); ?>
    </button>
</form>