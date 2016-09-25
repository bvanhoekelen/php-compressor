<?php namespace PhpCompressor\Lib;

class Files {

    private $compressor;            // Supper class
    public $files;                  // Store filse
    public $combine;

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
                    if (isset($fileInfo['extension']) and in_array($fileInfo['extension'], $extensions))
                    {
                        $fileInfo['date'] = filemtime($path . $file);
                        $fileInfo['path'] = $path;
                        $fileInfo['pathAndFile'] = $path . $file;
                        $fileInfo['modified'] = 1;
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
        $log = $this->compressor->config->getLogFileContent();

        // Exit if log not exist
        if( ! $log)
            return false;

        // Check for change
        $logFiles = (isset($log->files)) ? $log->files : [];

        // Find logFile
        function findLogFile($logFiles, $file)
        {
            $logFiles = $logFiles;
            $checkFileStack = (isset($logFiles->{$file['extension']})) ? $logFiles->{$file['extension']} : [];

            foreach ($checkFileStack as $logFile)
            {
                if($logFile->pathAndFile == $file['pathAndFile'])
                {
                    return $logFile;
                }
            }

            return false;
        }

        try
        {
            foreach ($this->files as $extension => $files)
            {
                foreach ($files as $key => $file)
                {
                    // Find file in log
                    $logFile = findLogFile($logFiles, $file);

                    if($logFile and $logFile->date == $file['date'])
                        $this->files[$extension][$key]['modified'] = false;
                    else
                        $this->files[$extension][$key]['modified'] = true;
                }
            }
        }
        catch (\Exception $e)
        {
            return false;
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
            // Combine only if file is change
            $extensionModified = 0;
            foreach ($files as $file)
            {
                if($file['modified'] != false) $extensionModified = 1;
            }

            if( $extensionModified and count($files) )
            {
                $this->compressor->performance->set(__FUNCTION__, $extension);
                $this->combine[$extension] = "";
                foreach ($files as $file)
                {
                    $this->combine[$extension] .= "\n/* add file " . $file['filename'] . "." . $file['extension'] . " */\n";
                    $this->combine[$extension] .= file_get_contents($file['pathAndFile']);
                }
                $this->compressor->performance->stop(__FUNCTION__, $extension);

                $this->combine[$extension] .= "\n/* \n * Take." . $file['extension'] . " done in " . $this->compressor->performance->get('μs', __FUNCTION__, $extension) . " \n */\n";
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