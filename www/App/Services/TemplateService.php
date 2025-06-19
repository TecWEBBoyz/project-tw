<?php
namespace PTW\Services;

use Exception;

class TemplateService {
    private static $basedir = __DIR__ . "/../../static/htmlTemplates/";

    public static function renderHtml($callerFile, $base_replacements = [], $repeated_replacements = []) {
        $templatePath = self::$basedir . str_replace(".php", ".html", $callerFile);
        
        if (!file_exists($templatePath)) {
            throw new Exception("Template file not found: " . $templatePath);
        }
        
        $html = file_get_contents($templatePath);
        if ($html === false) {
            throw new Exception("Could not read template file: " . $templatePath);
        }

        // HANDLE BASE PLACEHOLDER
        // Replace specified placeholders in the HTML template with actual values
        $html = strtr($html, $base_replacements);

        // Replace logged user placeholders
        $loginNav = ''; // Inizializziamo la stringa per la navigazione dinamica

        if (AuthService::isUserLoggedIn()) {
            // Utente normale loggato
            if (basename($callerFile) === 'userDashboard.php') {
                // Se siamo nella dashboard utente, "Prenotazioni" non è un link
                $loginNav .= '<li class="current-page"><span aria-current="page">Prenotazioni</span></li>';
            } else if (basename($callerFile) === 'login.php') {
                $loginNav .= '<li><a class="menu-link" href="userDashboard.php">Prenotazioni</a></li>';
                $loginNav .= '<li class="current-page"><span aria-current="page"><span lang="en">Login</span></span></li>';
            } else {
                $loginNav .= '<li><a class="menu-link" href="userDashboard.php">Prenotazioni</a></li>';
            }
            $loginNav .= '<li><a class="menu-link" href="logout.php"><span lang="en">Logout</span></a></li>';

        } else if (AuthService::isAdminLoggedIn()) {
            // Amministratore loggato
            if (basename($callerFile) === 'adminDashboard.php') {
                // Se siamo nella dashboard admin, "Gestione" non è un link
                $loginNav .= '<li class="current-page"><span aria-current="page">Gestione</span></li>';
            } else if (basename($callerFile) === 'login.php') {
                $loginNav .= '<li><a class="menu-link" href="adminDashboard.php">Gestione</a></li>';
                $loginNav .= '<li class="current-page"><span aria-current="page"><span lang="en">Login</span></span></li>';
            } else {
                $loginNav .= '<li><a class="menu-link" href="adminDashboard.php">Gestione</a></li>';
            }
            $loginNav .= '<li><a class="menu-link" href="logout.php"><span lang="en">Logout</span></a></li>';

        } else {
            // Utente non loggato
            if (basename($callerFile) === 'login.php') {
                // Se siamo nella pagina di login, "Login" non è un link
                $loginNav .= '<li class="current-page"><span aria-current="page"><span lang="en">Login</span></span></li>';
            } else {
                $loginNav .= '<li><a class="menu-link" href="login.php"><span lang="en">Login</span></a></li>';
            }
        }

// Ora sostituiamo il placeholder con la stringa HTML che abbiamo costruito
$html = str_replace("[[ifLoggedIn]]", $loginNav, $html);

        foreach ($repeated_replacements as $key => $value) {
            // HANDLE REPETITION PLACEHOLDER
            if (preg_match('/\[\[' . $key .  '\]\]\{\{(.*?)\}\}/s', $html, $matches)) {
                $to_repeat_content = $matches[1] ?? '';
                $full_repeated_content = '';

                foreach ($value as $single_replacement) {
                    if (is_array($single_replacement)) {
                        $full_repeated_content .= strtr($to_repeat_content, $single_replacement);
                    }
                }

                $html = str_replace($matches[0], $full_repeated_content, $html);
            }
        }


        return $html;
    }

}
?>