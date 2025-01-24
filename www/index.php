<?php
//error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE & ~E_DEPRECATED);
//ini_set('display_errors', '0');
/**
 * Main entry point of the website
 */

use PTW\App;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/App/Helpers.php';
use function PTW\traslation;


App::Init();
App::HandleRoute();
