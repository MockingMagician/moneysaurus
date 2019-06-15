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
    /** @var int */
    const DEFAULT_TIME = 10;

    private $system;
    /** @var DynamicRootNode */
    private $dynamicRootNode;
    /**
     * @var int
     */
    private $maxThinkingTime;

    public function __construct(QuantifiedSystem $system, int $maxThinkingTime = self::DEFAULT_TIME)
    {
        $this->system = $system;
        $this->maxThinkingTime = $maxThinkingTime;
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
        $startTime = time();

        $this->dynamicRootNode = new DynamicRootNode($this->system, $amount);

        while (null === $this->dynamicRootNode->getSuccessOnChildren()) {
            $this->dynamicRootNode->nextRow();
            var_dump(\count($this->dynamicRootNode->getLastRow()));
            if ($startTime + self::DEFAULT_TIME > $this->maxThinkingTime) {
                die;
            }
            if (\count($this->dynamicRootNode->getLastRow()) > 10000) {
                die();
            }
        }

        return new QuantifiedSystem();
    }
}
