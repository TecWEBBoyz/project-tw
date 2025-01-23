<?php

namespace PTW\Utility;

use function PTW\config;

class BreadCrumbUtility {
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
        if (strpos($currentUri, $usernamePrefix) === 0) {
            $relativeUri = substr($currentUri, strlen($usernamePrefix) - 1);
        } else {
            $relativeUri = $currentUri; // Nessun prefisso trovato
        }

        // Breadcrumb mapping per tutte le rotte GET senza il prefisso
        $breadcrumbMapping = [
            "/" => [
                ["label" => "", "url" => "/"]
            ],
            "/home" => [
                ["label" => "", "url" => "/"]
            ],
            "/about" => [
                ["label" => "HOME", "url" => "/"],
                ["label" => "ABOUT US", "url" => null]
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
        function getBreadcrumb($uri, $breadcrumbMapping, $usernamePrefix) {
            $breadcrumb = $breadcrumbMapping[$uri] ?? [["label" => "HOME", "url" => $usernamePrefix]];
            // Aggiunge il prefisso username a ogni link
            foreach ($breadcrumb as &$crumb) {
                if (!empty($crumb['url'])) {
                    $crumb['url'] = $usernamePrefix . ltrim($crumb['url'], '/');
                }
            }
            return $breadcrumb;
        }

        // Ottieni il breadcrumb per la route corrente senza il prefisso
        $currentBreadcrumb = getBreadcrumb($relativeUri, $breadcrumbMapping, $usernamePrefix);
        $breadcrumb ="";
        $breadcrumb= $breadcrumb."<div class='breadcrumb'>";
        $totalCrumbs = count($currentBreadcrumb);
        foreach ($currentBreadcrumb as $index => $crumb) {
            if (!empty($crumb['url'])) {
                $breadcrumb .= "<a href='".htmlspecialchars($crumb['url'])."'>".htmlspecialchars($crumb['label'])."</a>";
            } else {
                $breadcrumb .= "<span> " .htmlspecialchars($crumb['label']) . "</span>";
            }

            // Aggiungi il separatore solo se non è l'ultimo elemento
            if ($index < $totalCrumbs - 1) {
                $breadcrumb .= " &raquo;";
            }
        }
        $breadcrumb = $breadcrumb. "</div>";
        return $breadcrumb;
    }
}