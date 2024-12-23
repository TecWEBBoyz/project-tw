<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Images</title>
</head>
<body>
<h1>Upload Images</h1>
<form action="index.php" method="post" enctype="multipart/form-data">
    <input type="file" name="images[]" accept="image/*" multiple required>
    <button type="submit">Upload</button>
</form>
</body>
</html>
<?php

function createResizedImages($filePath, $outputDir, $fileName) {
    $imageInfo = getimagesize($filePath);
    $originalWidth = $imageInfo[0];
    $originalHeight = $imageInfo[1];
    $mimeType = $imageInfo['mime'];
    switch ($mimeType) {
        case 'image/jpeg':
            $sourceImage = imagecreatefromjpeg($filePath);
            break;
        case 'image/png':
            $sourceImage = imagecreatefrompng($filePath);
            break;
        default:
            echo "Unsupported image type for $fileName.<br>";
            return;
    }

    $resolutions = [
        5 => '_5percent',
        25 => '_25percent',
        50 => '_50percent',
        75 => '_75percent'
    ];

    foreach ($resolutions as $scale => $suffix) {
        $scale = $scale / 100;
        $newWidth = (int) round($originalWidth * $scale);
        $newHeight = (int) round($originalHeight * $scale);
        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($resizedImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

        $newFilePath = $outputDir . '/' . pathinfo($fileName, PATHINFO_FILENAME) . $suffix . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
        switch ($mimeType) {
            case 'image/jpeg':
                imagejpeg($resizedImage, $newFilePath);
                break;
            case 'image/png':
                imagepng($resizedImage, $newFilePath);
                break;
        }
        imagedestroy($resizedImage);
        echo "Resized image saved: " . basename($newFilePath) . "<br>";
    }
    imagedestroy($sourceImage);
}

function uploadErrorToMessage($errorCode) {
    switch ($errorCode) {
        case UPLOAD_ERR_INI_SIZE:
            return "The uploaded file exceeds the upload_max_filesize directive in php.ini.";
        case UPLOAD_ERR_FORM_SIZE:
            return "The uploaded file exceeds the MAX_FILE_SIZE directive in the HTML form.";
        case UPLOAD_ERR_PARTIAL:
            return "The uploaded file was only partially uploaded.";
        case UPLOAD_ERR_NO_FILE:
            return "No file was uploaded.";
        case UPLOAD_ERR_NO_TMP_DIR:
            return "Missing a temporary folder.";
        case UPLOAD_ERR_CANT_WRITE:
            return "Failed to write file to disk.";
        case UPLOAD_ERR_EXTENSION:
            return "A PHP extension stopped the file upload.";
        default:
            return "Unknown upload error.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['images'])) {
        $uploadDir = __DIR__ . '/static/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        var_dump($_FILES['images']['name']);
        foreach ($_FILES['images']['name'] as $key => $name) {
            echo "Uploading file: $name<br>";
            $tmpName = $_FILES['images']['tmp_name'][$key];
            $error = $_FILES['images']['error'][$key];
            $size = $_FILES['images']['size'][$key];

            if ($error === UPLOAD_ERR_OK && $size > 0) {
                $fileName = basename($name);
                $fileName = strtolower($fileName);
                $filePath = $uploadDir . $fileName;

                if (move_uploaded_file($tmpName, $filePath)) {
                    echo "File successfully uploaded: $fileName<br>";
                    createResizedImages($filePath, $uploadDir, $fileName);
                } else {
                    echo "Error moving uploaded file: $fileName<br>";
                }
            } else {
                echo "Error uploading file $name: " . uploadErrorToMessage($error) . "<br>";
            }
        }
    } else {
        echo "No files uploaded. Please select files.";
    }

    echo '<br><br>';
    echo '<a href="index.html">Go back</a>';
}
?>

