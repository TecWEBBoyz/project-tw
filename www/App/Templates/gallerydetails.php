<?php
$src = $_GET['src'] ?? '';
$description = $_GET['description'] ?? '';
$alt = $_GET['alt'] ?? '';
$location = $_GET['location'] ?? 'Unknown Location';
?>
<?php if ($src): ?>
    <img src="static/uploads/<?= htmlspecialchars($src) ?>" alt="<?= htmlspecialchars($alt) ?>">
    <div class="modal-description">
        <p><?= htmlspecialchars($description) ?></p>
        <p><strong><?php echo \PTW\translation('gallery-details-location'); ?></strong> <?= htmlspecialchars($location) ?></p>
    </div>
<?php else: ?>
    <p><?php echo \PTW\translationWithSpan('gallery-details-error') ?></p>
<?php endif; ?>

<a href="home"><?php
    echo \PTW\translation('go-homepage');
    ?></a>