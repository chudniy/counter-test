<?php
namespace App;

use App\Enum\CountOperation;
use App\Handler\LogHandler;
use App\Handler\ResultHandler;
use Exception;

class Counter
{
    private ResultHandler $resultHandler;

    private LogHandler $logHandler;

    private string $file;

    private string $operation;


    /**
     * Counter constructor.
     * @throws Exception
     */
    public function __construct(string $operation, string $file)
    {
        $this->operation = $operation;
        $this->file = $file;
        $this->resultHandler = new ResultHandler();
        $this->logHandler = new LogHandler();
    }

    /**
     * destructor
     */
    public function __destruct()
    {
        $this->closeHandlers();
    }

    /**
     * @return string
     */
    public function getFile() : string
    {
        return $this->file;
    }

    /**
     * @return string
     */
    public function getOperation() : string
    {
        return $this->operation;
    }


    /**
     * main function, execute main code
     *
     * @throws Exception
     */
    public function count(): void
    {
        $operation = $this->getOperation();
        $this->validateResourceFile();

        $this->logHandler->write("Started $operation operation");

        $handle = fopen($this->getFile(),'r');
        while ( ($line = fgetcsv($handle) ) !== FALSE ) {
            [$value1, $value2] = $this->prepareValues($line[0]);
            $result = $this->countResult($value1, $value2);
            if($this->isResultValid($result)) {
                $this->resultHandler->writeSuccessResult($value1, $value2, $result);
            } else {
                $this->wrongResultLog($value1, $value2);
            }
        }

        $this->logHandler->write("Finished $operation operation");
    }

    /**
     * write in logs if numbers give wrong result
     * @param int $value1
     * @param int $value2
     * @throws Exception
     */
    private function wrongResultLog(int $value1, int $value2) : void
    {
        $message = "numbers $value1 and $value2 are wrong";
        $this->logHandler->write($message);
    }

    /**
     * validate if result is valid
     * @param null|int|float $result
     * @return bool
     */
    private function isResultValid(null|int|float $result) : bool
    {
        if($result && $result > 0)
            return true;

        return false;
    }

    /**
     * count result
     *
     * @param int $value1
     * @param int $value2
     *
     * @return int|float|null
     * @throws Exception
     */
    private function countResult(int $value1, int $value2): null|int|float
    {
        switch ($this->getOperation()) {
            case CountOperation::Plus->getName():  return $value1 + $value2;
            case CountOperation::Minus->getName():  return $value1 - $value2;
            case CountOperation::Multiply->getName():  return $value1 * $value2;
            case CountOperation::Division->getName():
                if ($value2 === 0) {
                    return null;
                }
                return $value1 / $value2;
            default: throw new Exception('Wrong action is selected');
        }
    }

    /**
     * prepare numbers before action, explode it from csv string
     * @param string $line
     * @return array
     */
    private function prepareValues(string $line) : array
    {
        $line = explode(";", $line);
        $value1 = $this->prepareNumber($line[0]);
        $value2 = $this->prepareNumber($line[1]);
        return [$value1, $value2];

    }

    /**
     * prepare number before action
     * @param string $value
     * @return int
     */
    private function prepareNumber(string $value) : int
    {
        $value = trim($value);

        return intval($value);
    }

    /**
     * @return void
     * @throws Exception
     */
    private function validateResourceFile() : void {
        if($this->getFile() === null) {
            throw new Exception("Please define file with data");
        }

        if(!file_exists($this->getFile())) {
            throw new Exception("Please define file with data");
        }

        if(!is_readable($this->getFile())) {
            throw new Exception("We have not rights to read this file");
        }
    }

    /**
     * close opened handlers
     */
    private function closeHandlers() : void
    {
        $this->resultHandler->close();
        $this->logHandler->close();
    }
}