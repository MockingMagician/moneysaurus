<?php

declare(strict_types=1);

/**
 * @author Marc MOREAU <moreau.marc.web@gmail.com>
 * @license https://github.com/MockingMagician/moneysaurus/blob/master/LICENSE.md Apache License 2.0
 * @link https://github.com/MockingMagician/moneysaurus/blob/master/README.md
 */

use MockingMagician\Moneysaurus\Algorithms\DynamicAlgorithm;
use MockingMagician\Moneysaurus\Exceptions\DuplicateValueException;
use MockingMagician\Moneysaurus\Exceptions\MaxWorkingTimeException;
use MockingMagician\Moneysaurus\Exceptions\NegativeQuantityException;
use MockingMagician\Moneysaurus\Exceptions\ValueNotExistException;
use MockingMagician\Moneysaurus\QuantifiedSystem;
use MockingMagician\Moneysaurus\System;

/**
 * @internal
 * @coversNothing
 */
final class DynamicAlgorithmTest extends PHPUnit\Framework\TestCase
{
    /** @var System */
    private $euroSystem;
    /** @var QuantifiedSystem */
    private $quantifiedSystem;
    /** @var DynamicAlgorithm */
    private $dynamic;

    /**
     * @throws ValueNotExistException
     * @throws NegativeQuantityException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->euroSystem = new System(...[
            0.01, 0.02, 0.05, 0.10, 0.20, 0.50, 1.0, 2.0,
            5.0, 10.0, 20.0, 50.0, 100.0, 200.0, 500.0,
        ]);

        $this->quantifiedSystem = new QuantifiedSystem($this->euroSystem);
        $this->quantifiedSystem->setQuantity(0.5, 3);
        $this->quantifiedSystem->setQuantity(1.0, 3);
        $this->quantifiedSystem->setQuantity(2.0, 1);
        $this->quantifiedSystem->setQuantity(5.0, 1);

        $this->dynamic = new DynamicAlgorithm($this->quantifiedSystem);
    }

    /**
     * @throws DuplicateValueException
     * @throws MaxWorkingTimeException
     * @throws NegativeQuantityException
     * @throws ValueNotExistException
     */
    public function test change(): void
    {
        $expected = (new QuantifiedSystem())
            ->addValue(0.5, 1)
            ->addValue(1.0, 2)
            ->addValue(2.0, 1)
            ->addValue(5.0, 1)
        ;
        $actual = $this->dynamic->change(9.5);
        static::assertEquals($expected, $actual);
    }

    /**
     * @throws DuplicateValueException
     * @throws MaxWorkingTimeException
     * @throws NegativeQuantityException
     * @throws ValueNotExistException
     */
    public function test change too long(): void
    {
        $this->quantifiedSystem = new QuantifiedSystem($this->euroSystem);
        $this->quantifiedSystem->setQuantity(0.05, 3);
        $this->quantifiedSystem->setQuantity(0.2, 3);
        $this->quantifiedSystem->setQuantity(0.5, 3);
        $this->quantifiedSystem->setQuantity(1, 3);
        $this->quantifiedSystem->setQuantity(2, 3);
        $this->quantifiedSystem->setQuantity(5, 3);
        $this->quantifiedSystem->setQuantity(50, 3);
        $this->quantifiedSystem->setQuantity(100, 3);

        $this->expectException(MaxWorkingTimeException::class);

        $this->dynamic->change(10.35);
    }
}
