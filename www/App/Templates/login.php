<?php
    $error = $TEMPLATE_DATA['error'] ?? null;
?>

<p class="h1 text-center">Bentornato!</p>

<form action="login" method="POST">
    <div class="form-group">
        <label class="caption" for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="Mario Rossi" required>
    </div>
    <div class="form-group">
        <label class="caption" for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Password" required>
    </div>
    <div class="error-message"><?= $error ?></div>
    <button class="btn-outlined" type="submit">
        <span>Login</span>
        <?php echo file_get_contents("static/images/paper-plane.svg"); ?>
    </button>
</form>