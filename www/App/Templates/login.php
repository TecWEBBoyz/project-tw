<?php
    $error = $TEMPLATE_DATA['error'] ?? null;
?>

<form action="login" method="POST">
    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="Enter your username" required>
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" required>
    </div>
    <div class="error-message"><?= $error ?></div>
    <button type="submit">Login</button>
</form>