<?php namespace PhpCompressor\Lib;

class CompressorType {

    const COMPRESSOR_TYPE_FULL = 'COMPRESSOR_TYPE_FULL';

    private $type;

    public function __construct()
    {
        $this->type = self::COMPRESSOR_TYPE_FULL;
    }

    /*
     * Set destination
     */
    public function set($compressorType)
    {

        $refl = new ReflectionClass(__CLASS__);
        $types = $refl->getConstants();

        if( ! isset($types[$compressorType]))
            new ErrorMessage($this,
                'Compressor type "' . $compressorType . '" does not exist',
                'Check `compressor type`!',
                [
                    'Your compressor type' => $compressorType,
                    'Accept compressor types' => $types
                ]);

        $this->type = $compressorType;
    }

    /*
     * Return destination
     */
    public function get()
    {
        return $this->type;
    }

}