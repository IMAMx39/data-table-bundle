<?php

declare(strict_types=1);

namespace Kreyu\Bundle\DataTableBundle\Filter\Type;

use Kreyu\Bundle\DataTableBundle\Filter\FilterInterface;
use Kreyu\Bundle\DataTableBundle\Filter\FilterView;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractType implements FilterTypeInterface
{
    public function buildView(FilterView $view, FilterInterface $filter, array $options): void
    {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
    }

    public function getParent(): ?string
    {
        return FilterType::class;
    }
}