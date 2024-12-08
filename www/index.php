<?php

/**
 * Main entry point of the website
 */

use PTW\App;

require_once "./vendor/autoload.php";
require_once "./App/Helpers.php";

App::Init();
App::HandleRoute();