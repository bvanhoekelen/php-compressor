<?php

require_once('../vendor/autoload.php');

use PhpCompressor\PhpCompressor;

$production = true;
// Place before view is build
// DO NOT RUN ON PRODUCTION
//if(env('APP_DEBUG')) // Laravel detaction
if($production)
    PhpCompressor::run(['tests/resources/css/', 'tests/resources/js/'], 'tests/public/');

// Place in html <head> section to access the assets
//<link href='/compressor/take.css' rel='stylesheet'>
//<script src='/compressor/take.js'></script>


