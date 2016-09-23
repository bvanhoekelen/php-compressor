<?php namespace PhpCompressor\Lib;

class Compressor {

    const COMPRESSOR_VERSION = '1.0.3';

    public $performance;        // Store performance information
    public $paths;              // Stores the paths to css and js filse
    public $destination;        // Destination of the one css and js file
    public $config;             // Store config settings
    public $files;              // Store files form the paths
    public $write;              // Write files to destination

    /*
     * Constructor
     */
    public function __construct()
    {
        // Performance test
        $this->performance = new Performance();
        $this->config = new Config($this);
        $this->paths = new Paths();
        $this->destination = new Destination();
        $this->files = new Files($this);
        $this->write = new Write($this);

    }

    /*
     * Run the compressor
     */
    public function run()
    {
        //Performance
        $this->performance->set(__FUNCTION__);

        // Action
        $this->files->collectFiles();
        $this->files->combine();
        $this->write->out();

        // Performance test
        $this->performance->stop(__FUNCTION__);
    }

    /*
     * Clean
     */
    public function clean()
    {
        unset($this->paths);
        unset($this->destination);
        unset($this->files);
    }

    /*
     * Return html code for loading compressor file
     */
    public function getAssets()
    {
        $this->performance->set(__FUNCTION__);
        $compressFiles = $this->write->compressorFile;
        $performanceFormat = 'μs';
        $return = "";
        foreach ($compressFiles as $compressFile)
        {
            $extension = $compressFile['extension'];
            $file = $compressFile['file'];
            $datetime = date('Y-m-d H:i:s', $compressFile['date']);
            switch ($extension)
            {
                case 'css':
                    $compressInfo = "<!-- Php-compressor on " . $datetime . " in " . $this->performance->get($performanceFormat, 'out', $extension) . " -->";
                    $return .= "<link href='" . $file . "' rel='stylesheet'>" . $compressInfo . "\n";
                    break;
                case 'js':
                    $compressInfo = "<!-- Php-compressor on  " . $datetime . " in " . $this->performance->get($performanceFormat, 'out', $extension) . " -->";
                    $return .= "<script src='" . $file . "'></script>" . $compressInfo . "\n";
                    break;
                default:
                    new ErrorMessage($this, 'Extension support "' . $extension . '" in function "' . __FUNCTION__ . '" not exist');
                    break;
            }
        }
        $this->performance->stop(__FUNCTION__);

        $return .= "<!-- Php-compressor finished in " . $this->performance->get('ms', 'full') . " -->";

        return $return;
    }

    /*
     * Return html code for loading compressor file
     */
    public function getAssetsFormInput($path, Array $assets)
    {
        $return = "";
        foreach ($assets as $asset)
        {
            $file = $path . 'compressor/take.' . $asset;
            switch ($asset)
            {
                case 'css':
                    $return .= "    <link href='" . $file . "' rel='stylesheet'>\n";
                    break;
                case 'js':
                    $return .= "    <script src='" . $file . "'></script>\n";
                    break;
                default:
                    new ErrorMessage($this, 'Extension support "' . $asset . '" in function "' . __FUNCTION__ . '" not exist');
                    break;
            }
        }

        $return .= "<!-- php-compressor on production --> \n";

        return $return;
    }

    /*
     * Setters
     */

    public function setConfig(Array $configs)
    {
        $this->config->setMultiple($configs);
    }

    public function setPaths(Array $paths)
    {
        $this->paths->setMultiple($paths);
    }

    public function setDestination($destination)
    {
        $this->destination->set($destination);
    }

}