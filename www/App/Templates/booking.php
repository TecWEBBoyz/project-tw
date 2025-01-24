<?php
$error = $TEMPLATE_DATA['error'] ?? null;
?>

<form action="book-service" method="POST">

    <!-- <input type="hidden" name="userId" value="<?php echo $TEMPLATE_DATA['userId'] ?>" /> -->

    <div class="form-group">
        <label for="service" id="service-label">Servizio</label>
        <select id="service" name="service" aria-labelledby="service-label" required>
            <option value="">--Seleziona un servizio--</option>
            <option value="photoshoot">Servizio Fotografico</option>
            <option value="marriage">Matrimonio</option>
            <option value="party">Festa</option>
        </select>
    </div>
    <div class="form-group">
        <label for="date">Data</label>
        <input type="date" id="date" name="date" required>
    </div>
    <div class="form-group">
        <label for="notes">Notes</label>
        <textarea id="notes" class="no-resize" name="notes" required></textarea>
    </div>
    <div class="error-message"><?= $error ?></div>
    <button type="submit">Prenota</button>
</form>