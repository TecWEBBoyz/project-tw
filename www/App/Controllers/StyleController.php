<?php

namespace PTW\Controllers;

use PTW\Contracts\ControllerContract;

class StyleController extends ControllerContract
{
    public function get(): void
    {
        // Path to the CSS directory
        $cssDir = __DIR__ . '/../../static/css';

        // Check if the directory exists
        if (!is_dir($cssDir)) {
            http_response_code(404);
            echo "CSS directory not found.";
            return;
        }

        // Initialize an array to define the order of the first files
        $priorityFiles = ['icons.css', 'main.css', 'menu.css'];

        // Initialize an array to hold CSS content
        $mergedCss = '';

        // Open the directory and read the files
        $files = scandir($cssDir);

        // Add the priority files first if they exist
        $cssFiles = [];
        foreach ($priorityFiles as $priorityFile) {
            if (in_array($priorityFile, $files)) {
                $cssFiles[] = $priorityFile;
            }
        }

        // Add other CSS files, excluding those in the priority list, sorted alphabetically
        foreach ($files as $file) {
            if (!in_array($file, $priorityFiles) && pathinfo($file, PATHINFO_EXTENSION) === 'css') {
                $cssFiles[] = $file;
            }
        }

        // Read and merge the content of the CSS files
        foreach ($cssFiles as $file) {
            $filePath = $cssDir . '/' . $file;

            if (is_file($filePath)) {
                $mergedCss .= file_get_contents($filePath) . "\n";
            }
        }

        // Set the appropriate content-type header for CSS
        header("Content-Type: text/css");
        // cache force
        header("Cache-Control: public");
        // Output the merged CSS
        echo $mergedCss;
    }
    public function test($data): void
    {
        $templateFile = $data['template'] . '.css';
        // Path to the CSS directory
        $cssDir = __DIR__ . '/../../static/css';

        // Check if the directory exists
        if (!is_dir($cssDir)) {
            http_response_code(404);
            echo "CSS directory not found.";
            return;
        }

        // Initialize an array to define the order of the first files
        $priorityFiles = ['icons.css', 'main.css', 'menu.css'];

        // Include the template file if it exists
        if (!empty($templateFile)) {
            $priorityFiles[] = $templateFile;
        }

        // Initialize an array to hold CSS content
        $mergedCss = '';

        // Open the directory and read the files
        $files = scandir($cssDir);

        // Add the priority files first if they exist
        $cssFiles = [];
        foreach ($priorityFiles as $priorityFile) {
            if (in_array($priorityFile, $files)) {
                $cssFiles[] = $priorityFile;
            }
        }


        // Read and merge the content of the CSS files
        foreach ($cssFiles as $file) {
            $filePath = $cssDir . '/' . $file;

            if (is_file($filePath)) {
                $mergedCss .= file_get_contents($filePath) . "\n";
            }
        }

        // Set the appropriate content-type header for CSS
        header("Content-Type: text/css");
        //Change the cache control to public
        header("Cache-Control: public");

        // Output the merged CSS
        echo $mergedCss;
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