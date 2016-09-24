<?php namespace PhpCompressor\Lib;

class Config {

    // Public
    public $compressorType;         // Define the compressor type
    public $useFileExtensions;      // Collecting files only with this extension
    public $pathDestination;        // Path for destination
    public $pathLocations;          // Path for locations
    public $compressFolder;         // Folder for output
    public $compressFilename;       // Name of output file

    // Private
    private $config;
    private $compressor;
    private $configItems;

    public function __construct(Compressor $compressor)
    {
        $this->config = false;
        $this->compressor = $compressor;
        $this->configItems = false;
        $this->pathLocations = [];
        $this->compressFolder = 'compressor/';
        $this->compressFilename = 'take';
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

    /*
     * Config path destination
     */
    private function configPathDestination($value)
    {
        $value = ($value == "/") ?  "" : $value;
        $destination = getcwd() . '/' . $value;

        // Check if ends with slach
        if(substr($destination, -1) != '/')
            $destination .= '/';

        // Add compressor folder if it is missing
        if(substr($destination, -11) != $this->compressFolder)
            $destination .= $this->compressFolder;

        // Check if folder exists
        if( ! is_dir($destination))
        {
            // Check normal folder exists
            $normalFolder = substr($destination, 0, -11);

            if( ! is_dir($normalFolder))
            {
                new ErrorMessage($this,
                    'Destination not exists!',
                    'Check permissions of if folder <span class="highlighted">&lt;destination&gt;</span> exist!<br>Error in line: <code>PhpCompressor::run(&lt;location&gt;, <span class="highlighted">&lt;destination&gt;</span>)</code>',
                    ['current dir' => getcwd(), 'destination' => $normalFolder]);
            }
            else
            {
                // Create compressor folder
                mkdir($destination, 0755, true);

                if( ! is_dir($destination))
                {
                    new ErrorMessage($this,
                        'Folder <span class="highlighted">' . $this->compressFolder . '</span> not exist!',
                        'Create folder <span class="highlighted">' . $this->compressFolder . '</span> in ' . $normalFolder,
                        ['current dir' => getcwd(), 'destination' => $normalFolder, '  required ' => $destination]);
                }
            }
        }

        $this->pathDestination = $destination;
    }

    /*
     * Config patch locations
     */
    private function configPathLocationMultiple(Array $array)
    {
        foreach ($array as $item)
        {
            $this->configPathLocation($item);
        }
    }

    /*
     * Config patch location
     */
    private function configPathLocation($value)
    {
        $value = ($value == "/") ?  "" : $value;
        $location = getcwd() . '/' . $value;

        // Check if ends with slach
        if(substr($location, -1) != '/')
            $location .= '/';

        if( ! is_dir($location))
            new ErrorMessage($this,
                'Location not exist!',
                'Check if folder <span class="highlighted">' . $location . '</span> exist or permission. <br>Error in line: <code>PhpCompressor::run( <span class="highlighted">&lt;location&gt;</span>, &lt;destination&gt;)</code>',
                ['current dir' => getcwd(), 'location' => $location]);

        $this->pathLocations[] = $location;

    }

}