<?php namespace PhpCompressor\Lib;

class Files {

    public $files;                  // Store filse
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
        $paths = $this->compressor->config->pathLocations;
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
     * Check if files has change
     */
    public function checkForChange()
    {
        $logFile = $this->compressor->config->pathDestination . $this->compressor->config->logFile;
        if(file_exists($logFile))
        {
            $log = file_get_contents($logFile);
            $log = json_decode($log);

            // Check version
            $logVersion = (isset($log->version)) ? $log->version : false;

            if(Compressor::COMPRESSOR_VERSION != $logVersion)
            {
                unlink($logFile);
                return;
            }

            try
            {
                // Check for change
                $logFiles = (isset($log->files)) ? $log->files : [];

                foreach ($this->files as $extension => $files)
                {
                    $modified = false;
                    foreach ($files as $file)
                    {
                        // Find file in log
                        $checkFileStack = (isset($logFiles->{$file['extension']})) ? $logFiles->{$file['extension']} : [];

                        foreach ($checkFileStack as $checkFile)
                        {
                            if($file['basename'] == $checkFile->basename)
                            {
                                echo "Hit";
                                if($file['date'] == $checkFile->date)
                                {
                                    $file['modified'] = false;
                                }
                                else
                                {
                                    $file['modified'] = true;
                                    $modified = 1;
                                }
                            }
                            else
                            {
                                echo "=";
                                $file['modified'] = false;
                            }
                        }
//                        echo '<h1>checkFileStack</h1><pre>'; print_r($checkFileStack); echo '</pre>';
//                        echo '<h1>ff</h1><pre>'; print_r($log->files->{$file['extension']}); echo '</pre>';
//                        echo '<h1>Info</h1><pre>'; print_r($file); echo '</pre>';

                        echo $modified;

                    }
//                    echo '<h1>Info</h1><pre>'; print_r($extension); echo '</pre>';
//                    exit;
                }

                echo '<h1>Info</h1><pre>'; print_r($this->files); echo '</pre>';
                exit;



//                foreach ($logFiles as $logExtension => $logFilesOnExtension)
//                {
//                    $modified = false;
//                    foreach ($logFilesOnExtension as $logFile)
//                    {
//                        foreach ($this->files[$logExtension] as $key => $checkFile)
//                        {
//                            if ($checkFile['basename'] == $logFile->basename)
//                            {
//                                if($checkFile['date'] == $logFile->date)
//                                {
//                                    $modified = true;
//                                    $this->files[$logExtension][$key]['modified'] = 0;
//                                }
//                                else
//                                {
//                                    $this->files[$logExtension][$key]['modified'] = 1;
//                                }
//
//                            }
//                        }
//                        echo '<h1>Info</h1><pre>'; print_r($this->files[$logExtension]); echo '</pre>';
//                    }
//
//                    if($modified)
//                    {
//
//                    }

//                }
            }
            catch (\Exception $e)
            {
                unlink($logFile);
                return;
            }
        }
    }

    /*
     * Combine file by extension
     */
    public function combine()
    {
        $this->combine = [];

        echo '<h1>Info</h1><pre>'; print_r($this->files); echo '</pre>';
        exit;

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