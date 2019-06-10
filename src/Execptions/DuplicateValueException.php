<?php

namespace MockingMagician\Moneysaurus\Execptions;

use Throwable;

class DuplicateValueException extends \Exception
{
    public const MESSAGE = '`%s` value already exist in current system.';

    public function __construct(float $duplicateValue, int $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf(self::MESSAGE, $duplicateValue), $code, $previous);
    }
}
