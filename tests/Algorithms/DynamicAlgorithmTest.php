<?php

/**
 * @author Marc MOREAU <moreau.marc.web@gmail.com>
 * @license https://github.com/MockingMagician/moneysaurus/blob/master/LICENSE.md Apache License 2.0
 * @link https://github.com/MockingMagician/moneysaurus/blob/master/README.md
 */

use MockingMagician\Moneysaurus\Algorithms\DynamicAlgorithm;
use MockingMagician\Moneysaurus\Execptions\ValueNotExistException;
use MockingMagician\Moneysaurus\QuantifiedSystem;
use MockingMagician\Moneysaurus\System;

/**
 * @author Marc MOREAU <moreau.marc.web@gmail.com>
 * @license https://github.com/MockingMagician/moneysaurus/blob/master/LICENSE.md Apache License 2.0
 *
 * @see https://github.com/MockingMagician/moneysaurus/blob/master/README.md
 *
 * @internal
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
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->euroSystem = new System(...[
            0.01, 0.02, 0.05, 0.10, 0.20, 0.50, 1.0, 2.0,
            5.0, 10.0, 20.0, 50.0, 100.0, 200.0, 500.0,
        ]);
        $this->quantifiedSystem = new QuantifiedSystem($this->euroSystem);
//        $this->quantifiedSystem->setQuantity(0.02, 3);
//        $this->quantifiedSystem->setQuantity(0.05, 1);
        $this->quantifiedSystem->setQuantity(0.5, 4);
        $this->quantifiedSystem->setQuantity(1, 3);
        $this->quantifiedSystem->setQuantity(2, 2);
        $this->quantifiedSystem->setQuantity(5, 1);

        $this->dynamic = new DynamicAlgorithm($this->quantifiedSystem);
    }

    /**
     * @throws ValueNotExistException
     */
    public function test change()
    {
        $this->dynamic->change(12.15);
        ini_set('xdebug.var_display_max_depth', 12);
        ini_set('xdebug.var_display_max_children', 4);
        var_dump($this->dynamic);
    }
}
