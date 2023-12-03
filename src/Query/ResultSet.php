<?php

declare(strict_types=1);

namespace Kreyu\Bundle\DataTableBundle\Query;

use Kreyu\Bundle\DataTableBundle\Util\IterableCounter;

class ResultSet implements ResultSetInterface
{
    public function __construct(
        protected readonly iterable $items,
        protected ?int $itemCount = null,
    ) {
    }

    public function getItems(): iterable
    {
        return $this->items;
    }

    public function getItemCount(): int
    {
        if (null === $this->itemCount) {
            $this->itemCount = IterableCounter::count($this->items);
        }

        return $this->itemCount;
    }
}
