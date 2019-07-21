<?php

declare(strict_types=1);

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
    public function test float minus(): void
    {
        /** @var float[] $toDeduce */
        $toDeduce = [0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9, 1.1, 1.2, 1.3, 1.4, 0.5]; // Sum is 10.0

        $a = 10.0;
        foreach ($toDeduce as $deduce) {
            $a -= $deduce;
        }

        static::assertFalse('0' === (string) $a); // Can be true or false, depend on case
        static::assertFalse(0.0 === $a);
        static::assertFalse(0 === $a);
        static::assertFalse(0.0 === $a);
        static::assertFalse(0 === $a);

        $a = 10.0;
        foreach ($toDeduce as $deduce) {
            $a = minus($a, $deduce);
        }

        static::assertTrue(0.0 === $a);
        // cause type mismatch is false
        static::assertFalse(0 === $a);
        // but not less or great than 0
        static::assertFalse(0 > $a);
        static::assertFalse(0 < $a);

        static::assertTrue(0.0 === $a);
        static::assertTrue(0 == $a);
    }

    public function test float plus(): void
    {
        /** @var float[] $toPlus */
        $toPlus = [0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 1.3, 1.4, 0.5, 0.11, 1.11, 1.22, 2.12, 0.44]; // Sum is 11.0

        $a = 0.0;
        foreach ($toPlus as $plus) {
            $a += $plus;
        }

        static::assertTrue('11' === (string) $a); // Can be true or false, depend on case
        static::assertFalse(11 === $a);
        static::assertFalse(11.0 === $a);
        static::assertFalse(11 === $a);
        static::assertFalse(11.0 === $a);

        $a = 0.0;
        foreach ($toPlus as $plus) {
            $a = plus($a, $plus);
        }

        static::assertTrue(11.0 === $a);
        // cause type mismatch is false
        static::assertFalse(11 === $a);
        // but not less or great than 11
        static::assertFalse(11 > $a);
        static::assertFalse(11 < $a);

        static::assertTrue(11.0 === $a);
        static::assertTrue(11 == $a);
    }

    public function test float multiply(): void
    {
        /** @var float[] $toMultiply */
        $toMultiply = [0.2, 0.3, 0.4, 1.5, 1.7, 1.8]; // multiply equal 0.11016

        $a = 1.0;
        foreach ($toMultiply as $multiply) {
            $a *= $multiply;
        }

        static::assertTrue('0.11016' === (string) $a); // Can be true or false, depend on case
        static::assertFalse(0.11016 === $a);
        static::assertFalse(0.11016 === $a);

        $a = 1.0;
        foreach ($toMultiply as $multiply) {
            $a = multiply($a, $multiply);
        }

        static::assertTrue(0.11016 === $a, (string) $a);
        static::assertTrue(0.11016 === $a, (string) $a);
    }
}
