<?php

/**
 * About page controller
 */

namespace PTW\Controllers;

use Cassandra\Varint;
use Exception;
use PTW\Contracts\ControllerContract;
use PTW\Models\Image;
use PTW\Models\ImageCategory;
use PTW\Utility\ImageCategoryUtility;
use PTW\Models\ImageType;
use PTW\Modules\Auth\Role;
use PTW\Utility\CustomException;
use PTW\Utility\ScrollToUtility;
use PTW\Utility\TemplateUtility;
use PTW\Utility\ToastUtility;
use function PTW\dd;


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

        $category = isset($_GET['category']) && $_GET["category"] != "" ? $_GET["category"] : "Travels";
        $current_page = isset($_GET['page']) && $_GET["page"] != "" && is_int((int) $_GET["page"]) ? (int) $_GET["page"] : 1;

        $count_images = $imageRepository->Count(["category" => $category]);
        $page_size = 5;
        $max_page = ceil($count_images / $page_size);

        if ($current_page <= 0) $current_page = 1;

        if ($max_page < $current_page) {
            $current_page = $max_page;
        }
        $images = $imageRepository->GetImagesByCategory($category, $current_page, $page_size);
        TemplateUtility::getTemplate('admin', [
            "images" => $images,
            "category" =>$category,
            "current_page" => $current_page,
            "total_images" => $count_images,
            "page_size" => $page_size,
            "title" => \PTW\translation('title-admin'),
            "description" => \PTW\translation('description-admin'),
            "keywords" => \PTW\translation('keywords-admin')
        ]);
    }
    public function post(): void
    {

    }

    private function checkImage(array $value, array &$errors) : bool
    {
        if (!isset($value['title']) || $value['title'] == '') {
            $errors['title'] = \PTW\translation('image-title-required');
            return false;
        }

        if (strlen($value['title']) < 2 || strlen($value['title']) > 50) {
            $errors['title'] = \PTW\translation('image-title-length');
            return false;
        }

        if (!isset($value['alt']) || $value['alt'] == '') {
            $errors['alt'] = \PTW\translation('image-alt-required');
            return false;
        }

        if (strlen($value['alt']) < 2 || strlen($value['alt']) > 100) {
            $errors['alt'] = \PTW\translation('image-alt-length');
            return false;
        }

        if (!isset($value['description']) || $value['description'] == '') {
            $errors['description'] = \PTW\translation('image-description-required');
            return false;
        }

        if (strlen($value['description']) < 2) {
            $errors['alt'] = \PTW\translation('image-description-length');
            return false;
        }

        if (!isset($value['place']) || $value['place'] == '') {
            $errors['place'] = \PTW\translation('image-place-required');
            return false;
        }

        if (strlen($value['place']) > 30) {
            $errors['place'] = \PTW\translation('image-place-length');
            return false;
        }

        if (!isset($value['date']) || $value['date'] == '') {
            $errors['date'] = \PTW\translation('image-date-required');
            return false;
        }

        if (!strtotime($value['date'])) {
            $errors['date'] = \PTW\translation('image-date-invalid');
            return false;
        }

        if (!isset($value['category']) || $value['category'] == '') {
            $errors['category'] = \PTW\translation('image-category-required');
            return false;
        }

        if (!ImageCategoryUtility::CheckCategory($value['category'])) {
            $errors['category'] = \PTW\translation('image-category-invalid');
            return false;
        }

        if(!ImageCategoryUtility::CheckCategorySelected($value['category'])) {
            $errors['category'] = \PTW\translation('image-category-invalid');
            return false;
        }

        if (!($value['visible'] == 'on' | $value['visible'] == 'off')) {
            $errors['visible'] = \PTW\translation('image-visible-invalid');
            return false;
        }

        return true;
    }

    public function justUploadedImage($data, $templateData = [])
    {
        $imageRepository = new \PTW\Modules\Repositories\ImageRepository();
        $images = $imageRepository->GetJustUploadedImages();

        if(count($images) == 0) {
            $this->locationReplace('/admin');
        }

        TemplateUtility::getTemplate('image-edit', array_merge([
            "images" => $images,
            "title" => \PTW\translation('title-upload-image'),
            "description" => \PTW\translation('description-edit-image'),
            "keywords" => \PTW\translation('keywords-edit-image'),
            "action_type" => "just-uploaded-image"
            ], $templateData));
    }
    public function uploadForm()
    {
        TemplateUtility::getTemplate('upload', [
            "title" => \PTW\translation('title-upload-image'),
            "description" => \PTW\translation('description-upload-image'),
            "keywords" => \PTW\translation('keywords-upload-image')
        ]);
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
                    return \PTW\translation('admin-image-upload-error-UPLOAD_ERR_INI_SIZE');
                case UPLOAD_ERR_FORM_SIZE:
                    return \PTW\translation('admin-image-upload-error-UPLOAD_ERR_FORM_SIZE');
                case UPLOAD_ERR_PARTIAL:
                    return \PTW\translation('admin-image-upload-error-UPLOAD_ERR_PARTIAL');
                case UPLOAD_ERR_NO_FILE:
                    return \PTW\translation('admin-image-upload-error-UPLOAD_ERR_NO_FILE');
                case UPLOAD_ERR_NO_TMP_DIR:
                    return \PTW\translation('admin-image-upload-error-UPLOAD_ERR_NO_TMP_DIR');
                case UPLOAD_ERR_CANT_WRITE:
                    return \PTW\translation('admin-image-upload-error-UPLOAD_ERR_CANT_WRITE');
                case UPLOAD_ERR_EXTENSION:
                    return \PTW\translation('admin-image-upload-error-UPLOAD_ERR_EXTENSION');
                default:
                    return \PTW\translation('admin-image-upload-error');
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
                                $error = \PTW\translation('admin-image-upload-error');
                            }
                        } else {
                            $error = uploadErrorToMessage($error);
                        }
                    }
                } else {
                    // No files uploaded
                    $error = \PTW\translation('admin-image-upload-error');
                }
            }
        } catch (Exception $e) {
            $error = \PTW\translation('admin-image-upload-error');
        }
        if ($error) {
            TemplateUtility::getTemplate('upload', [
                "title" => \PTW\translation('title-upload-image'),
                "description" => \PTW\translation('description-upload-image'),
                "keywords" => \PTW\translation('keywords-upload-image'),
                "error" => $error]);
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
                throw new Exception(\PTW\translation('admin-image-no-id'));
            }
            $image = $imageRepository->GetElementByID($data['id']);

            if ($image == null) {
                throw new Exception(\PTW\translation('admin-image-not-found'));
            }

            TemplateUtility::getTemplate('image-edit', array_merge([
                "title" => \PTW\translation('title-edit-image'),
                "description" => \PTW\translation('description-edit-image'),
                "keywords" => \PTW\translation('keywords-edit-image'),
                "images" => [$image],
                "action_type" => "single-image"], $templateData));

        }catch (Exception $e) {
            ScrollToUtility::setScrollTarget($data['id']);
            ToastUtility::addToast('error', \PTW\translation('image-edit-error'));
            $this->previusPage();
        }
    }
    public function editImage($data)
    {
        $imageRepository = new \PTW\Modules\Repositories\ImageRepository();

        $values = [];
        $errors = [];

        $values['id'] = trim($data['id']);
        $values['title'] = trim($data['title']);
        $values['alt'] = trim($data['alt']);
        $values['description'] = trim($data['description']);
        $values['category'] = trim($data['category']);
        $values['place'] = trim($data['place']);
        $values['date'] = trim($data['date']);
        $values['visible'] = trim($data['visible'] ?? 'off');
        $values['action-type'] = trim($data['action-type']);

        $fields = $values;

        if (!$this->checkImage($values, $errors))
        {
            $singleImage = $imageRepository->GetElementByID($values['id']);
            TemplateUtility::getTemplate('image-edit', [
                "error" => $errors,
                "form_fields" => $fields,
                "images" => $values["action-type"] == "single-image" ? [$singleImage] : $imageRepository->GetJustUploadedImages(),
                "title"=>\PTW\translation('title-image-edit'),
                "description"=>\PTW\translation('description-image-edit'),
                "keywords"=>\PTW\translation('keywords-image-edit'),
                "action_type" => $values['action-type']]);
            return;
        }

        $numberOfImages = 0;
        try {
            if (!isset($values['id'])) {
                throw new Exception(\PTW\translation('admin-image-no-id'));
            }

            if (!($values['action-type'] == 'single-image' || $values['action-type'] == 'just-uploaded-image')) {
                throw new Exception(\PTW\translation('admin-action-not-recognized'));
            }

            $image = $imageRepository->GetElementByID($values['id']);

            if($image == null) {
                throw new Exception(\PTW\translation('admin-image-not-found'));
            }

            $values['visible'] = $values['visible'] == 'on' ? 1 : 0;

            $image->SetData($image->FilterData($values));

            $imageRepository->Update($values['id'], $image);

            $images = [];

            if ($values['action-type'] == 'just-uploaded-image') {
                $images = $imageRepository->GetJustUploadedImages();
            }

            $numberOfImages = count($images);

        }catch (CustomException $e) {
            ToastUtility::addToast('error', $e->getMessage());
        } catch (Exception $e) {
            ToastUtility::addToast('error', \PTW\translation('image-edit-error'));

        } finally {
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
                throw new Exception(\PTW\translation('admin-image-no-id'));
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
            $this->locationReplace("/admin");
        }
    }

    public function reorderImage($data)
    {
        try {
            if (!isset($data['id']) || !isset($data['direction'])) {
                throw new Exception(\PTW\translation('admin-image-no-id-or-direction-provided'));
            }

            $params = "";
            if (isset($data['page']) || isset($data['category'])) {
                $params .= "?";
                $params .= (isset($data['category']) ? "category=" . $data['category'] : "");
                $params .= (isset($data['page']) ? ($params == "?" ? "" : "&") . "page=" . $data['page'] : "");
            }

            $imageRepository = new \PTW\Modules\Repositories\ImageRepository();
            $image = $imageRepository->GetElementByID($data['id']);
            if($image == null) {
                throw new Exception(\PTW\translation('admin-image-not-found'));
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
                throw new Exception(\PTW\translation('admin-image-not-found'));
            }
        } catch (Exception $e) {
            ToastUtility::addToast('error', $e->getMessage());
        } finally {
            ScrollToUtility::setScrollTarget($data['id']);
            $this->locationReplace('/admin' . $params);
        }
    }

    public function put(): void
    {
    }

    public function delete(): void
    {
    }
}