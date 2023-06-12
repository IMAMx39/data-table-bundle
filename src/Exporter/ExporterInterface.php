<?php

declare(strict_types=1);

namespace Kreyu\Bundle\DataTableBundle\Exporter;

use Kreyu\Bundle\DataTableBundle\DataTableView;

interface ExporterInterface
{
    public function getName(): string;

    public function getOption(string $name, mixed $default = null): mixed;

    public function export(DataTableView $view, string $filename): ExportFile;
}