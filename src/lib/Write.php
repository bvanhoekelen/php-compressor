<?php namespace PhpCompressor\Lib;

class Write {

    private $write;
    private $compressor;
    public $compressorFile;

    public function __construct(Compressor $compressor)
    {
        $this->write = false;
        $this->compressor = $compressor;
    }

    /*
     * Set destination
     */
    public function out()
    {
        $combine = $this->compressor->files->combine;
        $path = $this->compressor->config->pathDestination;
        $staticFileName = $this->compressor->config->compressFilename;
        $this->compressorFile = [];




        foreach ($combine as $extension => $content)
        {
            $this->compressor->performance->set(__FUNCTION__, $extension);
            $file = $path . $staticFileName . '.' . $extension;
            $content = $this->getSoftwareComment($extension) . $content;
            if( ! file_put_contents($file, $content))
                new ErrorMessage($this,
                    'Fail to write to file "' . $file . '"',
                    'Check if destination path and permission! <br>Note the folder that you has specify "' . $path . '" need a "compressor" folder.',
                    ['write to path' => $path]);
            else
                $this->compressorFile[] = ['extension' => $extension, 'file' => $file, 'date' => time()] ;

            $this->compressor->performance->stop(__FUNCTION__, $extension);
        }

//        $this->updateLog();
//
//        echo '<h1>Info</h1><pre>'; print_r($this->compressorFile); echo '</pre>';
//        exit;



    }

//    public function writeCombineFile()

    public function updateLog()
    {
        $logFile = $this->compressor->config->pathDestination . $this->compressor->config->logFile;

//        if( ! file_exists($logFile))
//        {
//            file_put_contents($logFile, "");
//        }

        // Log
        $log = [];
        $log['log'] = [];
        $log['version'] = Compressor::COMPRESSOR_VERSION;
        $log['pathDestination'] = $this->compressor->config->pathDestination;
        $log['pathLocations'] = $this->compressor->config->pathLocations;
        $log['compressorFile'] = $this->compressorFile;
        $log['files'] = $this->compressor->files->files;

        // Update log
        file_put_contents($logFile, json_encode($log));

        echo "Log Done";


    }

    /*
     * Get Software comment
     */
    public function getSoftwareComment($extension)
    {
        switch ($extension)
        {
            case 'css':
                return "/* css compile by php compressor (https://github.com/bvanhoekelen/php-compressor) on " . date('Y-m-d H:i:s') . " */\n";
                break;
            case 'js':
                return "/* js compile by php compressor (https://github.com/bvanhoekelen/php-compressor) on " . date('Y-m-d H:i:s') . "  */\n";
                break;
            default:
                new ErrorMessage($this, 'Extension support "' . $extension . '" in function "' . __FUNCTION__ . '" not exist');
                break;
            }
    }

}