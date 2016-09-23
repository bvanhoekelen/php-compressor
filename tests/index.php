<?php

require_once('../vendor/autoload.php');

use PhpCompressor\PhpCompressor;

$production = true;
// Place before view is build
// DO NOT RUN ON PRODUCTION
//if(env('APP_DEBUG')) // Laravel detaction
if($production)
    PhpCompressor::run(['resources/css/', 'resources/js/'], 'public/');

// Place in html <head> section to access the assets
PhpCompressor::get('public/', ['css','js']);


