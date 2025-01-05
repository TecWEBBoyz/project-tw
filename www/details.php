<?php
$src = $_GET['src'] ?? '';
$description = $_GET['description'] ?? '';
$alt = $_GET['alt'] ?? '';
$location = $_GET['location'] ?? 'Unknown Location';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.9);
            color: #fff;
            height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
            text-align: center;
            overflow: hidden;
        }

        img {
            max-width: 100%;
            max-height: 60%;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.6);
        }

        .modal-description {
            font-size: 18px;
            margin: 10px 0;
            word-wrap: break-word;
            overflow-y: auto;
            max-height: 30%;
        }

        a {
            margin-top: 20px;
            display: inline-block;
            padding: 10px 20px;
            background-color: #444;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            transition: background-color 0.3s;
        }

        a:hover {
            background-color: #666;
        }
    </style>
</head>
<body>
<?php if ($src): ?>
    <img src="<?= htmlspecialchars($src) ?>" alt="<?= htmlspecialchars($alt) ?>">
    <div class="modal-description">
        <p><?= htmlspecialchars($description) ?></p>
        <p><strong>Location:</strong> <?= htmlspecialchars($location) ?></p>
    </div>
<?php else: ?>
    <p>Image details not available. Please go back and select a valid image.</p>
<?php endif; ?>

<a href="/gallery.php">Back to Gallery</a>
</body>
</html>
