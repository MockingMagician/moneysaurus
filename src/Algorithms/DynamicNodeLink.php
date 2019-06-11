<?php

/**
 * @author Marc MOREAU <moreau.marc.web@gmail.com>
 * @license https://github.com/MockingMagician/moneysaurus/blob/master/LICENSE.md Apache License 2.0
 * @link https://github.com/MockingMagician/moneysaurus/blob/master/README.md
 */

namespace MockingMagician\Moneysaurus\Algorithms;

class DynamicNodeLink
{
    private $weight;
    private $node;
    private $parent;

    public function __construct(float $weight, DynamicNode $node, DynamicNode $parent)
    {
        $this->weight = $weight;
        $this->node = $node;
        $this->parent = $parent;
    }

    public function __debugInfo()
    {
        return [
            'weight' => $this->weight,
            'node' => $this->node,
            'parent' => $this->parent,
        ];
    }

    public function getParent(): DynamicNode
    {
        return $this->parent;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function getNode(): DynamicNode
    {
        return $this->node;
    }
}
