<?php

declare(strict_types=1);

namespace Kreyu\Bundle\DataTableBundle\Query;

interface ResultSetInterface
{
    public function getItems(): iterable;

    public function getItemCount(): int;
}
