<?php

class Templating {

    public static function renderHtml($callerFile, $replacements = []) {
        $templatePath = __DIR__ . "/../static/htmlTemplates/" . str_replace(".php", ".html", $callerFile);
        
        if (!file_exists($templatePath)) {
            throw new Exception("Template file not found: " . $templatePath);
        }
        
        $html = file_get_contents($templatePath);
        if ($html === false) {
            throw new Exception("Could not read template file: " . $templatePath);
        }

        foreach ($replacements as $key => $value) {
            $html = str_replace($key, $value, $html);
        }

        return $html;
    }

}
?>