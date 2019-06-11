<?php

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

    public function __debugInfo()
    {
        return [
            'weight' => $this->weight,
            'node' => $this->node,
            'parent' => $this->parent,
        ];
    }

}
