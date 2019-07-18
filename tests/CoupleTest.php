<?php

declare(strict_types=1);

/**
 * @author Marc MOREAU <moreau.marc.web@gmail.com>
 * @license https://github.com/MockingMagician/moneysaurus/blob/master/LICENSE.md Apache License 2.0
 * @link https://github.com/MockingMagician/moneysaurus/blob/master/README.md
 */

namespace MockingMagician\Moneysaurus\Tests;

use MockingMagician\Moneysaurus\Couple;
use MockingMagician\Moneysaurus\Exceptions\NegativeQuantityException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class CoupleTest extends TestCase
{
    /** @var Couple */
    private $couple;

    /**
     * @throws NegativeQuantityException
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->couple = new Couple(0.1, 1);
    }

    /**
     * @throws NegativeQuantityException
     */
    public function test construct fail(): void
    {
        $this->expectException(NegativeQuantityException::class);
        new Couple(1.0, -5);
    }

    /**
     * @throws NegativeQuantityException
     */
    public function test decrement(): void
    {
        static::assertEquals(1, $this->couple->getQuantity());
        $this->couple->decrement();
        static::assertEquals(0, $this->couple->getQuantity());
    }

    /**
     * @throws NegativeQuantityException
     */
    public function test decrement fail(): void
    {
        $this->expectException(NegativeQuantityException::class);
        $this->couple->setQuantity(0);
        $this->couple->decrement();
    }

    public function test increment(): void
    {
        static::assertEquals(1, $this->couple->getQuantity());
        $this->couple->increment();
        static::assertEquals(2, $this->couple->getQuantity());
    }

    public function test plus(): void
    {
        static::assertEquals(1, $this->couple->getQuantity());
        $this->couple->plus(5);
        static::assertEquals(6, $this->couple->getQuantity());
    }

    /**
     * @throws NegativeQuantityException
     */
    public function test minus(): void
    {
        $this->couple->setQuantity(5);
        $this->couple->minus(3);
        static::assertEquals(2, $this->couple->getQuantity());
    }

    /**
     * @throws NegativeQuantityException
     */
    public function test minus fail(): void
    {
        $this->expectException(NegativeQuantityException::class);
        $this->couple->minus(3);
    }
}
