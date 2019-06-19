<?php

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
    public function test construct fail()
    {
        $this->expectException(NegativeQuantityException::class);
        new Couple(1.0, -5);
    }

    /**
     * @throws NegativeQuantityException
     */
    public function test decrement()
    {
        $this->assertEquals(1, $this->couple->getQuantity());
        $this->couple->decrement();
        $this->assertEquals(0, $this->couple->getQuantity());
    }

    /**
     * @throws NegativeQuantityException
     */
    public function test decrement fail()
    {
        $this->expectException(NegativeQuantityException::class);
        $this->couple->setQuantity(0);
        $this->couple->decrement();
    }

    public function test increment()
    {
        $this->assertEquals(1, $this->couple->getQuantity());
        $this->couple->increment();
        $this->assertEquals(2, $this->couple->getQuantity());
    }

    public function test plus()
    {
        $this->assertEquals(1, $this->couple->getQuantity());
        $this->couple->plus(5);
        $this->assertEquals(6, $this->couple->getQuantity());
    }

    /**
     * @throws NegativeQuantityException
     */
    public function test minus()
    {
        $this->couple->setQuantity(5);
        $this->couple->minus(3);
        $this->assertEquals(2, $this->couple->getQuantity());
    }

    /**
     * @throws NegativeQuantityException
     */
    public function test minus fail()
    {
        $this->expectException(NegativeQuantityException::class);
        $this->couple->minus(3);
    }
}
