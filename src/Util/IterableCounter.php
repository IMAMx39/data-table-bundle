<?php

declare(strict_types=1);

namespace Kreyu\Bundle\DataTableBundle\Util;

use Kreyu\Bundle\DataTableBundle\Exception\InvalidArgumentException;

class IterableCounter
{
    public static function count(iterable $iterable): int
    {
        if ($iterable instanceof \Traversable) {
            return iterator_count($iterable);
        }

        if (is_countable($iterable)) {
            return count($iterable);
        }

        throw new InvalidArgumentException('Unable to count the iterable, is is neither an instance of \Traversable nor \Countable.');
    }
}
