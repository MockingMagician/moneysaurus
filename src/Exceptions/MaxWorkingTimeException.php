<?php

declare(strict_types=1);

/**
 * @author Marc MOREAU <moreau.marc.web@gmail.com>
 * @license https://github.com/MockingMagician/moneysaurus/blob/master/LICENSE.md Apache License 2.0
 * @link https://github.com/MockingMagician/moneysaurus/blob/master/README.md
 */

namespace MockingMagician\Moneysaurus\Exceptions;

use Throwable;

class MaxWorkingTimeException extends \Exception
{
    public function __construct(int $maxWorkingTime, int $code = 0, Throwable $previous = null)
    {
        $message = \sprintf('Maximum working time of %s exceeded', $maxWorkingTime);
        parent::__construct($message, $code, $previous);
    }
}
