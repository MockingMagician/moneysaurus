<?php

/**
 * @author Marc MOREAU <moreau.marc.web@gmail.com>
 * @license https://github.com/MockingMagician/moneysaurus/blob/master/LICENSE.md Apache License 2.0
 * @link https://github.com/MockingMagician/moneysaurus/blob/master/README.md
 */

namespace MockingMagician\Moneysaurus;

use MockingMagician\Moneysaurus\Execptions\DuplicateValueException;
use MockingMagician\Moneysaurus\Execptions\ValueNotExistException;

class QuantifiedSystem
{
    /** @var int[] */
    private $quantities = [];
    /** @var System */
    private $system;

    /**
     * QuantifiedSystem constructor.
     *
     * @param null|System $system
     *
     * @throws ValueNotExistException
     */
    public function __construct(?System $system = null)
    {
        if (null === $system) {
            $system = new System();
        }

        $this->system = $system;

        foreach ($this->system->getValues() as $v) {
            $this->setQuantity($v, 0);
        }
    }

    /**
     * @codeCoverageIgnore
     */
    public function __debugInfo()
    {
        return [
            'system' => $this->system->__debugInfo(),
            'quantities' => $this->quantities,
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
     * @param float $value
     * @param int   $quantity
     *
     * @throws ValueNotExistException
     *
     * @return QuantifiedSystem
     */
    public function setQuantity(float $value, int $quantity): self
    {
        if (false === ($k = array_search($value, $this->system->getValues(), true))) {
            throw new ValueNotExistException($value);
        }

        $this->quantities[$k] = $quantity;

        return $this;
    }

    /**
     * @param float $value
     *
     * @throws ValueNotExistException
     *
     * @return int
     */
    public function getQuantity(float $value): int
    {
        if (false === ($k = array_search($value, $this->system->getValues(), true))) {
            throw new ValueNotExistException($value);
        }

        return $this->quantities[$k];
    }

    /**
     * @param float $value
     * @param int   $quantity
     *
     * @throws DuplicateValueException
     * @throws ValueNotExistException
     *
     * @return QuantifiedSystem
     */
    public function addValue(float $value, int $quantity): self
    {
        $this->system->addValue($value);
        $this->setQuantity($value, $quantity);

        return $this;
    }

    /**
     * @param float $value
     *
     * @throws ValueNotExistException
     *
     * @return QuantifiedSystem
     */
    public function removeValue(float $value): self
    {
        if (false === ($k = array_search($value, $this->system->getValues(), true))) {
            throw new ValueNotExistException($value);
        }
        $this->system->removeValue($value);
        unset($this->quantities[$k]);

        return $this;
    }

    /**
     * @return float[]
     */
    public function getValues(): array
    {
        return $this->system->getValues();
    }
}
