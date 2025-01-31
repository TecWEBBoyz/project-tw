<?php

use PTW\Utility\ToastUtility;
use function PTW\config;

if (!isset($TEMPLATE_DATA)) {
    echo "No template data found!";
    die();
}
if (!isset($TEMPLATE_DATA['name'])) {
    echo "No template name found!";
    die();
}
if (!isset($TEMPLATE_DATA['templateFileName'])) {
    echo "No template file name found!";
    die();
}

if (!file_exists(__DIR__ . "/../../Templates/$TEMPLATE_DATA[templateFileName]")) {
    throw new Exception("Template not found!");
}
isset($TEMPLATE_DATA) ?: $TEMPLATE_DATA = [];
$TEMPLATE_DATA['title'] = $TEMPLATE_DATA['title'] ?? "No Title";

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <?php require __DIR__ . "/../../Templates/core/head.php"; ?>
</head>
<body>

<?php
if (!isset($_SERVER['HTTP_REFERER'])) {
    ?>
    <div class="fullscreen-logo">
        <div class="logo-image"></div>
    </div>
    <?php
} else {
    ?>
    <div class="content-loader auto-hide">
        <div class="spinner"></div>
    </div>
    <?php
}
?>

<?php require __DIR__ . "/../../Templates/core/menu.php"; ?>

<div class="wrapper">
    <div class="content" id="content">
        <div class="container template-<?php echo $TEMPLATE_DATA['name']; ?>">
            <?php echo $breadcrumb; ?>
            <?php require __DIR__ . "/../$TEMPLATE_DATA[name].php"; ?>
        </div>
    </div>
    <!-- Footer -->
    <?php require __DIR__ . "/../../Templates/core/footer.php"; ?>

    <div id="toast-container">
        <?php
        $toasts = ToastUtility::getToasts();
        foreach ($toasts as $toast): ?>
            <div class="toast <?= htmlspecialchars($toast['type']) ?>" role="alert" aria-live="assertive" aria-atomic="true" tabindex="-1">
                <span class="icon">
                    <?= $toast['type'] === 'success' ? '&#10003;' : ($toast['type'] === 'error' ? '&#10007;' : '') ?>
                </span>
                <?= htmlspecialchars($toast['message']) ?>
                <button type="button" class="toast-close" aria-label="<?php echo \PTW\translation('toast-close'); ?>">&times;</button>
            </div>
        <?php endforeach; ?>
    </div>

</div>
<button id="scrollTopButton" role="button" aria-label="Vai al menu"><?php echo file_get_contents("static/images/up-arrow.svg"); ?></button>
</body>
</html>
