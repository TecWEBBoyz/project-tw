<?php

/**
 * About page controller
 */

namespace PTW\Controllers;

use Exception;
use PTW\Contracts\ControllerContract;
use PTW\Models\Image;
use PTW\Models\ImageType;
use PTW\Modules\Auth\Role;
use PTW\Modules\Auth\SessionManager;
use PTW\Utility\TemplateUtility;
$imageRepository = new \PTW\Modules\Repositories\ImageRepository();

class AdminController extends ControllerContract
{
    public function get(): void
    {
        if(!$this->sessionManager->authorize(Role::Administrator)) {
            $this->locationReplace('/login');
        }
        TemplateUtility::getTemplate('admin', ['title' => 'Admin Dashboard']);
    }

    public function post(): void
    {

    }

    public function justUploadedImage()
    {
        $imageRepository = new \PTW\Modules\Repositories\ImageRepository();
        $images = $imageRepository->GetJustUploadedImages();
        TemplateUtility::getTemplate('image-edit', ['title' => 'Edit Uploaded Images', 'images' => $images]);
    }
    public function uploadForm()
    {
        TemplateUtility::getTemplate('upload', ['title' => 'Upload Images']);
    }
    public function uploadImage(): void
    {
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
                $uploadDir = __DIR__ . '/../../static/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $randomImagename = uniqid();
                $ext = pathinfo($_FILES['images']['name'][0], PATHINFO_EXTENSION);
                foreach ($_FILES['images']['name'] as $key => $name) {
                    echo "Uploading file: $name<br>";
                    $tmpName = $_FILES['images']['tmp_name'][$key];
                    $error = $_FILES['images']['error'][$key];
                    $size = $_FILES['images']['size'][$key];

                    if ($error === UPLOAD_ERR_OK && $size > 0) {
                        $fileName = basename($randomImagename.'.'.$ext);
                        $fileName = strtolower($fileName);
                        $filePath = $uploadDir . $fileName;

                        if (move_uploaded_file($tmpName, $filePath)) {
                            echo "File successfully uploaded: $fileName, $filePath<br>";
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

        }
        $imageRepository = new \PTW\Modules\Repositories\ImageRepository();
        $images = $imageRepository->Create(new Image([(ImageType::path)->value => $fileName]));
    }
    public function editSingleImage($data)
    {
        $imageRepository = new \PTW\Modules\Repositories\ImageRepository();
        if(!isset($data['id'])) {
            throw new Exception("No image ID provided.");
        }
        $image = $imageRepository->GetElementByID($data['id']);
        TemplateUtility::getTemplate('image-edit', ['title' => 'Edit Image', 'images' => $image]);
    }
    public function editImage($data)
    {
        $imageRepository = new \PTW\Modules\Repositories\ImageRepository();
        if (!isset($data['id'])) {
            throw new Exception("No image ID provided.");
        }
        $image = $imageRepository->GetElementByID($data['id']);
        if($image == null) {
            echo "No image found.";
        }
        if($data['date'] == '') {
            echo "No data provided.";
            $data['date'] = 'NULL';
        }
        $image->SetData($image->FilterData($data));

        $imageRepository->Update($_POST['id'], $image);
    }

    public function deleteImage($data)
    {
        $imageRepository = new \PTW\Modules\Repositories\ImageRepository();
        $imageRepository->Delete($data['id']);
    }

    public function put(): void
    {
    }

    public function delete(): void
    {
    }
}