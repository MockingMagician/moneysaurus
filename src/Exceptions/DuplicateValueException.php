<?php

/**
 * @author Marc MOREAU <moreau.marc.web@gmail.com>
 * @license https://github.com/MockingMagician/moneysaurus/blob/master/LICENSE.md Apache License 2.0
 * @link https://github.com/MockingMagician/moneysaurus/blob/master/README.md
 */

namespace MockingMagician\Moneysaurus\Exceptions;

use Throwable;

class DuplicateValueException extends \Exception
{
    public const MESSAGE = '`%s` value already exist in current system.';

    public function __construct(float $duplicateValue, int $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf(self::MESSAGE, $duplicateValue), $code, $previous);
    }
}
