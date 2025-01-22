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
use PTW\Utility\ToastUtility;

class AdminController extends ControllerContract
{
    public function get(): void
    {
        if(!$this->sessionManager->authorize(Role::Administrator)) {
            $this->locationReplace('/login');
        }
        $imageRepository = new \PTW\Modules\Repositories\ImageRepository();
        $images = $imageRepository->All();
        TemplateUtility::getTemplate('admin', ['title' => 'Admin Dashboard', 'images' => $images]);
    }

    public function post(): void
    {

    }

    public function justUploadedImage($data, $templateData = [])
    {
        $imageRepository = new \PTW\Modules\Repositories\ImageRepository();
        $images = $imageRepository->GetJustUploadedImages();
        if(count($images) == 0) {
            $this->locationReplace('/admin');
        }
        TemplateUtility::getTemplate('image-edit', array_merge(['title' => 'Edit Uploaded Images', 'images' => $images], $templateData));
    }
    public function uploadForm()
    {
        TemplateUtility::getTemplate('upload', ['title' => 'Upload Images']);
    }
    public function uploadImage(): void
    {
        $error = null;
        function createResizedImages($filePath, $outputDir, $fileName)
        {
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
                    $error = "Unsupported image type $mimeType.";
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
            }
            imagedestroy($sourceImage);
        }

        function uploadErrorToMessage($errorCode)
        {
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
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_FILES['images'])) {
                    $uploadDir = __DIR__ . '/../../static/uploads/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    foreach ($_FILES['images']['name'] as $key => $name) {
                        $tmpName = $_FILES['images']['tmp_name'][$key];
                        $error = $_FILES['images']['error'][$key];
                        $size = $_FILES['images']['size'][$key];

                        if ($error === UPLOAD_ERR_OK && $size > 0) {
                            $ext = pathinfo($name, PATHINFO_EXTENSION);
                            $randomImageName = uniqid() . '.' . strtolower($ext);
                            $filePath = $uploadDir . $randomImageName;

                            if (move_uploaded_file($tmpName, $filePath)) {
                                createResizedImages($filePath, $uploadDir, $randomImageName);

                                $imageRepository = new \PTW\Modules\Repositories\ImageRepository();
                                $imageRepository->Create(new Image([(ImageType::path)->value => $randomImageName]));
                            } else {
                                $error = "Error moving uploaded file.";
                            }
                        } else {
                            $error = uploadErrorToMessage($error);
                        }
                    }
                } else {
                    $error = "No images uploaded.";
                }
            }
        } catch (Exception $e) {
            $error = "Error uploading images.";
        }
        if ($error) {
            TemplateUtility::getTemplate('upload', ['title' => 'Upload Images', 'error' => $error]);
        } else {
            ToastUtility::addToast('success', \PTW\translation('image-uploaded'));
            $this->locationReplace('/admin/justuploadedimage');
        }
    }

    public function editSingleImage($data, $templateData = [])
    {
        $imageRepository = new \PTW\Modules\Repositories\ImageRepository();
        if(!isset($data['id'])) {
            throw new Exception("No image ID provided.");
        }
        $image = $imageRepository->GetElementByID($data['id']);
        TemplateUtility::getTemplate('image-edit', array_merge(['title' => 'Edit Image', 'images' => [$image]], $templateData));
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
        if ($data['visible'] == 'true') {
            $data['visible'] = 1;
        } else {
            $data['visible'] = 0;
        }
        $image->SetData($image->FilterData($data));

        $imageRepository->Update($_POST['id'], $image);
        $images = $imageRepository->GetJustUploadedImages();
        $numberOfImages = count($images);
        if ($numberOfImages > 0) {
            $this->previusPage();
        } else {
            $this->locationReplace('/admin');
        }
    }

    public function editImageVisibility($data)
    {
        if(!isset($data['id'])) {
            throw new Exception("No image ID provided.");
        }
        $imageRepository = new \PTW\Modules\Repositories\ImageRepository();
        $image = $imageRepository->GetElementByID($data['id']);
        $imageArray = $image->ToArray();
        $image->SetData($image->FilterData(['visible' => $imageArray['visible'] == 1 ? 0 : 1]));
        $imageRepository->Update($data['id'], $image);
        $this->previusPage();
    }

    public function deleteImage($data)
    {
        try {
            if (!isset($data['id'])) {
                throw new Exception("No image ID provided.");
            }
            $imageRepository = new \PTW\Modules\Repositories\ImageRepository();
            $res = $imageRepository->Delete($data['id']);
            if ($res) {
                throw new Exception("Error deleting image.");
            }
            ToastUtility::addToast('success', \PTW\translation('image-deleted'));
        } catch (Exception $e) {
            ToastUtility::addToast('error', 'Errore durante l\'eliminazione dell\'immagine.');
        }
        $this->previusPage();
    }

    public function put(): void
    {
    }

    public function delete(): void
    {
    }
}