<?php

/**
 * @author Marc MOREAU <moreau.marc.web@gmail.com>
 * @license https://github.com/MockingMagician/moneysaurus/blob/master/LICENSE.md Apache License 2.0
 * @link https://github.com/MockingMagician/moneysaurus/blob/master/README.md
 */

namespace MockingMagician\Moneysaurus\Algorithms;

use MockingMagician\Moneysaurus\Execptions\DuplicateValueException;
use MockingMagician\Moneysaurus\Execptions\MaxWorkingTimeException;
use MockingMagician\Moneysaurus\Execptions\ValueNotExistException;
use function MockingMagician\Moneysaurus\preventFromPhpInternalRoundingAfterOperate;
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
    private $maxWorkingTime;

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
        $string = json_encode($this->__debugInfo());

        return $string ? $string : '';
    }

    /**
     * @param float $amount
     *
     * @return QuantifiedSystem
     * @throws MaxWorkingTimeException
     * @throws ValueNotExistException
     * @throws DuplicateValueException
     */
    public function change(float $amount): QuantifiedSystem
    {
        $startTime = time();

        $this->dynamicRootNode = new DynamicRootNode($this->system, $amount);

        while (null === $this->dynamicRootNode->getSuccessOnChildren()) {
            $this->dynamicRootNode->nextRow();
            if (time() - $startTime > $this->maxWorkingTime) {
                throw new MaxWorkingTimeException($this->maxWorkingTime);
            }
        }

        $change = new QuantifiedSystem();
        $node = $this->dynamicRootNode->getSuccessOnChildren();

        do {
            $parent = $node->getParent();
            if (is_null($parent)) {
                break;
            }
            $amount = preventFromPhpInternalRoundingAfterOperate($parent->getChange(), $node->getChange());

            try {
                $quantity = $change->getQuantity($amount);
                $change->setQuantity($amount, $quantity + 1);
            } catch (ValueNotExistException $exception) {
                $change->addValue($amount, 1);
            }
        } while ($node = $node->getParent());

        return $change;
    }
}
