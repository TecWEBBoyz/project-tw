<?php
    $error = $TEMPLATE_DATA['error'] ?? null;
?>

<p class="h1 text-center"><?php echo PTW\translationWithSpan('login-title') ?></p>

<form action="login" method="POST">
    <div class="form-group">
        <label class="caption" for="username"><?php echo PTW\translationWithSpan('login-username') ?></label>
        <input type="text" id="username" name="username" placeholder="Mario Rossi" required>
    </div>
    <div class="form-group">
        <label class="caption" for="password"><?php echo PTW\translationWithSpan('login-password') ?></label>
        <input type="password" id="password" name="password" placeholder="Password" required>
    </div>
    <!-- Mostra l'errore solo se presente -->
    <?php if ($error): ?>
        <div class="error-message">
            <span role="alert" aria-live="assertive" aria-relevant="additions" aria-atomic="true"><?= $error ?></span>
        </div>
    <?php endif; ?>
    <button class="btn-outlined" type="submit">
        <span><?php echo PTW\translationWithSpan('login-button') ?></span>
        <?php echo file_get_contents("static/images/paper-plane.svg"); ?>
    </button>
</form>