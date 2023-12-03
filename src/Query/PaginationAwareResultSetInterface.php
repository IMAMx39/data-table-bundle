<?php

declare(strict_types=1);

namespace Kreyu\Bundle\DataTableBundle\Query;

interface PaginationAwareResultSetInterface extends ResultSetInterface
{
    public function getCurrentPageItemCount(): int;
}
