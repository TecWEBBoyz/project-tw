<?php

namespace PTW\Utility;

use function PTW\config;

class BreadCrumbUtility {

    public static $excludedRoutes = [
        // "/home",
        // "/"
    ];

    public static $breadcrumbMapping = [];

    // Funzione per inizializzare il mapping dei breadcrumb
    public static function initBreadcrumbMapping()
    {
        self::$breadcrumbMapping = [
            "/" => [
                ["label" => \PTW\translation('breadcrumb-home'), "url" => "/home"]
            ],
            "/home" => [
                ["label" => \PTW\translation('breadcrumb-home'), "url" => "/home"]
            ],
            "/about" => [
                ["label" => \PTW\translation('breadcrumb-home'), "url" => "/home"],
                ["label" => \PTW\translation('breadcrumb-about-me'), "url" => null]
            ],
            "/services" => [
                ["label" => \PTW\translation('breadcrumb-home'), "url" => "/home"],
                ["label" => \PTW\translation('breadcrumb-services'), "url" => null]
            ],
            "/contact" => [
                ["label" => \PTW\translation('breadcrumb-home'), "url" => "/home"],
                ["label" => \PTW\translation('breadcrumb-contact'), "url" => null]
            ],
            "/register" => [
                ["label" => \PTW\translation('breadcrumb-home'), "url" => "/home"],
                ["label" => \PTW\translation('breadcrumb-register'), "url" => null]
            ],
            "/login" => [
                ["label" => \PTW\translation('breadcrumb-home'), "url" => "/home"],
                ["label" => \PTW\translation('breadcrumb-login'), "url" => null]
            ],
            "/logout" => [
                ["label" => \PTW\translation('breadcrumb-home'), "url" => "/home"],
                ["label" => \PTW\translation('breadcrumb-logout'), "url" => null]
            ],
            "/admin" => [
                ["label" => \PTW\translation('breadcrumb-home'), "url" => "/home"],
                ["label" => \PTW\translation('breadcrumb-admin'), "url" => null]
            ],
            "/admin/justuploadedimage" => [
                ["label" => \PTW\translation('breadcrumb-home'), "url" => "/home"],
                ["label" => \PTW\translation('breadcrumb-admin'), "url" => "/admin"],
                ["label" => \PTW\translation('breadcrumb-just-uploaded-image'), "url" => null]
            ],
            "/admin/edit-image" => [
                ["label" => \PTW\translation('breadcrumb-home'), "url" => "/home"],
                ["label" => \PTW\translation('breadcrumb-admin'), "url" => "/admin"],
                ["label" => \PTW\translation('breadcrumb-edit-image'), "url" => null]
            ],
            "/admin/upload" => [
                ["label" => \PTW\translation('breadcrumb-home'), "url" => "/home"],
                ["label" => \PTW\translation('breadcrumb-admin'), "url" => "/admin"],
                ["label" => \PTW\translation('breadcrumb-upload-image'), "url" => null]
            ],
            "/dashboard" => [
                ["label" => \PTW\translation('breadcrumb-home'), "url" => "/home"],
                ["label" => \PTW\translation('breadcrumb-dashboard'), "url" => null]
            ],
            "/500" => [
                ["label" => \PTW\translation('breadcrumb-home'), "url" => "/home"],
                ["label" => \PTW\translation('breadcrumb-error-500'), "url" => null]
            ],
            "/gallerydetails" => [
                ["label" => \PTW\translation('breadcrumb-home'), "url" => "/home"],
                ["label" => \PTW\translation('breadcrumb-gallery-details'), "url" => null]
            ],
            "/book-service" => [
                ["label" => \PTW\translation('breadcrumb-home'), "url" => "/home"],
                ["label" => \PTW\translation('breadcrumb-book-service'), "url" => null]
            ],
            "/profile" => [
                ["label" => \PTW\translation('breadcrumb-home'), "url" => "/home"],
                ["label" => \PTW\translation('breadcrumb-profile'), "url" => null]
            ],
            "/profile/edit-booking" => [
                ["label" => \PTW\translation('breadcrumb-home'), "url" => "/home"],
                ["label" => \PTW\translation('breadcrumb-profile'), "url" => "/profile"],
                ["label" => \PTW\translation('breadcrumb-edit-booking'), "url" => null]
            ]
        ];
    }

    // Funzione per ottenere il breadcrumb basato sulla route senza prefisso
    private static function getBreadcrumb($uri, $usernamePrefix) {
        // Inizializza il mapping dei breadcrumb
        if (empty(BreadCrumbUtility::$breadcrumbMapping)) {
            BreadCrumbUtility::initBreadcrumbMapping();
        }
        $breadcrumb = BreadCrumbUtility::$breadcrumbMapping[$uri] ?? [["label" => \PTW\translation('breadcrumb-home'), "url" => $usernamePrefix]];
        // Aggiunge il prefisso username a ogni link
        foreach ($breadcrumb as &$crumb) {
            if (!empty($crumb['url'])) {
                $crumb['url'] = $usernamePrefix . ltrim($crumb['url'], '/');
            }
        }
        return $breadcrumb;
    }

    public static function getBreadCrumbElement($template, $data = []): string
    {
        // Inizializza il mapping dei breadcrumb
        if (empty(BreadCrumbUtility::$breadcrumbMapping)) {
            BreadCrumbUtility::initBreadcrumbMapping();
        }
        // Configurazione del prefisso username
        $usernamePrefix = config("router.baseURL")."/";
        $currentUri = $_SERVER['REQUEST_URI']; // Percorso corrente
        // Rimuovi i parametri dall'URI
        if (($pos = strpos($currentUri, '?')) !== false) {
            $currentUri = substr($currentUri, 0, $pos);
        }
        // Rimuovi il prefisso `username` dal percorso per mappare correttamente i breadcrumb
        if (str_starts_with($currentUri, $usernamePrefix)) {
            $relativeUri = substr($currentUri, strlen($usernamePrefix) - 1);
        } else {
            $relativeUri = $currentUri; // Nessun prefisso trovato
        }

        // Ottieni il breadcrumb per la route corrente senza il prefisso
        $currentBreadcrumb = BreadCrumbUtility::getBreadcrumb($relativeUri, $usernamePrefix);
        $breadcrumb = "";
        $breadcrumb= $breadcrumb . '<div class="breadcrumb ' . (in_array($relativeUri, BreadCrumbUtility::$excludedRoutes) ? "hidden" : "") . '">';
        $totalCrumbs = count($currentBreadcrumb);
        foreach ($currentBreadcrumb as $index => $crumb) {
            if (!empty($crumb['url'])) {
                $breadcrumb .= "<a href='".htmlspecialchars($crumb['url'])."' class='link'>".htmlspecialchars($crumb['label'])."</a>";
            } else {
                $breadcrumb .= "<p> " .htmlspecialchars($crumb['label']) . "</p>";
            }

            // Aggiungi il separatore solo se non è l'ultimo elemento
            if ($index < $totalCrumbs - 1) {
                $breadcrumb .= file_get_contents("static/images/next-arrow.svg");
            }
        }
        $breadcrumb = $breadcrumb. "</div>";
        return $breadcrumb;
    }
}
