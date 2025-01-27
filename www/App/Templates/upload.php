<?php
$error = $TEMPLATE_DATA['error'] ?? null;
?>

<form action="admin/upload" method="POST" enctype="multipart/form-data" onsubmit="return validateForm(event)">
    <div class="form-group">
        <label for="image-upload"><?php echo \PTW\translation('image-select-files') ?></label>
        <div id="drop-zone">
            <span><?php echo \PTW\translation('image-drop-files-here') ?></span>
            <input type="file" id="image-upload" name="images[]" accept="image/*" multiple required>
        </div>
    </div>
    <div class="error-message"><?= $error ?></div>
    <button class="btn-outlined" type="submit">
        <span><?php echo \PTW\translation('image-upload')?></span>
        <?php echo file_get_contents("static/images/paper-plane.svg"); ?>
    </button>
</form>