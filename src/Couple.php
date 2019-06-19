<?php

declare(strict_types=1);

/**
 * @author Marc MOREAU <moreau.marc.web@gmail.com>
 * @license https://github.com/MockingMagician/moneysaurus/blob/master/LICENSE.md Apache License 2.0
 * @link https://github.com/MockingMagician/moneysaurus/blob/master/README.md
 */

namespace MockingMagician\Moneysaurus;

use MockingMagician\Moneysaurus\Exceptions\NegativeQuantityException;

class Couple
{
    private $value;
    private $quantity;

    /**
     * Couple constructor.
     *
     * @param float $value
     * @param int   $quantity
     *
     * @throws NegativeQuantityException
     */
    public function __construct(float $value, int $quantity = 0)
    {
        if (0 > $quantity) {
            throw new NegativeQuantityException($quantity);
        }
        $this->value = $value;
        $this->quantity = $quantity;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function increment(): self
    {
        ++$this->quantity;

        return $this;
    }

    /**
     * @throws NegativeQuantityException
     */
    public function decrement(): self
    {
        if (0 > $this->quantity - 1) {
            throw new NegativeQuantityException(-1);
        }

        --$this->quantity;

        return $this;
    }

    public function plus(int $quantity)
    {
        $this->quantity += $quantity;

        return $this;
    }

    /**
     * @param int $quantity
     *
     * @throws NegativeQuantityException
     *
     * @return Couple
     */
    public function minus(int $quantity): self
    {
        if (0 > $this->quantity - $quantity) {
            throw new NegativeQuantityException($this->quantity - $quantity);
        }

        $this->quantity -= $quantity;

        return $this;
    }
}
