<?php

/**
 * @author Marc MOREAU <moreau.marc.web@gmail.com>
 * @license https://github.com/MockingMagician/moneysaurus/blob/master/LICENSE.md Apache License 2.0
 * @link https://github.com/MockingMagician/moneysaurus/blob/master/README.md
 */

namespace MockingMagician\Moneysaurus\Helpers\PreventFromPhpBadStockingAfterOperate {
    /**
     * Floating point numbers have limited precision... Additionally, rational numbers that are exactly representable as
     * floating point numbers in base 10, like 0.1 or 0.7, do not have an exact representation as floating point numbers
     * in base 2, which is used internally, no matter the size of the mantissa.
     *
     * This helper prevent that side effect after an operate.
     *
     * @example https://github.com/MockingMagician/moneysaurus/blob/master/tests/PhpBadOperateTest.php
     *
     * @see https://www.php.net/manual/en/language.types.float.php
     *
     * @param float $a
     * @param float $b
     *
     * @return float
     */
    function minus(float $a, float $b): float
    {
        $a -= $b;
        $exp = explode('.', (string) $a);
        $d = isset($exp[1]) ? $exp[1] : '';
        $l = mb_strlen($d);

        return round($a, $l);
    }

    function plus(float $a, float $b): float
    {
        $a += $b;
        $exp = explode('.', (string) $a);
        $d = isset($exp[1]) ? $exp[1] : '';
        $l = mb_strlen($d);

        return round($a, $l);
    }

    function multiply(float $a, float $b): float
    {
        $a *= $b;
        $exp = explode('.', (string) $a);
        $d = isset($exp[1]) ? $exp[1] : '';
        $l = mb_strlen($d);

        return round($a, $l);
    }

    function matcher()
    {

    }
}
