<?php
    use PTW\Utility\ToastUtility;
?>

<footer class="footer" role="contentinfo" id="footer">
    <ul>
        <li><a href="https://www.instagram.com/filippor_photography/" aria-label="Instagram Profile" class="social-link"><?php echo file_get_contents("static/images/ig.svg") ?></a></li>
        <li><a href="https://www.facebook.com/filippo.rizzato.716?locale=it_IT" aria-label="Facebook Profile" class="social-link"><?php echo file_get_contents("static/images/fb.svg") ?></a></li>
    </ul>
    <p class="copyright">© Filippo Rizzato - All rights reserved</p>
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