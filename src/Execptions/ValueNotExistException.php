<?php

namespace MockingMagician\Moneysaurus\Execptions;

use Throwable;

class ValueNotExistException extends \Exception
{
    public const MESSAGE = '`%s` value not existing in current system.';

    public function __construct(float $value, int $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf(self::MESSAGE, $value), $code, $previous);
    }
}
