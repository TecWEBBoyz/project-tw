<?php
    use PTW\Utility\ToastUtility;
?>

<footer class="footer" role="contentinfo" id="footer">
    <p>© TecWebBoyz - All rights reserved</p>
    <p>Follow us on <a href="https://www.instagram.com/filippor_photography/" aria-label="Instagram profile">Instagram</a> |
        <a href="https://www.facebook.com/filippo.rizzato.716?locale=it_IT" aria-label="Facebook profile">Facebook</a></p>
</footer>
<div id="toast-container">
    <?php
    $toasts = ToastUtility::getToasts();
    foreach ($toasts as $toast): ?>
        <div class="toast <?= htmlspecialchars($toast['type']) ?>">
            <span class="icon">
                <?= $toast['type'] === 'success' ? '&#10003;' : ($toast['type'] === 'error' ? '&#10007;' : '') ?>
            </span>
            <?= htmlspecialchars($toast['message']) ?>
        </div>
    <?php endforeach; ?>
</div>