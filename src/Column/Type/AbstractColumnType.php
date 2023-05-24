<?php

declare(strict_types=1);

namespace Kreyu\Bundle\DataTableBundle\Column\Type;

use Kreyu\Bundle\DataTableBundle\Column\ColumnHeaderView;
use Kreyu\Bundle\DataTableBundle\Column\ColumnInterface;
use Kreyu\Bundle\DataTableBundle\Column\ColumnValueView;
use Kreyu\Bundle\DataTableBundle\Util\StringUtil;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractColumnType implements ColumnTypeInterface
{
    public function buildHeaderView(ColumnHeaderView $view, ColumnInterface $column, array $options): void
    {
    }

    public function buildValueView(ColumnValueView $view, ColumnInterface $column, array $options): void
    {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
    }

    public function getBlockPrefix(): string
    {
        return StringUtil::fqcnToShortName(static::class, ['ColumnType', 'Type']) ?: '';
    }

    public function getParent(): ?string
    {
        return ColumnType::class;
    }
}