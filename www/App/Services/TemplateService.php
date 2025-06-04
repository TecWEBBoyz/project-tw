<?php
namespace PTW\Services;

use Exception;

class TemplateService {
    private static $basedir = __DIR__ . "/../../static/htmlTemplates/";

    public static function renderHtml($callerFile, $replacements = []) {
        $templatePath = self::$basedir . str_replace(".php", ".html", $callerFile);
        
        if (!file_exists($templatePath)) {
            throw new Exception("Template file not found: " . $templatePath);
        }
        
        $html = file_get_contents($templatePath);
        if ($html === false) {
            throw new Exception("Could not read template file: " . $templatePath);
        }

        // Replace specified placeholders in the HTML template with actual values
        $html = strtr($html, $replacements);

        // Replace logged user placeholders
        if (AuthService::isUserLoggedIn()) {
            $html = str_replace("[[ifLoggedIn]]", '<li><a class="menu-link" href="userDashboard.php">Prenotazioni</a></li><li><a class="menu-link" href="logout.php">Logout</a></li>', $html);
        } else if (AuthService::isAdminLoggedIn()) {
            $html = str_replace("[[ifLoggedIn]]", '<li><a class="menu-link" href="adminDashboard.php">Gestione</a></li><li><a class="menu-link" href="logout.php"><span lang="en">Logout</span></a></li>', $html);
        } else {
            $html = str_replace("[[ifLoggedIn]]", '<li><a class="menu-link" href="login.php"><span lang="en">Login</span></a></li>', $html);
        }

        return $html;
    }

}
?>