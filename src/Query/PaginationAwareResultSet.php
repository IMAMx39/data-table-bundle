<?php

declare(strict_types=1);

namespace Kreyu\Bundle\DataTableBundle\Query;

use Kreyu\Bundle\DataTableBundle\Util\IterableCounter;

class PaginationAwareResultSet extends ResultSet implements PaginationAwareResultSetInterface
{
    public function __construct(
        iterable $items,
        ?int $itemCount = null,
        private ?int $currentPageItemCount = null,
    ) {
        parent::__construct($items, $itemCount);
    }

    public function getCurrentPageItemCount(): int
    {
        if (null === $this->currentPageItemCount) {
            $this->currentPageItemCount = IterableCounter::count($this->items);
        }

        return $this->currentPageItemCount;
    }
}
