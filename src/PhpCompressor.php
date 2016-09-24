<?php namespace PhpCompressor;

use PhpCompressor\Lib\Compressor;

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
    public static function run(Array $paths, $destination = '', $config = [])
    {
        $compressor = self::getCompressor();
        $compressor->setPaths($paths);
        $compressor->setDestination($destination);
        $compressor->setConfig($config);
        $compressor->run();
        $compressor->clean();
    }
}