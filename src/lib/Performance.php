<?php namespace PhpCompressor\Lib;

class Performance {

    private $data =[];

    public function __construct()
    {
        $this->set('full');
    }

    /*
     * Set destination
     */
    public function set($name, $subName = null)
    {
        if($subName)
            $this->data[$name][$subName]['start'] = microtime(true);
        else
            $this->data[$name]['start'] = microtime(true);
    }

    /*
     * Return destination
     */
    public function stop($name, $subName = null)
    {
        if($subName and isset($this->data[$name][$subName]['start']))
            $this->data[$name][$subName]['stop'] = microtime(true);
        elseif(isset($this->data[$name]['start']))
            $this->data[$name]['stop'] = microtime(true);
        else
            new ErrorMessage('Performance name ' . $name . ' not exist');
    }

    /**
     * Get performance
     */
    public function get($format, $name, $subName = null)
    {
        $diff = 0;
        if($subName and isset($this->data[$name][$subName]['stop']))
            $diff = $this->data[$name][$subName]['stop'] - $this->data[$name][$subName]['start'];
        elseif($name and isset($this->data[$name]['stop']))
            $diff = $this->data[$name]['stop'] - $this->data[$name]['start'];
        elseif(isset($this->data[$name]['start']))
            $diff = microtime(true) - $this->data[$name]['start'];

        switch ($format)
        {
            case 'μs':
                return round($diff * 1000000) . $format;
                break;
            case 'ms':
                return round($diff * 1000) . $format;
                break;
            case 's':
                return round($diff * 1) . $format;
                break;
            default:
                new ErrorMessage($this, 'Performance format ' . $format . ' not exist');
        }


    }

}