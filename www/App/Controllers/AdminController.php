<?php

/**
 * About page controller
 */

namespace PTW\Controllers;

use Exception;
use PTW\Contracts\ControllerContract;
use PTW\Models\Image;
use PTW\Models\ImageCategory;
use PTW\Models\ImageCategoryUtility;
use PTW\Models\ImageType;
use PTW\Modules\Auth\Role;
use PTW\Utility\CustomException;
use PTW\Utility\ScrollToUtility;
use PTW\Utility\TemplateUtility;
use PTW\Utility\ToastUtility;
use function PTW\Models\CheckCategory;


class AdminController extends ControllerContract
{
    private $resolutions = [
        5 => '_5percent',
        25 => '_25percent',
        50 => '_50percent',
        75 => '_75percent'
    ];
    public function get(): void
    {
        if(!$this->sessionManager->authorize(Role::Administrator)) {
            $this->locationReplace('/login');
        }
        $imageRepository = new \PTW\Modules\Repositories\ImageRepository();

        $category = $_GET['category'] ?? "Travels";
        $images = $imageRepository->GetImagesByCategory($category);
        TemplateUtility::getTemplate('admin', ['title' => 'Admin Dashboard', 'images' => $images, 'category' =>$category]);
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
        function createResizedImages($filePath, $outputDir, $fileName, $resolutions)
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
                                createResizedImages($filePath, $uploadDir, $randomImageName, $this->resolutions);

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
        try {
            $imageRepository = new \PTW\Modules\Repositories\ImageRepository();
            if(!isset($data['id'])) {
                throw new Exception("No image ID provided.");
            }
            $image = $imageRepository->GetElementByID($data['id']);

            if ($image == null) {
                throw new Exception("No image found.");
            }
            TemplateUtility::getTemplate('image-edit', array_merge(['title' => 'Edit Image', 'images' => [$image]], $templateData));
        }catch (Exception $e) {
            ScrollToUtility::setScrollTarget($data['id']);
            ToastUtility::addToast('error', \PTW\translation('image-edit-error'));
            $this->previusPage();
        }
    }
    public function editImage($data)
    {
        $numberOfImages = 0;
        try {
            $imageRepository = new \PTW\Modules\Repositories\ImageRepository();
            if (!isset($data['id'])) {
                throw new Exception("No image ID provided.");
            }
            $image = $imageRepository->GetElementByID($data['id']);
            if($image == null) {
                throw new Exception("No image found.");
            }
            if($data['date'] == '') {
                $data['date'] = 'NULL';
                throw new Exception("No data provided.");
            }
            if ($data['visible'] == 'true') {
                $data['visible'] = 1;
            } else {
                $data['visible'] = 0;
            }

            var_dump($data);

            if(!ImageCategoryUtility::CheckCategory($data['category'])) {
                throw new CustomException(\PTW\translation('image-category-invalid'));
            }

            if(!ImageCategoryUtility::CheckCategorySelected($data['category'])) {
                throw new CustomException(\PTW\translation('image-category-required'));
            }

            $image->SetData($image->FilterData($data));

            $imageRepository->Update($_POST['id'], $image);
            $images = $imageRepository->GetJustUploadedImages();
            $numberOfImages = count($images);
        }catch (CustomException $e) {
            ToastUtility::addToast('error', $e->getMessage());
        } catch (Exception $e) {
            ToastUtility::addToast('error', \PTW\translation('image-edit-error'));

        } finally {
            ScrollToUtility::setScrollTarget($data['id']);
            if ($numberOfImages > 0) {
                $this->previusPage();
            } else {
                $this->locationReplace('/admin');
            }
        }
    }

    public function editImageVisibility($data)
    {
        try {
            if(!isset($data['id'])) {
                throw new Exception("No image ID provided.");
            }
            $imageRepository = new \PTW\Modules\Repositories\ImageRepository();
            $image = $imageRepository->GetElementByID($data['id']);
            $imageArray = $image->ToArray();
            $image->SetData($image->FilterData(['visible' => $imageArray['visible'] == 1 ? 0 : 1]));
            $imageRepository->Update($data['id'], $image);
            $this->previusPage();
        }catch (Exception $e) {
            ToastUtility::addToast('error', \PTW\translation('image-edit-error'));
        } finally {
            ScrollToUtility::setScrollTarget($data['id']);
            $this->previusPage();
        }


    }

    public function deleteImage($data)
    {
        try {
            if (!isset($data['id'])) {
                throw new Exception("No image ID provided.");
            }
            $imageRepository = new \PTW\Modules\Repositories\ImageRepository();
            $image = $imageRepository->GetElementByID($data['id']);
            if($image == null) {
                throw new Exception("No image found.");
            }
            $image = $image->ToArray();

            try {
                foreach ($this->resolutions  as $scale => $suffix) {
                    $filePath = __DIR__ . '/../../static/uploads/' . pathinfo($image[ImageType::path->value], PATHINFO_FILENAME) . $suffix . '.' . strtolower(pathinfo($image[ImageType::path->value], PATHINFO_EXTENSION));
                    if (!unlink($filePath)) {
                        throw new Exception("Error deleting image.");
                    }
                }
            } catch (Exception $e) {
                throw new Exception("Error deleting image.");
            }
            $res = $imageRepository->Delete($data['id']);
            if ($res) {
                throw new Exception("Error deleting image.");
            }
            ToastUtility::addToast('success', \PTW\translation('image-deleted'));
        } catch (Exception $e) {
            ToastUtility::addToast('error', \PTW\translation('image-delete-error'));
        } finally {
            ScrollToUtility::setScrollTarget($data['id']);
            $this->locationReplace("/admin");
        }
    }

    public function reorderImage($data)
    {
        try {
            if (!isset($data['id']) || !isset($data['direction'])) {
                throw new Exception("No image ID or direction provided.");
            }
            $imageRepository = new \PTW\Modules\Repositories\ImageRepository();
            $image = $imageRepository->GetElementByID($data['id']);
            if($image == null) {
                throw new Exception("No image found.");
            }
            $nextImage = $imageRepository->GetNextImage($image->ToArray()[ImageType::order->value], $image->ToArray()[ImageType::category->value]);
            $previusImage = $imageRepository->GetPreviusImage($image->ToArray()[ImageType::order->value], $image->ToArray()[ImageType::category->value]);
            if($data['direction'] == 'up' && $previusImage != null) {
                $previusImage = $previusImage[0];
                $previusImageArray = $previusImage->ToArray();
                $imageArray = $image->ToArray();
                $image->SetData($image->FilterData([ImageType::order->value => $previusImageArray[ImageType::order->value]]));
                $previusImage->SetData($previusImage->FilterData([ImageType::order->value => $imageArray[ImageType::order->value]]));
                $imageRepository->Update($data['id'], $image);
                $imageRepository->Update($previusImageArray['id'], $previusImage);
            } else if($data['direction'] == 'down' && $nextImage != null) {
                $nextImage = $nextImage[0];
                $nextImageArray = $nextImage->ToArray();
                $imageArray = $image->ToArray();
                $image->SetData($image->FilterData([ImageType::order->value => $nextImageArray[ImageType::order->value]]));
                $nextImage->SetData($nextImage->FilterData([ImageType::order->value => $imageArray[ImageType::order->value]]));
                $imageRepository->Update($data['id'], $image);
                $imageRepository->Update($nextImageArray['id'], $nextImage);
            } else {
                throw new Exception("No image found.");
            }
        } catch (Exception $e) {
            ToastUtility::addToast('error', $e->getMessage());
        } finally {
            ScrollToUtility::setScrollTarget($data['id']);
            $this->locationReplace('/admin');
        }
    }

    public function put(): void
    {
    }

    public function delete(): void
    {
    }
}