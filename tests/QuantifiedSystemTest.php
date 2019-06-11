<?php

namespace MockingMagician\Moneysaurus\Tests;

use MockingMagician\Moneysaurus\Execptions\DuplicateValueException;
use MockingMagician\Moneysaurus\Execptions\ValueNotExistException;
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
     * @throws ValueNotExistException
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->system = new QuantifiedSystem();
        $this->system->addValue(0.1, 10);
    }

    /**
     * @throws DuplicateValueException
     * @throws ValueNotExistException
     */
    public function test add value ok()
    {
        $this->system->addValue(0.2, 20);
        $this->assertSame([0.1, 0.2], $this->system->getValues());
    }

    /**
     * @throws DuplicateValueException
     * @throws ValueNotExistException
     */
    public function test add value ko()
    {
        $this->expectException(DuplicateValueException::class);
        $this->system->addValue(0.1, 10);
    }

    /**
     * @throws ValueNotExistException
     */
    public function test remove value ok()
    {
        $this->system->removeValue(0.1);
        $this->assertSame([], $this->system->getValues());
    }

    /**
     * @throws ValueNotExistException
     */
    public function test remove value ko()
    {
        $this->expectException(ValueNotExistException::class);
        $this->system->removeValue(0.3);
    }

    /**
     * @throws ValueNotExistException
     */
    public function test set quantity ok()
    {
        $this->system->setQuantity(0.1, 15);
        $this->assertSame(15, $this->system->getQuantity(0.1));
    }

    /**
     * @throws ValueNotExistException
     */
    public function test set quantity ko()
    {
        $this->expectException(ValueNotExistException::class);
        $this->system->setQuantity(0.2, 15);
    }

    /**
     * @throws ValueNotExistException
     */
    public function test get quantity ok()
    {
        $this->assertSame(10, $this->system->getQuantity(0.1));
    }

    /**
     * @throws ValueNotExistException
     */
    public function test get quantity ko()
    {
        $this->expectException(ValueNotExistException::class);
        $this->system->getQuantity(0.2);
    }

    /**
     * @throws ValueNotExistException
     */
    public function test construct with existing system()
    {
        $system = new System(...[0.1, 0.2]);
        $this->system = new QuantifiedSystem($system);
        $this->assertSame([0.1, 0.2], $this->system->getValues());
        $this->assertSame(0, $this->system->getQuantity(0.1));
        $this->assertSame(0, $this->system->getQuantity(0.2));
        $this->system->setQuantity(0.1, 10);
        $this->assertSame(10, $this->system->getQuantity(0.1));
        $this->expectException(ValueNotExistException::class);
        $this->system->setQuantity(0.3, 10);
    }
}
