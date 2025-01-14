<?php
$error = $TEMPLATE_DATA['error'] ?? null;
?>

<form action="admin/upload" method="POST" enctype="multipart/form-data" onsubmit="return validateForm(event)">
    <div id="drop-zone">
        <?php echo \PTW\translation('image-drop-files-here') ?>
        <label for="image-upload" style="color: #009578; cursor: pointer;"><?php echo \PTW\translation('image-select-files') ?></label>.
        <input type="file" id="image-upload" name="images[]" accept="image/*" multiple required>
    </div>
    <div class="error-message"><?= $error ?></div>
    <button type="submit"><?php echo \PTW\translation('image-select-files') ?></button>
</form>