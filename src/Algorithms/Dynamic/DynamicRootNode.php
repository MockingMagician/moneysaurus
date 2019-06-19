<?php

/**
 * @author Marc MOREAU <moreau.marc.web@gmail.com>
 * @license https://github.com/MockingMagician/moneysaurus/blob/master/LICENSE.md Apache License 2.0
 * @link https://github.com/MockingMagician/moneysaurus/blob/master/README.md
 */

namespace MockingMagician\Moneysaurus\Algorithms\Dynamic;

use MockingMagician\Moneysaurus\Exceptions\ValueNotExistException;
use MockingMagician\Moneysaurus\QuantifiedSystem;

class DynamicRootNode extends DynamicNode
{
    /** @var ?DynamicNode */
    private $successOnChildren;
    /** @var DynamicNode[] */
    private $lastRow = [];

    public function __construct(QuantifiedSystem $system, float $change)
    {
        parent::__construct($system, $change, null);
        $this->lastRow = [$this];
    }

    /**
     * @throws ValueNotExistException
     */
    public function nextRow(): void
    {
        if ($this->successOnChildren) {
            return;
        }

        $lastRow = [];

        foreach ($this->lastRow as $node) {
            $node->nextChildren();
            if ($this->successOnChildren = $node->getSuccessOnChild()) {
                return;
            }
            $lastRow = array_merge($lastRow, $node->getChildren());
        }

        var_dump($this->lastRow);

        $this->lastRow = $lastRow;
    }

    public function getSuccessOnChildren(): ?DynamicNode
    {
        return $this->successOnChildren;
    }

    /**
     * @return DynamicNode[]
     */
    public function getLastRow(): array
    {
        return $this->lastRow;
    }
}
