<?php namespace PhpCompressor\Lib;

class Compressor {

    const COMPRESSOR_VERSION = '1.1.4';

    public $performance;        // Store performance information
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
        $this->files->checkForChange();
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
     * Setters
     */

    public function setConfig(Array $configs)
    {
        $this->config->setMultiple($configs);
    }

    public function setPaths(Array $paths)
    {
        $this->config->set('PathLocationMultiple', $paths);
    }

    public function setDestination($destination)
    {
        $this->config->set('PathDestination', $destination);
    }

}