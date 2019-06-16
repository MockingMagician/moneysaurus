<?php

namespace MockingMagician\Moneysaurus\Execptions;


use Throwable;

class MaxWorkingTimeException extends \Exception
{
    public function __construct(int $maxWorkingTime, int $code = 0, Throwable $previous = null)
    {
        $message = sprintf('Maximum working time of %s exceeded', $maxWorkingTime);
        parent::__construct($message, $code, $previous);
    }

}
