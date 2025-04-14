<?php

class Templating {

    public static function renderHtml($callerFile) {

        $calledFile = __DIR__ . "/../htmlTemplates/" . str_replace(".php", ".html", $callerFile);

        return file_get_contents($calledFile);
    }

}

?>