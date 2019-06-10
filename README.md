# Description

Moneysaurus is a PHP library for the change-making problem.

The change-making problem addresses the question of finding the minimum number of coins (of certain denominations) that add up to a given amount of money.

# Install

Simply run `composer require mocking-magician/moneysaurus`

# Use example

````PHP
<?

use MockingMagician\Moneysaurus\QuantifiedSystem;
use MockingMagician\Moneysaurus\Algorithms\GreedyAlgorithm;

$quantifiedSystem = new QuantifiedSystem();
$quantifiedSystem->addValue(0.01,  50);
$quantifiedSystem->addValue(0.02,  50);
$quantifiedSystem->addValue(0.05,  50);
$quantifiedSystem->addValue(0.10,  50);
$quantifiedSystem->addValue(0.20,  50);
$quantifiedSystem->addValue(0.50,  50);
$quantifiedSystem->addValue(1.0,   50);
$quantifiedSystem->addValue(2.0,   50);
$quantifiedSystem->addValue(5.0,   50);
$quantifiedSystem->addValue(10.0,  50);
$quantifiedSystem->addValue(20.0,  50);
$quantifiedSystem->addValue(50.0,  50);
$quantifiedSystem->addValue(100.0, 50);
$quantifiedSystem->addValue(200.0, 50);
$quantifiedSystem->addValue(500.0, 50);

$resolver = new GreedyAlgorithm($quantifiedSystem);
$change = $resolver->change(11.21);

````
