<?php

namespace PTW\Utility;

use function PTW\config;

class BreadCrumbUtility {

    public static $excludedRoutes = [
        "/home",
        "/"
    ];

    public static $breadcrumbMapping = [
        "/" => [
            // ["label" => "HOME", "url" => "/"]
        ],
        "/home" => [
            // ["label" => "HOME", "url" => "/"]
        ],
        "/about" => [
            ["label" => "HOME", "url" => "/"],
            ["label" => "ABOUT ME", "url" => null]
        ],
        "/services" => [
            ["label" => "HOME", "url" => "/"],
            ["label" => "SERVICES", "url" => null]
        ],
        "/contact" => [
            ["label" => "HOME", "url" => "/"],
            ["label" => "CONTACT", "url" => null]
        ],
        "/register" => [
            ["label" => "HOME", "url" => "/"],
            ["label" => "REGISTER", "url" => null]
        ],
        "/login" => [
            ["label" => "HOME", "url" => "/"],
            ["label" => "LOGIN", "url" => null]
        ],
        "/logout" => [
            ["label" => "HOME", "url" => "/"],
            ["label" => "LOGOUT", "url" => null]
        ],
        "/admin" => [
            ["label" => "HOME", "url" => "/"],
            ["label" => "ADMIN", "url" => null]
        ],
        "/admin/justuploadedimage" => [
            ["label" => "HOME", "url" => "/"],
            ["label" => "ADMIN", "url" => "/admin"],
            ["label" => "JUST UPLOADED IMAGE", "url" => null]
        ],
        "/admin/edit-image" => [
            ["label" => "HOME", "url" => "/"],
            ["label" => "ADMIN", "url" => "/admin"],
            ["label" => "EDIT IMAGE", "url" => null]
        ],
        "/admin/upload" => [
            ["label" => "HOME", "url" => "/"],
            ["label" => "ADMIN", "url" => "/admin"],
            ["label" => "UPLOADE IMAGE", "url" => null]
        ],
        "/dashboard" => [
            ["label" => "HOME", "url" => "/"],
            ["label" => "DASHBOARD", "url" => null]
        ],
        "/500" => [
            ["label" => "HOME", "url" => "/"],
            ["label" => "ERROR 500", "url" => null]
        ],
        "/gallerydetails" => [
            ["label" => "HOME", "url" => "/"],
            ["label" => "GALLERY DETAILS", "url" => null]
        ],
        "/book-service" => [
            ["label" => "HOME", "url" => "/"],
            ["label" => "BOOK SERVICE", "url" => null]
        ],
        "/profile" => [
            ["label" => "HOME", "url" => "/"],
            ["label" => "PROFILE", "url" => null]
        ]
    ];

    // Funzione per ottenere il breadcrumb basato sulla route senza prefisso
    private static function getBreadcrumb($uri, $usernamePrefix) {
        $breadcrumb = BreadCrumbUtility::$breadcrumbMapping[$uri] ?? [["label" => "HOME", "url" => $usernamePrefix]];
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
        // Configurazione del prefisso username
        $usernamePrefix = config("router.baseURL")."/";
        $currentUri = $_SERVER['REQUEST_URI']; // Percorso corrente
        // Remove parameters from the URI
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