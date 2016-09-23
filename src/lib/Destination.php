<?php

class Destination {

    private $destination;

    public function __construct()
    {
        $this->destination = false;
    }

    /*
     * Set destination
     */
    public function set($destination)
    {
        if( ! is_dir($destination))
            new ErrorMessage($this,
                'Destination to "' . $destination . '" does not exists!',
                'Check the `destination` path!',
                ['destination' => $destination]);

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