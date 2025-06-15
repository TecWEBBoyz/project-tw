<?php
require_once 'init.php';

use PTW\Services\TemplateService;
use PTW\Repositories\ReviewRepository;
use PTW\Repositories\UserRepository;
use PTW\Models\Review;
use PTW\Models\User;

// Configurazione
$reviewsPerPage = 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

$reviewRepo = new ReviewRepository();
$userRepo = new UserRepository();

// Calcolo numero totale di pagine
$totalReviews = $reviewRepo->Count();
$totalPages = ceil($totalReviews / $reviewsPerPage);

// Fetch della pagina chiesta
$reviews = $reviewRepo->GetPage($page, $reviewsPerPage);

// Detrminazione se ci sono pagine precedenti o successive
$hasPrevious = $page > 1;
$hasNext = $page < $totalPages;

$repeated_replacements = [];
foreach($reviews as $review) {
    if (!$review instanceof PTW\Models\Review) {
        continue;
    }

    $user = $userRepo->GetElementById($review->getUserId());
    if (!$user instanceof \PTW\Models\User) {
        continue;
    }

    $single_replacement = [
        '[[username]]' => $user->getUsername(),
        '[[rating]]' => $review->getRating(),
        '[[comment]]' => $review->getComment(),
        '[[updateDate]]' => "<time datetime='" . $review->getUpdatedAt()->format("Y-m-d") . "'>" . $review->getUpdatedAt()->format("d M Y") . "</time>",
    ];

    $repeated_replacements[] = $single_replacement;
}


$currentFile = basename(__FILE__);
$htmlContent = TemplateService::renderHtml($currentFile, [
    '[[previousPage]]' => $hasPrevious ? ($page - 1) : null,
    '[[nextPage]]' => $hasNext ? ($page + 1) : null,
    '[[previousPageDisabled]]' => $hasPrevious ? '' : 'disabled',
    '[[nextPageDisabled]]' => $hasNext ? '' : 'disabled',
], $repeated_replacements);
echo $htmlContent;

?>