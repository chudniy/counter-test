<?php

namespace App\Builder;

use Exception;

class HandlerBuilder
{
    protected string $sourceFileName;

    protected $file;

    /**
     * @throws Exception
     */
    public function __construct($sourceFileName)
    {
        $this->sourceFileName = $sourceFileName;
        $this->prepareFile();
        $this->file = fopen($sourceFileName, 'a+');

        if($this->file === false) {
            throw new Exception("$sourceFileName file cannot be open for writing");
        }
    }

    /**
     * @return false|resource
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return string|null
     */
    public function getSourceFileName(): ?string
    {
        return $this->sourceFileName;
    }

    /**
     * @return void
     */
    public function close(): void
    {
        fclose($this->file);
    }

    /**
     * check and delete main App before execution
     */
    private function prepareFile() : void
    {
        //delete result file if it already exists
        if(file_exists($this->getSourceFileName())) {
            unlink($this->getSourceFileName());
        }
    }

    /**
     * write message in result file
     * @param string $message
     */
    public function write(string $message) : void
    {
        $message = $message."\r\n";
        fwrite($this->getFile(), $message);
    }
}