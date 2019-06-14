<?php

/**
 * @author Marc MOREAU <moreau.marc.web@gmail.com>
 * @license https://github.com/MockingMagician/moneysaurus/blob/master/LICENSE.md Apache License 2.0
 * @link https://github.com/MockingMagician/moneysaurus/blob/master/README.md
 */

namespace MockingMagician\Moneysaurus {
    function preventFromPhpInternalRoundingAfterOperate(float $amount, float $amountToDeduce): float
    {
        $amount -= $amountToDeduce;
        $exp = explode('.', (string) $amount);
        $d = isset($exp[1]) ? $exp[1] : '';
        $l = mb_strlen($d);

        return round($amount, $l);
    }
}
