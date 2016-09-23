<?php

/**
 * Created by PhpStorm.
 * User: bartvanhoekelen
 * Date: 23-09-16
 * Time: 00:12
 */
class ErrorMessage {

    /*
     * Show error message
     */
    public function __construct($class, $message, $hint = '', $var = [], $die = true)
    {
        echo '<h1>' . $message . '</h1>';
        echo '<h3>Hint:</h3>';
        echo '<p>' . $hint . '</p>';

        if($var)
        {
            echo '<h3>Vars:</h3>';
            echo '<pre>';
            print_r($var);
            echo '</pre>';
        }



        echo '<h3>Supper class this:</h3>';
        echo '<pre>';
        print_r($class);
        echo '</pre>';

        echo '___________________________________________________<br>';
        echo '<i>PhpCompressor error message v' . Compressor::COMPRESSOR_VERSION . '</i>';

        if($die)
            die;
    }

}