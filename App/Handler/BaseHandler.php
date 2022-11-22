<?php

namespace App\Handler;

use Exception;

abstract class BaseHandler
{
    protected ?string $sourceFileName = null;

    protected $file;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $fileName = $this->getSourceFileName();
        $this->prepareFile();
        $this->file = fopen($fileName, 'a+');

        if($this->file === false) {
            throw new Exception("$fileName file cannot be open for writing");
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

    /**
     * prepare info and save it in result file
     * @param int $value1
     * @param int $value2
     * @param null|int|float $result
     */
    public function writeSuccessResult(int $value1, int $value2, null|int|float $result) : void
    {
        $message = implode(";", [$value1, $value2, $result]);
        $this->write($message);
    }
}