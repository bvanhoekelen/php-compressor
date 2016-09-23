<?php namespace PhpCompressor\Lib;

class Files {

    private $files;                 // Store filse
    private $compressor;            // Supper class
    public  $combine;

    public function __construct(Compressor $compressor)
    {
        $this->files = false;
        $this->combineFile = false;
        $this->compressor = $compressor;
    }

    /*
     * Set files
     */
    public function collectFiles()
    {
        $this->files = [];
        $paths = $this->compressor->paths->get();
        $extensions = $this->compressor->config->useFileExtensions;

        // Set default pats
        foreach ($extensions as $extension)
        {
            $this->files[ $extension ] = [];
        }

        // Collect files
        foreach ($paths as $path)
        {
            if ($dh = opendir($path))
            {
                while (($file = readdir($dh)) !== false)
                {
                    $fileInfo = pathinfo($file);
                    if (in_array($fileInfo['extension'], $extensions))
                    {
                        $fileInfo['date'] = filemtime($path . $file);
                        $fileInfo['path'] = $path;
                        $fileInfo['pathAndFile'] = $path . $file;
                        unset($fileInfo['dirname']);
                        $this->files[$fileInfo['extension']][] = $fileInfo;
                    }
                }

                closedir($dh);
            }
        }
    }

    /*
     * Combine file by extension
     */
    public function combine()
    {
        $this->combine = [];

        foreach ($this->files as $extension => $files)
        {
            if( count($files) )
            {
                $this->compressor->performance->set(__FUNCTION__, $extension);
                $this->combine[$extension] = "";
                foreach ($files as $file)
                {
                    $this->combine[$extension] .= "\n/* add file " . $file['filename'] . "." . $file['extension'] . " */\n";
                    $this->combine[$extension] .= file_get_contents($file['pathAndFile']);
                }
                $this->compressor->performance->stop(__FUNCTION__, $extension);
            }

        }
    }

    /*
     * Return paths
     */
    public function get()
    {
        if( ! $this->files)
            $this->collectFiles();

        return $this->files;
    }

}