<?php
require_once 'init.php';

session_start();

use \PTW\Repositories\ReviewRepository;
use \PTW\Models\Review;
use \PTW\Services\AuthService;
use \PTW\Services\TemplateService;

$reviewRepo = new ReviewRepository();


// Check authentication before allowing access
if (!AuthService::isUserLoggedIn()) {
    // Salva l'URL corrente nella sessione
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php?error=unauthorized');
    exit;
}

// Get current user
$currentUser = AuthService::getCurrentUser();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    var_dump($_POST);
    
    // Check if all fields (except for user_id are present)
    if (!array_key_exists('id', $_POST)
        || !array_key_exists('rating', $_POST) 
        || !array_key_exists('comment', $_POST)) {
        
        http_response_code(400);
        echo json_encode(["message" => "Missing data field."]);
        exit();
    }

    $review = $reviewRepo->GetElementByID($_POST["id"]);

    if (!$review instanceof Review) {
        http_response_code(404);
        exit();
    }
    
    // Update review
    $reviewRepo->Update($_POST['id'], new Review(array_merge($review->toArray(), [
        'rating' => $_POST['rating'],
        'comment' => $_POST['comment'],
    ])));

    http_response_code(200);
    header("Location: reviews.php");
    exit();
}

if (!array_key_exists('id', $_GET)) {
    http_response_code(400);
    echo json_encode(["message" => "Missing data field.:3"]);
    exit();
}

$review = $reviewRepo->GetElementByID($_GET["id"]);

if (!$review instanceof Review) {
    http_response_code(404);
    exit();
}

$currentFile = basename(__FILE__);
$htmlContent = TemplateService::renderHtml($currentFile, [
    "[[bookingId]]" => $review->getId(),
    "[[rating]]" => $review->getRating(),
    "[[comment]]" => $review->getComment(),
]);
echo $htmlContent;
?>