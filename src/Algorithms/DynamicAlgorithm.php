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
    /** @var DynamicRootNode */
    private $dynamicRootNode;

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
            'dynamicRootNode' => $this->dynamicRootNode,
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    public function __toString()
    {
        $string = json_encode($this->__debugInfo());

        return $string ? $string : '';
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
        $this->dynamicRootNode = new DynamicRootNode($this->system, $amount);

        while (is_null($this->dynamicRootNode->getSuccessOnChildren())) {
            $this->dynamicRootNode->nextRow();
        }

        return new QuantifiedSystem();
    }
}
