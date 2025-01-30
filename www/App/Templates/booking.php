<?php
$errors = $TEMPLATE_DATA['error'] ?? [];
$fields = $TEMPLATE_DATA['form_fields'] ?? [];

$service = $fields["service"] ?? "";
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

            <label class="caption" for="service" id="service-label">Servizio</label>
            <select id="service" name="service" aria-labelledby="service-label" required>
                <option value="" selected disabled>-- Select an option --</option>
                <optgroup label="Services">
                    <option value="events" <?php echo ($service == "events" ? "selected" : "")?>>Evento / Concerto</option>
                    <option value="other"  <?php echo ($service == "other" ? "selected" : "")?>>Altro</option>
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
            <input type="date" class="custom-date-input" id="date" value="<?php echo $date ? $date : "" ?>" name="date" required>
        </div>
        <div class="form-group">
            <?php if (key_exists("notes", $errors)): ?>
                <div class="error-message" role="alert" aria-live="assertive" aria-atomic="true" tabindex="-1">
                    <p><?php echo $errors['notes'] ?></p>
                </div>
            <?php endif; ?>

            <label class="caption" for="notes">Notes</label>
            <textarea id="notes" class="no-resize" name="notes"><?php echo $notes ? $notes : ""?></textarea>
        </div>

    </div>
    <button class="btn-outlined" type="submit">
        <span>Book</span>
        <?php echo file_get_contents("static/images/paper-plane.svg"); ?>
    </button>
</form>