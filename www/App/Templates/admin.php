<?php
$admin = $TEMPLATE_DATA['admin']['username'] ?? "";
?>

<h2><?php echo \PTW\translationWithSpan("profile-welcome") . " " . $admin; ?></h2>
<p>
<?php echo \PTW\translationWithSpan("admin-paragraph")?>
</p>

<ul>
    <li>
        <a class="link" href="admin/images"><?php echo \PTW\translationWithSpan("admin-handle-images-link")?></a>
    </li>
    <li>
        <a class="link" href="admin/bookings"><?php echo \PTW\translationWithSpan("admin-handle-bookings-link")?></a>
    </li>
</ul>


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
