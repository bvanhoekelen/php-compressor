<?php namespace PhpCompressor\Lib;

class Config {

    // Public
    public $compressorType;         // Define the compressor type
    public $useFileExtensions;      // Collecting files only with this extension

    // Private
    private $config;
    private $compressor;
    private $configItems;

    public function __construct(Compressor $compressor)
    {
        $this->config = false;
        $this->compressor = $compressor;
        $this->configItems = false;
        $this->compressorType = new CompressorType();
        $this->useFileExtensions = ['css', 'js'];
    }


    public function setMultiple(Array $configs)
    {
        foreach ($configs as $key => $value)
        {
            $this->set($key, $value);
        }
    }

    /*
     * Set destination
     */
    public function set($key, $value)
    {
        $configName = 'config' . $key;

        if( ! method_exists($this, $configName))
            new ErrorMessage($this,
                'Config item "' . $key . '" not exists',
                'Check config item `' . $key . '`!',
                [
                    'Your config item' => $key,
                    'Accept config item' => $this->getConfigItems()
                ]);

        $this->$configName($value);
    }

    /*
     * Return destination
     */
    public function get()
    {
        return $this->config;
    }

/*
 * Private fucntions
 */
    private function getConfigItems()
    {
        if( ! $this->configItems)
        {
            $classMethods = get_class_methods($this);
            $this->configItems = [];
            foreach ($classMethods as $method)
            {
                if (substr($method, 0, 6) == 'config')
                {
                    $this->configItems[] = substr($method, 6);
                }
            }
        }

        return $this->configItems;
    }

    /*
     * Config item compressor type
     */
    private function configCompressorType($value)
    {
        $this->compressorType->set($value);
    }

}