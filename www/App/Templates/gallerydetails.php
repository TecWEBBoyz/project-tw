<?php
$src = $_GET['src'] ?? '';
$description = $_GET['description'] ?? '';
$alt = $_GET['alt'] ?? '';
$location = $_GET['location'] ?? 'Unknown Location';
?>
<?php if ($src): ?>
    <img src="<?= htmlspecialchars($src) ?>" alt="<?= htmlspecialchars($alt) ?>">
    <div class="modal-description">
        <p><?= htmlspecialchars($description) ?></p>
        <p><strong>Location:</strong> <?= htmlspecialchars($location) ?></p>
    </div>
<?php else: ?>
    <p>Image details not available. Please go back and select a valid image.</p>
<?php endif; ?>

<a href="home">Back to Gallery</a>