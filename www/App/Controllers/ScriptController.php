<?php

namespace PTW\Controllers;

use PTW\Contracts\ControllerContract;

class ScriptController extends ControllerContract
{
    public function get(): void
    {
        $jsDir = __DIR__ . '/../../static/js';

        if (!is_dir($jsDir)) {
            http_response_code(404);
            echo "JavaScript directory not found.";
            return;
        }

        $priorityFiles = [ 'main.js', 'menu.js'];
        $mergedJs = '';
        $files = scandir($jsDir);

        $jsFiles = [];
        foreach ($priorityFiles as $priorityFile) {
            if (in_array($priorityFile, $files)) {
                $jsFiles[] = $priorityFile;
            }
        }

        foreach ($files as $file) {
            if (!in_array($file, $priorityFiles) && pathinfo($file, PATHINFO_EXTENSION) === 'js') {
                $jsFiles[] = $file;
            }
        }

        foreach ($jsFiles as $file) {
            $filePath = $jsDir . '/' . $file;
            if (is_file($filePath)) {
                $mergedJs .= file_get_contents($filePath) . "\n";
            }
        }

        $etag = md5($mergedJs);

        header("Content-Type: application/javascript");
        header("Cache-Control: public, max-age=31536000"); // 1-year cache
        header("ETag: $etag");

        if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] === $etag) {
            http_response_code(304); // Not Modified
            return;
        }

        echo $mergedJs;
    }

    public function post(): void
    {
    }

    public function put(): void
    {
    }

    public function delete(): void
    {
    }
}
