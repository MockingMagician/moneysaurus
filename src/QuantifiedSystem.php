<?php

/**
 * @author Marc MOREAU <moreau.marc.web@gmail.com>
 * @license https://github.com/MockingMagician/moneysaurus/blob/master/LICENSE.md Apache License 2.0
 * @link https://github.com/MockingMagician/moneysaurus/blob/master/README.md
 */

namespace MockingMagician\Moneysaurus;

use MockingMagician\Moneysaurus\Exceptions\DuplicateValueException;
use MockingMagician\Moneysaurus\Exceptions\NegativeQuantityException;
use MockingMagician\Moneysaurus\Exceptions\ValueNotExistException;

class QuantifiedSystem
{
    /** @var Couple[] */
    private $couples = [];

    /**
     * QuantifiedSystem constructor.
     *
     * @param null|System $system
     *
     * @throws NegativeQuantityException
     */
    public function __construct(?System $system = null)
    {
        if (null === $system) {
            return;
        }

        foreach ($system->getValues() as $v) {
            $this->couples[] = new Couple($v);
        }
    }

    /**
     * @codeCoverageIgnore
     */
    public function __debugInfo()
    {
        return [
            'couples' => $this->couples,
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

    public function __clone()
    {
        foreach ($this->couples as $k => $couple) {
            $this->couples[$k] = clone $couple;
        }
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
        $this->getCouple($value)->setQuantity($quantity);

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
        return $this->getCouple($value)->getQuantity();
    }

    /**
     * @param float $value
     * @param int   $quantity
     *
     * @throws DuplicateValueException
     * @throws NegativeQuantityException
     *
     * @return QuantifiedSystem
     */
    public function addValue(float $value, int $quantity): self
    {
        try {
            $this->getCouple($value);
        } catch (ValueNotExistException $exception) {
            $this->couples[] = new Couple($value, $quantity);

            return $this;
        }

        throw new DuplicateValueException($value);
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
        $key = $this->getKeyCouple($value);
        unset($this->couples[$key]);

        return $this;
    }

    /**
     * @return float[]
     */
    public function getValues(): array
    {
        $values = [];
        foreach ($this->couples as $couple) {
            $values[] = $couple->getValue();
        }
        rsort($values);

        return $values;
    }

    /**
     * @param float $value
     *
     * @throws ValueNotExistException
     *
     * @return Couple
     */
    private function getCouple(float $value): Couple
    {
        return $this->couples[$this->getKeyCouple($value)];
    }

    /**
     * @param float $value
     *
     * @throws ValueNotExistException
     *
     * @return int
     */
    private function getKeyCouple(float $value): int
    {
        foreach ($this->couples as $k => $couple) {
            if ($value === $couple->getValue()) {
                return $k;
            }
        }

        throw new ValueNotExistException($value);
    }
}
