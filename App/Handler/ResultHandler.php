<?php

namespace App\Handler;

class ResultHandler extends BaseHandler
{
    protected ?string $sourceFileName = 'result.csv';

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