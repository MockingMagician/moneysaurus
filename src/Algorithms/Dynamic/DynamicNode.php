<?php

/**
 * @author Marc MOREAU <moreau.marc.web@gmail.com>
 * @license https://github.com/MockingMagician/moneysaurus/blob/master/LICENSE.md Apache License 2.0
 * @link https://github.com/MockingMagician/moneysaurus/blob/master/README.md
 */

namespace MockingMagician\Moneysaurus\Algorithms\Dynamic;

use MockingMagician\Moneysaurus\Exceptions\ValueNotExistException;
use function MockingMagician\Moneysaurus\preventFromPhpInternalRoundingAfterOperate;
use MockingMagician\Moneysaurus\QuantifiedSystem;

class DynamicNode
{
    /** @var bool */
    private $nextAsRun = false;
    /** @var ?DynamicNode */
    private $successOnChild;
    private $system;
    private $change;
    private $parent;
    /** @var DynamicNode[] */
    private $children = [];

    /**
     * DynamicNode constructor.
     *
     * @param QuantifiedSystem $system
     * @param float            $change
     * @param null|self        $parent
     */
    public function __construct(QuantifiedSystem $system, float $change, ?self $parent = null)
    {
        $this->system = $system;
        $this->change = $change;
        $this->parent = $parent;
    }

    /**
     * @codeCoverageIgnore
     */
    public function __debugInfo()
    {
        return [
            'system' => $this->system,
            'change' => $this->change,
            'children' => $this->children,
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    public function __toString()
    {
        $string = json_encode($this->__debugInfo());

        return $string ? $string : '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getSystem(): QuantifiedSystem
    {
        return $this->system;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getChange(): float
    {
        return $this->change;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getParent(): ?self
    {
        return $this->parent;
    }

    /**
     * @return DynamicNode[]
     * @codeCoverageIgnore
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @throws ValueNotExistException
     */
    public function nextChildren(): void
    {
        if ($this->nextAsRun) {
            return;
        }

        $values = $this->system->getValues();
        rsort($values);

        foreach ($values as $value) {
            $quantity = $this->system->getQuantity($value);
            if (0.0 <= ($amount = preventFromPhpInternalRoundingAfterOperate($this->change, $value)) && $quantity > 0) {
                --$quantity;
                $system = clone $this->system;
                $system->setQuantity($value, $quantity);
                $node = new self($system, $amount, $this);
                $this->addChild($node);
                if (0.0 === $amount) {
                    $this->successOnChild = $node;

                    break;
                }
            }
        }

        $this->nextAsRun = true;
    }

    public function getSuccessOnChild(): ?self
    {
        return $this->successOnChild;
    }

    private function addChild(self $node): void
    {
        $this->children[] = $node;
    }
}
