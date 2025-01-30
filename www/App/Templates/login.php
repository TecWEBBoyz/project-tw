<?php
    $errors = $TEMPLATE_DATA['error'] ?? [];
    $fields = $TEMPLATE_DATA['form_fields'] ?? [];

    $username = $fields["username"] ?? "";
?>

<p class="h1 text-center"><?php echo PTW\translationWithSpan('login-title') ?></p>

<form action="login" method="POST">
    <div>
        <?php if (array_keys($errors) === ['form']) : ?>
            <div class="error-message" role="alert" aria-live="assertive" aria-atomic="true" tabindex="-1">
                <p><?php echo $errors['form'] ?></p>
            </div>
        <?php endif; ?>

    <div class="form-group">
        <?php if (key_exists("username", $errors)): ?>
            <div class="error-message" role="alert" aria-live="assertive" aria-atomic="true" tabindex="-1">
                <p><?php echo $errors['username'] ?></p>
            </div>
        <?php endif; ?>

        <label class="caption" for="username"><?php echo PTW\translationWithSpan('login-username'); ?></label>
        <input type="text" id="username" name="username" placeholder="<?php echo PTW\translation('login-username'); ?>" value="<?php echo $username; ?>" <?php echo key_exists("username", $errors) ? 'class="error"' : '' ?>>
    </div>

    <div class="form-group">
        <?php if (key_exists("password", $errors)): ?>
            <div class="error-message" role="alert" aria-live="assertive" aria-atomic="true" tabindex="-1">
                <p><?php echo $errors['password'] ?></p>
            </div>
        <?php endif; ?>

        <label class="caption" for="password"><?php echo PTW\translationWithSpan('login-password') ?></label>
        <input type="password" id="password" name="password" placeholder="Password" <?php echo key_exists("password", $errors) ? 'class="error"': '' ?>>
    </div>
    </div>

    <button class="btn-outlined" type="submit">
        <span><?php echo PTW\translationWithSpan('login-button') ?></span>
        <?php echo file_get_contents("static/images/paper-plane.svg"); ?>
    </button>
</form>