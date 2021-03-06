<?php

declare(strict_types=1);

/**
 * @author Marc MOREAU <moreau.marc.web@gmail.com>
 * @license https://github.com/MockingMagician/moneysaurus/blob/master/LICENSE.md Apache License 2.0
 * @link https://github.com/MockingMagician/moneysaurus/blob/master/README.md
 */

namespace MockingMagician\Moneysaurus\Algorithms;

use MockingMagician\Moneysaurus\Algorithms\Dynamic\DynamicRootNode;
use MockingMagician\Moneysaurus\Exceptions\DuplicateValueException;
use MockingMagician\Moneysaurus\Exceptions\MaxWorkingTimeException;
use MockingMagician\Moneysaurus\Exceptions\NegativeQuantityException;
use MockingMagician\Moneysaurus\Exceptions\ValueNotExistException;
use function MockingMagician\Moneysaurus\Helpers\PreventFromPhpBadStockingAfterOperate\minus;
use MockingMagician\Moneysaurus\QuantifiedSystem;

/**
 * Class DynamicAlgorithm.
 *
 * Dynamic algorithm is polynomial so it can consume lot of memory and time
 * A max working time default value is set to 1 sec
 * It is not recommended to use this algorithm
 * It is better to use Greedy algorithm for canonical money
 * Or if you not sure about canonical from your money system, use...
 */
class DynamicAlgorithm implements ChangeInterface
{
    /** @var int */
    const DEFAULT_TIME = 1;

    private $system;
    private $maxWorkingTime;

    /** @var DynamicRootNode */
    private $dynamicRootNode;

    public function __construct(QuantifiedSystem $system, int $maxWorkingTime = self::DEFAULT_TIME)
    {
        $this->system = $system;
        $this->maxWorkingTime = $maxWorkingTime;
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
        $string = \json_encode($this->__debugInfo());

        return $string ? $string : '';
    }

    /**
     * @param float $amount
     *
     * @throws MaxWorkingTimeException
     * @throws ValueNotExistException
     * @throws DuplicateValueException
     * @throws NegativeQuantityException
     *
     * @return QuantifiedSystem
     */
    public function change(float $amount): QuantifiedSystem
    {
        $startTime = \time();

        $this->dynamicRootNode = new DynamicRootNode($this->system, $amount);

        while (null === ($node = $this->dynamicRootNode->getSuccessOnChildren())) {
            $this->dynamicRootNode->nextRow();
            if (\time() - $startTime > $this->maxWorkingTime) {
                throw new MaxWorkingTimeException($this->maxWorkingTime);
            }
        }

        $change = new QuantifiedSystem();

        while (null !== $node && $parent = $node->getParent()) {
            $amount = minus($parent->getChange(), $node->getChange());

            try {
                $quantity = $change->getQuantity($amount);
                $change->setQuantity($amount, $quantity + 1);
            } catch (ValueNotExistException $exception) {
                $change->addValue($amount, 1);
            }
            $node = $parent;
        }

        return $change;
    }
}
