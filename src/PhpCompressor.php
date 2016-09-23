<?php

function __autoload($classname) {
    $filename = "lib/". $classname .".php";
    include_once($filename);
}

class PhpCompressor {

    private static $compressor = false;

    private static function getCompressor()
    {
        if( ! self::$compressor)
            self::$compressor = new Compressor();
        return self::$compressor;
    }

    /*
     * Run the compressor
     */
    public static function run(Array $paths, $destination, $config = [])
    {
        $compressor = self::getCompressor();
        $compressor->setPaths($paths);
        $compressor->setDestination($destination);
        $compressor->setConfig($config);
        $compressor->run();
        $compressor->clean();
    }

    /*
     * Create html tag
     */
    public static function get($path, Array $assets)
    {
        if(self::$compressor)
            echo self::getCompressor()->getAssets();
        elseif($path)
            echo self::getCompressor()->getAssetsFormInput($path, $assets);
    }

}