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
                ["label_translation" => 'breadcrumb-home', "url" => "/home"]
            ],
            "/home" => [
                ["label_translation" => 'breadcrumb-home', "url" => "/home"]
            ],
            "/about" => [
                ["label_translation" => 'breadcrumb-home', "url" => "/home"],
                ["label_translation" => 'breadcrumb-about-me', "url" => null]
            ],
            "/services" => [
                ["label_translation" => 'breadcrumb-home', "url" => "/home"],
                ["label_translation" => 'breadcrumb-services', "url" => null]
            ],
            "/contact" => [
                ["label_translation" => 'breadcrumb-home', "url" => "/home"],
                ["label_translation" => 'breadcrumb-contact', "url" => null]
            ],
            "/register" => [
                ["label_translation" => 'breadcrumb-home', "url" => "/home"],
                ["label_translation" => 'breadcrumb-register', "url" => null]
            ],
            "/login" => [
                ["label_translation" => 'breadcrumb-home', "url" => "/home"],
                ["label_translation" => 'breadcrumb-login', "url" => null]
            ],
            "/logout" => [
                ["label_translation" => 'breadcrumb-home', "url" => "/home"],
                ["label_translation" => 'breadcrumb-logout', "url" => null]
            ],
            "/admin" => [
                ["label_translation" => 'breadcrumb-home', "url" => "/home"],
                ["label_translation" => 'breadcrumb-admin', "url" => null]
            ],
            "/admin/justuploadedimage" => [
                ["label_translation" => 'breadcrumb-home', "url" => "/home"],
                ["label_translation" => 'breadcrumb-admin', "url" => "/admin"],
                ["label_translation" => 'breadcrumb-just-uploaded-image', "url" => null]
            ],
            "/admin/edit-image" => [
                ["label_translation" => 'breadcrumb-home', "url" => "/home"],
                ["label_translation" => 'breadcrumb-admin', "url" => "/admin"],
                ["label_translation" => 'breadcrumb-edit-image', "url" => null]
            ],
            "/admin/bookings" => [
                ["label_translation" => 'breadcrumb-home', "url" => "/home"],
                ["label_translation" => 'breadcrumb-admin', "url" => "/admin"],
                ["label_translation" => 'breadcrumb-manage-bookings', "url" => null]
            ],
            "/admin/upload" => [
                ["label_translation" => 'breadcrumb-home', "url" => "/home"],
                ["label_translation" => 'breadcrumb-admin', "url" => "/admin"],
                ["label_translation" => 'breadcrumb-upload-image', "url" => null]
            ],
            "/dashboard" => [
                ["label_translation" => 'breadcrumb-home', "url" => "/home"],
                ["label_translation" => 'breadcrumb-dashboard', "url" => null]
            ],
            "/500" => [
                ["label_translation" => 'breadcrumb-home', "url" => "/home"]
            ],
            "/gallerydetails" => [
                ["label_translation" => 'breadcrumb-home', "url" => "/home"],
                ["label_translation" => 'breadcrumb-gallery-details', "url" => null]
            ],
            "/book-service" => [
                ["label_translation" => 'breadcrumb-home', "url" => "/home"],
                ["label_translation" => 'breadcrumb-book-service', "url" => null]
            ],
            "/profile" => [
                ["label_translation" => 'breadcrumb-home', "url" => "/home"],
                ["label_translation" => 'breadcrumb-profile', "url" => null]
            ],
            "/profile/edit-booking" => [
                ["label_translation" => 'breadcrumb-home', "url" => "/home"],
                ["label_translation" => 'breadcrumb-profile', "url" => "/profile"],
                ["label_translation" => 'breadcrumb-edit-booking', "url" => null]
            ]
        ];
    }

    // Funzione per ottenere il breadcrumb basato sulla route senza prefisso
    private static function getBreadcrumb($uri, $usernamePrefix) {
        // Inizializza il mapping dei breadcrumb
        if (empty(BreadCrumbUtility::$breadcrumbMapping)) {
            BreadCrumbUtility::initBreadcrumbMapping();
        }
        $breadcrumb = BreadCrumbUtility::$breadcrumbMapping[$uri] ?? [["label_translation" => 'breadcrumb-home', "url" => $usernamePrefix]];
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
                $breadcrumb .= "<a href='".htmlspecialchars($crumb['url'])."' class='link' ".\PTW\getOriginalLanguageAttribute($crumb['label_translation']).">".htmlspecialchars(\PTW\translation($crumb['label_translation']))."</a>";
            } else {
                $breadcrumb .= "<p".\PTW\getOriginalLanguageAttribute($crumb['label_translation'])."> " .htmlspecialchars(\PTW\translation($crumb['label_translation'])) . "</p>";
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
