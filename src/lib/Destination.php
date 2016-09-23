<?php namespace PhpCompressor\Lib;

class Destination {

    private $destination;

    public function __construct()
    {
        $this->destination = false;
    }

    /*
     * Set destination
     */
    public function set($inputDestination)
    {
        $destination = getcwd() . "/" . $inputDestination;

        if(substr($inputDestination,0,1) == "/" and $inputDestination != "/")
            new ErrorMessage($this,
                'Destination can not begin with a slash!',
                'Remove the first slash by path "<span class="highlighted-error" ">/</span>' . substr($destination, 1) . '"!',
                ['current dir' => getcwd(), 'path' => $destination]);

        if(substr($destination, -1, 1) != "/")
            new ErrorMessage($this,
                'Destination has to end with a slash!',
                'Add slash to the end "' . substr($destination, 0, -1) . '<span class="highlighted-error" ">/</span>"!',
                ['current dir' => getcwd(), 'path' => $destination]);

        if( ! is_dir($destination))
            new ErrorMessage($this,
                'Destination to "' . $destination . '" does not exists!',
                'Check the `destination` path!',
                ['current dir' => getcwd(), 'destination' => $destination]);

        // Check if compressor folder exists
        if( ! is_dir( $destination . "compressor/"))
            new ErrorMessage($this,
                'Folder "compressor" is missing!',
                'Create the folder <span class="highlighted">compressor/</span> in "' . $destination . '".',
                ['current dir' => getcwd(), 'destination' => $destination]);

        $this->destination = $destination;
    }

    /*
     * Return destination
     */
    public function get()
    {
        return $this->destination;
    }

}