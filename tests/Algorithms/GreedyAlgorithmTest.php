<?php

/**
 * @author Marc MOREAU <moreau.marc.web@gmail.com>
 * @license https://github.com/MockingMagician/moneysaurus/blob/master/LICENSE.md Apache License 2.0
 * @link https://github.com/MockingMagician/moneysaurus/blob/master/README.md
 */

use MockingMagician\Moneysaurus\Algorithms\GreedyAlgorithm;
use MockingMagician\Moneysaurus\Exceptions\ChangeAsLeftOver;
use MockingMagician\Moneysaurus\Exceptions\DuplicateValueException;
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
     * @throws ValueNotExistException
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
     * @throws ValueNotExistException
     * @throws DuplicateValueException
     */
    public function test change for optimal 18 cents()
    {
        $this->quantifiedSystem->setQuantity(0.2, 1);
        $this->quantifiedSystem->setQuantity(0.1, 1);
        $this->quantifiedSystem->setQuantity(0.05, 1);
        $this->quantifiedSystem->setQuantity(0.02, 1);
        $this->quantifiedSystem->setQuantity(0.01, 1);

        $this->assertEquals(
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

        $this->assertEquals(
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

        $this->assertInstanceOf(ChangeAsLeftOver::class, $exception);

        $this->assertEquals(0.01, $exception->getLeftOver());
        $this->assertEquals(
            (new QuantifiedSystem())
                ->addValue(0.1, 1)
                ->addValue(0.01, 7),
            $exception->getSystem()
        );
    }
}
