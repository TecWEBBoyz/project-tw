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
                ["label" => "Home", "url" => "/"]
            ],
            "/home" => [
                ["label" => "Home", "url" => "/"]
            ],
            "/about" => [
                ["label" => "Home", "url" => "/"],
                ["label" => "About Us", "url" => null]
            ],
            "/services" => [
                ["label" => "Home", "url" => "/"],
                ["label" => "Services", "url" => null]
            ],
            "/contact" => [
                ["label" => "Home", "url" => "/"],
                ["label" => "Contact", "url" => null]
            ],
            "/register" => [
                ["label" => "Home", "url" => "/"],
                ["label" => "Register", "url" => null]
            ],
            "/login" => [
                ["label" => "Home", "url" => "/"],
                ["label" => "Login", "url" => null]
            ],
            "/logout" => [
                ["label" => "Home", "url" => "/"],
                ["label" => "Logout", "url" => null]
            ],
            "/admin" => [
                ["label" => "Home", "url" => "/"],
                ["label" => "Admin", "url" => null]
            ],
            "/admin/justuploadedimage" => [
                ["label" => "Home", "url" => "/"],
                ["label" => "Admin", "url" => "/admin"],
                ["label" => "Just Uploaded Image", "url" => null]
            ],
            "/admin/edit-image" => [
                ["label" => "Home", "url" => "/"],
                ["label" => "Admin", "url" => "/admin"],
                ["label" => "Edit Image", "url" => null]
            ],
            "/admin/upload" => [
                ["label" => "Home", "url" => "/"],
                ["label" => "Admin", "url" => "/admin"],
                ["label" => "Upload Image", "url" => null]
            ],
            "/dashboard" => [
                ["label" => "Home", "url" => "/"],
                ["label" => "Dashboard", "url" => null]
            ],
            "/500" => [
                ["label" => "Home", "url" => "/"],
                ["label" => "Error 500", "url" => null]
            ],
            "/gallerydetails" => [
                ["label" => "Home", "url" => "/"],
                ["label" => "Gallery Details", "url" => null]
            ],
            "/book-service" => [
                ["label" => "Home", "url" => "/"],
                ["label" => "Book Service", "url" => null]
            ],
            "/profile" => [
                ["label" => "Home", "url" => "/"],
                ["label" => "Profile", "url" => null]
            ]
        ];
        // Funzione per ottenere il breadcrumb basato sulla route senza prefisso
        function getBreadcrumb($uri, $breadcrumbMapping, $usernamePrefix) {
            $breadcrumb = $breadcrumbMapping[$uri] ?? [["label" => "Home", "url" => $usernamePrefix]];
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
                $breadcrumb .= "<span>".htmlspecialchars($crumb['label'])."</span>";
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