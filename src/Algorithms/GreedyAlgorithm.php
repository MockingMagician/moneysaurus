<?php

/**
 * @author Marc MOREAU <moreau.marc.web@gmail.com>
 * @license https://github.com/MockingMagician/moneysaurus/blob/master/LICENSE.md Apache License 2.0
 * @link https://github.com/MockingMagician/moneysaurus/blob/master/README.md
 */

namespace MockingMagician\Moneysaurus\Algorithms;

use MockingMagician\Moneysaurus\Execptions\ChangeAsLeftOver;
use MockingMagician\Moneysaurus\Execptions\DuplicateValueException;
use MockingMagician\Moneysaurus\Execptions\ValueNotExistException;
use MockingMagician\Moneysaurus\QuantifiedSystem;

class GreedyAlgorithm implements ChangeInterface
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
        return json_encode($this->__debugInfo());
    }

    /**
     * @param float $amount
     *
     * @throws ValueNotExistException
     * @throws ChangeAsLeftOver
     *
     * @return QuantifiedSystem
     */
    public function change(float $amount): QuantifiedSystem
    {
        $change = new QuantifiedSystem();

        $values = $this->system->getValues();
        arsort($values);

        foreach ($values as $value) {
            $quantity = $this->system->getQuantity($value);
            while ($amount > 0 && $quantity > 0) {
                if ($amount - $value < 0) {
                    break;
                }
                $amount -= $value;
                // this part prevent from php internal rounding after operate
                $exp = explode('.', $amount);
                $d = isset($exp[1]) ? $exp[1] : '';
                $l = mb_strlen($d);
                $amount = round($amount, $l);
                // --- end of prevent ---
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
