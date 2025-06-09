<?php
require_once 'init.php';

use PTW\Repositories\ServiceRepository;
use PTW\Services\TemplateService;


$serviceRepo = new ServiceRepository();

$services = $serviceRepo->All();

$no_services_fallback = '';
$repeated_replacements = [];
if (empty($services)) {
    $no_services_fallback .= '<p>Nessun servizio è al momento disponibile, e purtroppo non sappiamo dirti quando torneremo operativi. Ci scusiamo per il disagio.</p>';
} else {
    foreach ($services as $service) {
        if (!$service instanceof \PTW\Models\Service) {
            continue;
        }
        
        $single_replacement = [
            '[[serviceTitle]]' => $service->getName(),
            '[[serviceDescription]]' => $service->getDescription(),
        ];

        $repeated_replacements[] = $single_replacement;
    }
}

$currentFile = basename(__FILE__);
$indexHtmlContent = TemplateService::renderHtml($currentFile, [
    '[[emptyServicesFallback]]' => $no_services_fallback,
], $repeated_replacements);
echo $indexHtmlContent;

?>