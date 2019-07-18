<?php

declare(strict_types=1);

/**
 * @author Marc MOREAU <moreau.marc.web@gmail.com>
 * @license https://github.com/MockingMagician/moneysaurus/blob/master/LICENSE.md Apache License 2.0
 * @link https://github.com/MockingMagician/moneysaurus/blob/master/README.md
 */

namespace MockingMagician\Moneysaurus\Algorithms;

use MockingMagician\Moneysaurus\Exceptions\DuplicateValueException;
use MockingMagician\Moneysaurus\Exceptions\NegativeQuantityException;
use MockingMagician\Moneysaurus\Exceptions\ValueNotExistException;
use function MockingMagician\Moneysaurus\Helpers\PreventFromPhpBadStockingAfterOperate\minus;
use MockingMagician\Moneysaurus\QuantifiedSystem;

class SuperGreedyAlgorithm implements ChangeInterface
{
    /** @var QuantifiedSystem */
    private $system;

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
        $string = \json_encode($this->__debugInfo());

        return $string ? $string : '';
    }

    /**
     * @param float $amount
     *
     * @throws NegativeQuantityException
     *
     * @return QuantifiedSystem
     */
    public function change(float $amount): QuantifiedSystem
    {
        // TODO: Implement change() method.
        return new QuantifiedSystem();
    }

    /**
     * @param float                 $amount
     * @param QuantifiedSystem      $system
     * @param null|QuantifiedSystem $change
     *
     * @throws NegativeQuantityException
     * @throws ValueNotExistException
     */
    private function reduce(float $amount, QuantifiedSystem $system, QuantifiedSystem &$change = null): void
    {
        $change = null !== $change ? $change : new QuantifiedSystem();
        $system = clone $system;

        $values = $system->getValues();
        \rsort($values);

        $currentValue = \current($values);
        $neededQuantity = \floor($amount / $currentValue) - 1;
        $quantity = $this->system->getQuantity($currentValue);
        if ($neededQuantity > $quantity) {
            $rest = minus($amount, $quantity * $currentValue);
            $system->setQuantity($currentValue, 0);

            $change->setQuantity($currentValue, $quantity);

            $this->reduce($rest, $system, $change);
        }

        foreach ($values as $value) {
            $quantity = $this->system->getQuantity($value);
            while ($amount > 0 && $quantity > 0) {
                if ($amount - $value < 0) {
                    break;
                }

                $amount = minus($amount, $value);
                --$quantity;

                try {
                    $change->addValue($value, 1);
                } catch (DuplicateValueException $e) {
                    $change->setQuantity($value, $change->getQuantity($value) + 1);
                }
            }
        }
    }
}
