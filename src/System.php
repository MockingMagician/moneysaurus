<?php

declare(strict_types=1);

/**
 * @author Marc MOREAU <moreau.marc.web@gmail.com>
 * @license https://github.com/MockingMagician/moneysaurus/blob/master/LICENSE.md Apache License 2.0
 * @link https://github.com/MockingMagician/moneysaurus/blob/master/README.md
 */

namespace MockingMagician\Moneysaurus;

use MockingMagician\Moneysaurus\Exceptions\DuplicateValueException;
use MockingMagician\Moneysaurus\Exceptions\ValueNotExistException;

class System
{
    /** @var float[] */
    private $values = [];

    public function __construct(float ...$values)
    {
        $this->values = $values;
    }

    /**
     * @codeCoverageIgnore
     */
    public function __debugInfo()
    {
        return [
            'values' => $this->getValues(),
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
     *
     * @throws DuplicateValueException
     *
     * @return System
     */
    public function addValue(float $value): self
    {
        if (false !== array_search($value, $this->values, true)) {
            throw new DuplicateValueException($value);
        }
        $this->values[] = $value;

        return $this;
    }

    /**
     * @param float $value
     *
     * @throws ValueNotExistException
     *
     * @return System
     */
    public function removeValue(float $value): self
    {
        if (false === ($k = array_search($value, $this->values, true))) {
            throw new ValueNotExistException($value);
        }
        unset($this->values[$k]);

        return $this;
    }

    /**
     * @return float[]
     */
    public function getValues(): array
    {
        return $this->values;
    }
}
