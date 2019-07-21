<?php

declare(strict_types=1);

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
    private const LARGER_LIMIT = 64;
    /** @var ?DynamicNode */
    private $successOnChildren;
    /** @var DynamicNode[] */
    private $lastRow = [];
    private $largerLimit;

    public function __construct(QuantifiedSystem $system, float $change, ?int $largerLimit = self::LARGER_LIMIT)
    {
        parent::__construct($system, $change, null);
        $this->lastRow = [$this];
        $this->largerLimit = $largerLimit;
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
            $lastRow = \array_merge($lastRow, $node->getChildren());
            if (null !== $this->largerLimit && $lastRow > $this->largerLimit) {
                break;
            }
        }

        $this->lastRow = $lastRow;

        if (0 === \count($this->lastRow)) {
            return;
        }
    }

    public function getSuccessOnChildren(): ?DynamicNode
    {
        return $this->successOnChildren;
    }

    /**
     * @return DynamicNode[]
     * @codeCoverageIgnore
     */
    public function getLastRow(): array
    {
        return $this->lastRow;
    }
}
