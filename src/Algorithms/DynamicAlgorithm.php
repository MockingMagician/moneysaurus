<?php

/**
 * @author Marc MOREAU <moreau.marc.web@gmail.com>
 * @license https://github.com/MockingMagician/moneysaurus/blob/master/LICENSE.md Apache License 2.0
 * @link https://github.com/MockingMagician/moneysaurus/blob/master/README.md
 */

namespace MockingMagician\Moneysaurus\Algorithms;

use MockingMagician\Moneysaurus\Execptions\ValueNotExistException;
use MockingMagician\Moneysaurus\QuantifiedSystem;

class DynamicAlgorithm implements ChangeInterface
{
    private $system;
    /** @var DynamicNode */
    private $dynamicNode;

    public function __construct(QuantifiedSystem $system)
    {
        $this->system = $system;
    }

    /**
     * @codeCoverageIgnore
     */
    public function __debugInfo()
    {
        return [
            'system' => $this->system->__debugInfo(),
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    public function __toString()
    {
        return json_encode($this->__debugInfo());
    }

    /**
     * @param float $amount
     *
     * @throws ValueNotExistException
     *
     * @return QuantifiedSystem
     */
    public function change(float $amount): QuantifiedSystem
    {
        $this->dynamicNode = new DynamicNode($this->system, $amount);
        $this->dynamicNode->getMostEfficientChange();
    }
}
