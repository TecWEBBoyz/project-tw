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
    if (!array_key_exists('rating', $_POST) 
        || !array_key_exists('comment', $_POST)) {
        
        http_response_code(400);
        echo json_encode(["message" => "Missing data field."]);
        exit();
    }


    // Create new booking
    $newReview = new Review([
        'user_id' => $currentUser->getId(),
        'rating' => $_POST['rating'],
        'comment' => $_POST['comment'],
    ]);
    $reviewRepo->Create($newReview);

    header("Location: userDashboard.php");
    exit();
}

$currentFile = basename(__FILE__);
$htmlContent = TemplateService::renderHtml($currentFile);
echo $htmlContent;

?>