# Description

Moneysaurus is a PHP library for the change-making problem.

The change-making problem addresses the question of finding the minimum number of coins (of certain denominations) that add up to a given amount of money.

This library works for every currency. Just start by defining a system. A system is a collection of coin and/or bank note values that can be used for change.

# Install

Simply run `composer require mocking-magician/moneysaurus`

# Use examples

You can just start by creating a system

````PHP
<?

use MockingMagician\Moneysaurus\System;
use MockingMagician\Moneysaurus\QuantifiedSystem;
use MockingMagician\Moneysaurus\Algorithms\GreedyAlgorithm;

// There, you can see an example with the Euro currency system 
$system = new System([
    0.01, 
    0.02, 
    0.05, 
    0.10, 
    0.20, 
    0.50, 
    1.0,  
    2.0,  
    5.0,  
    10.0, 
    20.0, 
    50.0, 
    100.0,
    200.0,
    500.0,
]);

// Then initialize a quantified system.
// A quantified system, is a system with a defined quantity of each coin/bank note available.

$quantifiedSystem = new QuantifiedSystem($system);
// By default, each value has been initialized with an zero amount quantity value.
// So after you can set the available quantity for each coin/bank note
$quantifiedSystem->setQuantity(0.01,  50);
$quantifiedSystem->setQuantity(0.02,  50);
$quantifiedSystem->setQuantity(0.05,  50);
$quantifiedSystem->setQuantity(0.10,  50);
$quantifiedSystem->setQuantity(0.20,  50);
$quantifiedSystem->setQuantity(0.50,  50);
$quantifiedSystem->setQuantity(1.0,   50);
$quantifiedSystem->setQuantity(2.0,   50);
$quantifiedSystem->setQuantity(5.0,   50);
$quantifiedSystem->setQuantity(10.0,  50);
$quantifiedSystem->setQuantity(20.0,  50);
$quantifiedSystem->setQuantity(50.0,  50);
$quantifiedSystem->setQuantity(100.0, 50);
$quantifiedSystem->setQuantity(200.0, 50);
$quantifiedSystem->setQuantity(500.0, 50);

$resolver = new GreedyAlgorithm($quantifiedSystem);
$change = $resolver->change(11.21);

````

An other solution consist to initialize an empty quantified system, and then add system values with their available quantities.

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
