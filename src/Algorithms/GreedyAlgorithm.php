<?php

namespace MockingMagician\Moneysaurus\Algorithms;

use MockingMagician\Moneysaurus\Execptions\ChangeAsLeftOver;
use MockingMagician\Moneysaurus\Execptions\DuplicateValueException;
use MockingMagician\Moneysaurus\QuantifiedSystem;

class GreedyAlgorithm
{
    /** @var QuantifiedSystem */
    private $system;

    public function __construct(QuantifiedSystem $system)
    {
        $this->system = $system;
    }

    /**
     * @param float $amount
     *
     * @throws \MockingMagician\Moneysaurus\Execptions\ValueNotExistException
     * @throws ChangeAsLeftOver
     *
     * @return QuantifiedSystem
     */
    public function change(float $amount): QuantifiedSystem
    {
        $change = new QuantifiedSystem();

        $values = $this->system->getValues();
        $values = arsort($values);

        foreach ($values as $value) {
            $quantity = $this->system->getQuantity($value);
            while ($amount > 0 && $quantity > 0) {
                if ($amount - $value < 0) {
                    break;
                }
                $amount -= $value;
                --$quantity;

                try {
                    $change->addValue($value, 1);
                } catch (DuplicateValueException $e) {
                    $change->setQuantity($value, $change->getQuantity($value) + 1);
                }
            }
        }

        if ($amount > 0) {
            throw new ChangeAsLeftOver($amount, $change);
        }

        return $change;
    }
}
