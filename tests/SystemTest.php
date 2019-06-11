<?php

namespace MockingMagician\Moneysaurus\Tests;

use MockingMagician\Moneysaurus\Execptions\DuplicateValueException;
use MockingMagician\Moneysaurus\Execptions\ValueNotExistException;
use MockingMagician\Moneysaurus\System;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class SystemTest extends TestCase
{
    /** @var System */
    private $system;

    /**
     * @throws DuplicateValueException
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->system = new System();
        $this->system->addValue(0.1);
    }

    /**
     * @throws DuplicateValueException
     */
    public function test add value ok()
    {
        $this->system->addValue(0.2);
        $this->assertSame([0.1, 0.2], $this->system->getValues());
    }

    /**
     * @throws DuplicateValueException
     */
    public function test add value ko()
    {
        $this->expectException(DuplicateValueException::class);
        $this->system->addValue(0.1);
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

    public function test construct with values()
    {
        $this->system = new System(...[0.1, 0.2]);
        $this->assertSame([0.1, 0.2], $this->system->getValues());
    }
}
