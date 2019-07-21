<?php

declare(strict_types=1);

/**
 * @author Marc MOREAU <moreau.marc.web@gmail.com>
 * @license https://github.com/MockingMagician/moneysaurus/blob/master/LICENSE.md Apache License 2.0
 * @link https://github.com/MockingMagician/moneysaurus/blob/master/README.md
 */

namespace MockingMagician\Moneysaurus\Tests;

use MockingMagician\Moneysaurus\Exceptions\DuplicateValueException;
use MockingMagician\Moneysaurus\Exceptions\NegativeQuantityException;
use MockingMagician\Moneysaurus\Exceptions\ValueNotExistException;
use MockingMagician\Moneysaurus\QuantifiedSystem;
use MockingMagician\Moneysaurus\System;

/**
 * @internal
 */
final class QuantifiedSystemTest extends \PHPUnit\Framework\TestCase
{
    /** @var QuantifiedSystem */
    private $system;

    /**
     * @throws DuplicateValueException
     * @throws NegativeQuantityException
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->system = new QuantifiedSystem();
        $this->system->addValue(0.1, 10);
    }

    /**
     * @throws DuplicateValueException
     * @throws NegativeQuantityException
     */
    public function test add value ok(): void
    {
        $this->system->addValue(0.2, 20);
        static::assertSame([0.2, 0.1], $this->system->getValues());
    }

    /**
     * @throws DuplicateValueException
     * @throws NegativeQuantityException
     */
    public function test add value ko(): void
    {
        $this->expectException(DuplicateValueException::class);
        $this->system->addValue(0.1, 10);
    }

    /**
     * @throws ValueNotExistException
     */
    public function test remove value ok(): void
    {
        $this->system->removeValue(0.1);
        static::assertSame([], $this->system->getValues());
    }

    /**
     * @throws ValueNotExistException
     */
    public function test remove value ko(): void
    {
        $this->expectException(ValueNotExistException::class);
        $this->system->removeValue(0.3);
    }

    /**
     * @throws ValueNotExistException
     */
    public function test set quantity ok(): void
    {
        $this->system->setQuantity(0.1, 15);
        static::assertSame(15, $this->system->getQuantity(0.1));
    }

    /**
     * @throws ValueNotExistException
     */
    public function test set quantity ko(): void
    {
        $this->expectException(ValueNotExistException::class);
        $this->system->setQuantity(0.2, 15);
    }

    /**
     * @throws ValueNotExistException
     */
    public function test get quantity ok(): void
    {
        static::assertSame(10, $this->system->getQuantity(0.1));
    }

    /**
     * @throws ValueNotExistException
     */
    public function test get quantity ko(): void
    {
        $this->expectException(ValueNotExistException::class);
        $this->system->getQuantity(0.2);
    }

    /**
     * @throws NegativeQuantityException
     * @throws ValueNotExistException
     */
    public function test construct with existing system(): void
    {
        $system = new System(...[0.1, 0.2]);
        $this->system = new QuantifiedSystem($system);
        static::assertEquals([0.2, 0.1], $this->system->getValues());
        static::assertSame(0, $this->system->getQuantity(0.1));
        static::assertSame(0, $this->system->getQuantity(0.2));
        $this->system->setQuantity(0.1, 10);
        static::assertSame(10, $this->system->getQuantity(0.1));
        $this->expectException(ValueNotExistException::class);
        $this->system->setQuantity(0.3, 10);
    }
}
