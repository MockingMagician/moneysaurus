<?php

declare(strict_types=1);

/**
 * @author Marc MOREAU <moreau.marc.web@gmail.com>
 * @license https://github.com/MockingMagician/moneysaurus/blob/master/LICENSE.md Apache License 2.0
 * @link https://github.com/MockingMagician/moneysaurus/blob/master/README.md
 */

namespace MockingMagician\Moneysaurus\Tests\Algorithms;

use MockingMagician\Moneysaurus\Algorithms\GreedyAlgorithm;
use MockingMagician\Moneysaurus\Exceptions\ChangeAsLeftOver;
use MockingMagician\Moneysaurus\Exceptions\DuplicateValueException;
use MockingMagician\Moneysaurus\Exceptions\NegativeQuantityException;
use MockingMagician\Moneysaurus\Exceptions\ValueNotExistException;
use MockingMagician\Moneysaurus\QuantifiedSystem;
use MockingMagician\Moneysaurus\System;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class GreedyAlgorithmTest extends TestCase
{
    /** @var GreedyAlgorithm */
    private $greedy;
    /** @var System */
    private $euroSystem;
    /** @var QuantifiedSystem */
    private $quantifiedSystem;

    /**
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
        $this->greedy = new GreedyAlgorithm($this->quantifiedSystem);
    }

    /**
     * @throws ChangeAsLeftOver
     * @throws DuplicateValueException
     * @throws NegativeQuantityException
     * @throws ValueNotExistException
     */
    public function test change for optimal 18 cents(): void
    {
        $this->quantifiedSystem->setQuantity(0.2, 1);
        $this->quantifiedSystem->setQuantity(0.1, 1);
        $this->quantifiedSystem->setQuantity(0.05, 1);
        $this->quantifiedSystem->setQuantity(0.02, 1);
        $this->quantifiedSystem->setQuantity(0.01, 1);

        static::assertEquals(
            (new QuantifiedSystem())
                ->addValue(0.1, 1)
                ->addValue(0.05, 1)
                ->addValue(0.02, 1)
                ->addValue(0.01, 1),
            $this->greedy->change(0.18)
        );

        $this->quantifiedSystem->setQuantity(0.1, 1);
        $this->quantifiedSystem->setQuantity(0.05, 0);
        $this->quantifiedSystem->setQuantity(0.02, 4);
        $this->quantifiedSystem->setQuantity(0.01, 1);

        static::assertEquals(
            (new QuantifiedSystem())
                ->addValue(0.1, 1)
                ->addValue(0.02, 4),
            $this->greedy->change(0.18)
        );

        $this->quantifiedSystem->setQuantity(0.1, 1);
        $this->quantifiedSystem->setQuantity(0.05, 0);
        $this->quantifiedSystem->setQuantity(0.02, 0);
        $this->quantifiedSystem->setQuantity(0.01, 7);

        try {
            $exception = null;
            $this->greedy->change(0.18);
        } catch (ChangeAsLeftOver $exception) {
        }

        static::assertInstanceOf(ChangeAsLeftOver::class, $exception);

        static::assertEquals(0.01, $exception->getLeftOver());
        static::assertEquals(
            (new QuantifiedSystem())
                ->addValue(0.1, 1)
                ->addValue(0.01, 7),
            $exception->getSystem()
        );
    }
}
