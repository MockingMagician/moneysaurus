<?php

namespace MockingMagician\Moneysaurus\Algorithms;

use MockingMagician\Moneysaurus\QuantifiedSystem;

interface ChangeInterface
{
    public function change(float $amount): QuantifiedSystem;
}
