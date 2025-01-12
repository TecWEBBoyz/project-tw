<?php
$error = $TEMPLATE_DATA['error'] ?? null;
?>

<form action="admin/upload" method="POST" enctype="multipart/form-data" onsubmit="return validateForm(event)">
    <div id="drop-zone">
        Trascina le immagini qui oppure
        <label for="image-upload" style="color: #009578; cursor: pointer;">selezionale</label>.
        <input type="file" id="image-upload" name="images[]" accept="image/*" multiple required>
    </div>
    <div class="error-message"><?= $error ?></div>
    <button type="submit">Carica</button>
</form>