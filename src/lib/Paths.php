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
        if(substr($path,0,1) == "/")
            new ErrorMessage($this,
                'Path can not begin with a slash!',
                'Remove the first slash by path "<span class="highlighted-error" ">/</span>' . substr($path, 1) . '"!',
                ['current dir' => getcwd(), 'path' => $path]);

        if(substr($path, -1, 1) != "/")
            new ErrorMessage($this,
                'Path has to end with a slash!',
                'Add slash to the end "' . substr($path, 0, -1) . '<span class="highlighted-error" ">/</span>"!',
                ['current dir' => getcwd(), 'path' => $path]);

        if( ! is_dir($path))
            new ErrorMessage($this,
                'Path to "' . $path . '" does not exists!',
                'Check the `paths` path!',
                ['current dir' => getcwd(), 'path' => $path]);

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