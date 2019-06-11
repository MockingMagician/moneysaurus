<?php

/**
 * @author Marc MOREAU <moreau.marc.web@gmail.com>
 */

namespace MockingMagician\Moneysaurus\Algorithms;

use MockingMagician\Moneysaurus\Execptions\ValueNotExistException;
use MockingMagician\Moneysaurus\QuantifiedSystem;

class DynamicNode
{
    private $system;
    private $change;
    /** @var DynamicNodeLink[] */
    private $children = [];

    /**
     * DynamicNode constructor.
     *
     * @param QuantifiedSystem $system
     * @param float            $change
     *
     * @throws ValueNotExistException
     */
    public function __construct(QuantifiedSystem $system, float $change)
    {
        $this->system = $system;
        $this->change = $change;
        $this->createTree();
    }

    /**
     * @return DynamicNodeLink[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @throws ValueNotExistException
     */
    private function createTree(): void
    {
        foreach ($this->system->getValues() as $value) {
            $quantity = $this->system->getQuantity($value);
            if ($this->change - $value < 0 && $quantity > 0) {
                $amount = $this->change - $value;
                // this part prevent from php internal rounding after operate
                $exp = explode('.', $amount);
                $d = isset($exp[1]) ? $exp[1] : '';
                $l = mb_strlen($d);
                $amount = round($amount, $l);
                // --- end of prevent ---
                --$quantity;
                $system = clone $this->system;
                $system->setQuantity($value, $quantity);
                $this->addChild($value, new self($system, $amount));
            }
        }
    }

    private function addChild(float $weight, self $node): void
    {
        $this->children[] = new DynamicNodeLink($weight, $node, $this);
    }

    public function __debugInfo()
    {
        return [
            'system' => $this->system,
            'change' => $this->change,
            'children' => $this->children,
        ];
    }
}
