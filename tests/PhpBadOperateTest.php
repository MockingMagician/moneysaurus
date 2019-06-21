<?php

/**
 * @author Marc MOREAU <moreau.marc.web@gmail.com>
 * @license https://github.com/MockingMagician/moneysaurus/blob/master/LICENSE.md Apache License 2.0
 * @link https://github.com/MockingMagician/moneysaurus/blob/master/README.md
 */

namespace MockingMagician\Moneysaurus\Tests;

use function MockingMagician\Moneysaurus\Helpers\PreventFromPhpBadStockingAfterOperate\minus;
use function MockingMagician\Moneysaurus\Helpers\PreventFromPhpBadStockingAfterOperate\multiply;
use function MockingMagician\Moneysaurus\Helpers\PreventFromPhpBadStockingAfterOperate\plus;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class PhpBadOperateTest extends TestCase
{
    public function test float minus()
    {
        /** @var float[] $toDeduce */
        $toDeduce = [0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9, 1.1, 1.2, 1.3, 1.4, 0.5]; // Sum is 10.0

        $a = 10.0;
        foreach ($toDeduce as $deduce) {
            $a -= $deduce;
        }

        $this->assertFalse('0' === (string) $a); // Can be true or false, depend on case
        $this->assertFalse(0.0 === $a);
        $this->assertFalse(0 === $a);
        $this->assertFalse(0.0 == $a);
        $this->assertFalse(0 == $a);

        $a = 10.0;
        foreach ($toDeduce as $deduce) {
            $a = minus($a, $deduce);
        }

        $this->assertTrue(0.0 === $a);
        // cause type mismatch is false
        $this->assertFalse(0 === $a);
        // but not less or great than 0
        $this->assertFalse(0 > $a);
        $this->assertFalse(0 < $a);

        $this->assertTrue(0.0 == $a);
        $this->assertTrue(0 == $a);
    }

    public function test float plus()
    {
        /** @var float[] $toPlus */
        $toPlus = [0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 1.3, 1.4, 0.5, 0.11, 1.11, 1.22, 2.12, 0.44]; // Sum is 11.0

        $a = 0.0;
        foreach ($toPlus as $plus) {
            $a += $plus;
        }

        $this->assertTrue('11' === (string) $a); // Can be true or false, depend on case
        $this->assertFalse(11 === $a);
        $this->assertFalse(11.0 === $a);
        $this->assertFalse(11 == $a);
        $this->assertFalse(11.0 == $a);

        $a = 0.0;
        foreach ($toPlus as $plus) {
            $a = plus($a, $plus);
        }

        $this->assertTrue(11.0 === $a);
        // cause type mismatch is false
        $this->assertFalse(11 === $a);
        // but not less or great than 11
        $this->assertFalse(11 > $a);
        $this->assertFalse(11 < $a);

        $this->assertTrue(11.0 == $a);
        $this->assertTrue(11 == $a);
    }

    public function test float multiply()
    {
        /** @var float[] $toMultiply */
        $toMultiply = [0.2, 0.3, 0.4, 1.5, 1.7, 1.8]; // multiply equal 0.11016

        $a = 1.0;
        foreach ($toMultiply as $multiply) {
            $a *= $multiply;
        }

        $this->assertTrue('0.11016' === (string) $a); // Can be true or false, depend on case
        $this->assertFalse(0.11016 === $a);
        $this->assertFalse(0.11016 == $a);

        $a = 1.0;
        foreach ($toMultiply as $multiply) {
            $a = multiply($a, $multiply);
        }

        $this->assertTrue(0.11016 === $a, (string) $a);
        $this->assertTrue(0.11016 == $a, (string) $a);
    }
}
