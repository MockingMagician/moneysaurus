<?php

/**
 * @author Marc MOREAU <moreau.marc.web@gmail.com>
 * @license https://github.com/MockingMagician/moneysaurus/blob/master/LICENSE.md Apache License 2.0
 * @link https://github.com/MockingMagician/moneysaurus/blob/master/README.md
 */

namespace MockingMagician\Moneysaurus\Execptions;

use MockingMagician\Moneysaurus\QuantifiedSystem;
use Throwable;

class ChangeAsLeftOver extends \Exception
{
    public const MESSAGE = 'Change as `%s` of left-over.';
    private $leftOver;
    private $system;

    public function __construct(float $leftOver, QuantifiedSystem $system, int $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf(self::MESSAGE, $leftOver), $code, $previous);
        $this->leftOver = $leftOver;
        $this->system = $system;
    }

    public function getLeftOver(): float
    {
        return $this->leftOver;
    }

    public function getSystem(): QuantifiedSystem
    {
        return $this->system;
    }
}
