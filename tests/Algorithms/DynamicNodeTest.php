<?php

/**
 * @author Marc MOREAU <moreau.marc.web@gmail.com>
 * @license https://github.com/MockingMagician/moneysaurus/blob/master/LICENSE.md Apache License 2.0
 * @link https://github.com/MockingMagician/moneysaurus/blob/master/README.md
 */

use MockingMagician\Moneysaurus\Algorithms\DynamicNode;
use MockingMagician\Moneysaurus\Execptions\ValueNotExistException;
use MockingMagician\Moneysaurus\QuantifiedSystem;
use MockingMagician\Moneysaurus\System;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class DynamicNodeTest extends TestCase
{
    /** @var DynamicNode */
    private $dynamicNode;
    /** @var QuantifiedSystem */
    private $quantifiedSystem;

    /**
     * @throws ValueNotExistException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $euroSystem = new System(...[
            0.01, 0.02, 0.05, 0.10, 0.20, 0.50, 1.0, 2.0,
            5.0, 10.0, 20.0, 50.0, 100.0, 200.0, 500.0,
        ]);

        $this->quantifiedSystem = new QuantifiedSystem($euroSystem);
        $this->quantifiedSystem->setQuantity(0.5, 5);
        $this->quantifiedSystem->setQuantity(1, 5);
        $this->quantifiedSystem->setQuantity(2, 5);
        $this->quantifiedSystem->setQuantity(5, 5);

        $this->dynamicNode = new DynamicNode($this->quantifiedSystem, 2);
    }

    /**
     * @throws ValueNotExistException
     */
    public function testGetSuccessOnChild()
    {
        $this->dynamicNode->nextChildren();
        $this->assertNotNull($this->dynamicNode->getSuccessOnChild());
    }
}