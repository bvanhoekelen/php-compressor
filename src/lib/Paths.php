<?php namespace PhpCompressor\Lib;

class Paths {

    private $paths;

    public function __construct()
    {
        $this->paths = [];
    }

    /*
     * Set the path to CSS and JS
     */
    public function setMultiple(Array $paths)
    {
        foreach ($paths as $path)
        {
            $this->set($path);
        }
    }

    /*
     * Set path
     */
    public function set($path)
    {
        if( ! is_dir($path))
            new ErrorMessage($this,
                'Path to "' . $path . '" does not exists!',
                'Check the `paths` path!',
                ['path' => $path]);

        if( ! isset($this->paths[$path]))
            $this->paths[] = $path;

    }

    /*
     * Return paths
     */
    public function get()
    {
        return $this->paths;
    }

}