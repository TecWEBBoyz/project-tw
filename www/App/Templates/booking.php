<?php
$error = $TEMPLATE_DATA['error'] ?? null;
?>

<form action="book-service" method="POST">

    <!-- <input type="hidden" name="userId" value="<?php echo $TEMPLATE_DATA['userId'] ?>" /> -->

    <div class="form-group">
        <label class="caption" for="service" id="service-label">Servizio</label>
        <select id="service" name="service" aria-labelledby="service-label" required>
            <option value="" selected disabled>-- Select an option --</option>
            <optgroup label="Services">
                <option value="events">Evento / Concerto</option>
                <option value="other">Altro</option>
            </optgroup>
        </select>
    </div>
    <div class="form-group">
        <label class="caption" for="date">Data</label>
        <input type="date" class="custom-date-input" id="date" name="date" required>
    </div>
    <div class="form-group">
        <label class="caption" for="notes">Notes</label>
        <textarea id="notes" class="no-resize" name="notes" required></textarea>
    </div>
    <div class="error-message"><?= $error ?></div>
    <button class="btn-outlined" type="submit">
        <span>Book</span>
        <?php echo file_get_contents("static/images/paper-plane.svg"); ?>
    </button>
</form>