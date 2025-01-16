<?php

/**
 * Main entry point of the website
 */

use PTW\App;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/App/Helpers.php';
use function PTW\traslation;


App::Init();
App::HandleRoute();
