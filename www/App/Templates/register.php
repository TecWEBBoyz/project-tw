<?php
$errors = $TEMPLATE_DATA['errors'] ?? [];
?>
<form action="register" method="POST">
    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="Enter your username" required>
        <?php if (!empty($errors['username'])): ?>
            <div class="error-message"><?= $errors['username'] ?></div>
        <?php endif; ?>
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Enter your email" required>
        <?php if (!empty($errors['email'])): ?>
            <div class="error-message"><?= $errors['email'] ?></div>
        <?php endif; ?>
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" required>
        <?php if (!empty($errors['password'])): ?>
            <div class="error-message"><?= $errors['password'] ?></div>
        <?php endif; ?>
    </div>
    <div class="form-group">
        <label for="confirm-password">Confirm Password</label>
        <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm your password" required>
        <?php if (!empty($errors['confirm-password'])): ?>
            <div class="error-message"><?= $errors['confirm-password'] ?></div>
        <?php endif; ?>
    </div>
    <div class="form-group inline">
        <input type="checkbox" id="accept-terms" name="accept-terms" required>
        <label for="accept-terms">I accept the <a href="/terms" target="_blank">terms and conditions</a>.</label>
        <?php if (!empty($errors['accept-terms'])): ?>
            <div class="error-message"><?= $errors['accept-terms'] ?></div>
        <?php endif; ?>
    </div>
    <button type="submit">Register</button>
</form>
