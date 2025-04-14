<?php

class Templating {

    static $baseHtmlPath = __DIR__ . "/../static/htmlTemplates/";



    public static function renderHtml($callerFile) {
        $calledFile = self::$baseHtmlPath . str_replace(".php", ".html", $callerFile);
        return file_get_contents($calledFile);
    }

}

?>