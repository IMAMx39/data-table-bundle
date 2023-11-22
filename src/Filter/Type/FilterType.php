<?php

declare(strict_types=1);

namespace Kreyu\Bundle\DataTableBundle\Filter\Type;

use Kreyu\Bundle\DataTableBundle\Filter\FilterBuilderInterface;
use Kreyu\Bundle\DataTableBundle\Filter\FilterData;
use Kreyu\Bundle\DataTableBundle\Filter\FilterInterface;
use Kreyu\Bundle\DataTableBundle\Filter\FilterView;
use Kreyu\Bundle\DataTableBundle\Filter\Form\Type\OperatorType;
use Kreyu\Bundle\DataTableBundle\Filter\Operator;
use Kreyu\Bundle\DataTableBundle\Query\ProxyQueryInterface;
use Kreyu\Bundle\DataTableBundle\Util\StringUtil;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatableInterface;

final class FilterType implements FilterTypeInterface
{
    public function apply(ProxyQueryInterface $query, FilterData $data, FilterInterface $filter, array $options): void
    {
    }

    public function buildFilter(FilterBuilderInterface $builder, array $options): void
    {
        $setters = [
            'form_type' => $builder->setFormType(...),
            'form_options' => $builder->setFormOptions(...),
            'operator_form_type' => $builder->setOperatorFormType(...),
            'operator_form_options' => $builder->setOperatorFormOptions(...),
            'default_operator' => $builder->setDefaultOperator(...),
            'supported_operators' => $builder->setSupportedOperators(...),
            'operator_selectable' => $builder->setOperatorSelectable(...),
        ];

        foreach ($setters as $option => $setter) {
            $setter($options[$option]);
        }
    }

    public function buildView(FilterView $view, FilterInterface $filter, FilterData $data, array $options): void
    {
        $value = null;

        if ($data->hasValue()) {
            $value = $data->getValue();

            if ($formatter = $options['active_filter_formatter']) {
                $value = $formatter($data, $filter, $options);
            }
        }

        $view->data = $data;
        $view->value = $value;

        $view->vars = array_replace($view->vars, [
            'name' => $filter->getName(),
            'label' => $options['label'] ?? StringUtil::camelToSentence($filter->getName()),
            'label_translation_parameters' => $options['label_translation_parameters'],
            'translation_domain' => $options['translation_domain'] ?? $view->parent->vars['translation_domain'] ?? null,
            'query_path' => $filter->getQueryPath(),
            'form_type' => $filter->getConfig()->getFormType(),
            'form_options' => $filter->getConfig()->getFormOptions(),
            'operator_form_type' => $filter->getConfig()->getOperatorFormType(),
            'operator_form_options' => $filter->getConfig()->getOperatorFormOptions(),
            'operator_selectable' => $filter->getConfig()->isOperatorSelectable(),
            'default_operator' => $filter->getConfig()->getDefaultOperator(),
            'supported_operators' => $filter->getConfig()->getSupportedOperators(),
            'clear_filter_parameters' => [
                'value' => $options['empty_data'],
                'operator' => $filter->getConfig()->getDefaultOperator()->value,
            ],
            'data' => $view->data,
            'value' => $view->value,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'label' => null,
                'label_translation_parameters' => [],
                'translation_domain' => null,
                'query_path' => null,
                'form_type' => TextType::class,
                'form_options' => [],
                'operator_form_type' => OperatorType::class,
                'operator_form_options' => [],
                'default_operator' => Operator::Equals,
                'supported_operators' => [],
                'operator_selectable' => false,
                'active_filter_formatter' => null,
                'empty_data' => '',
            ])
            ->setAllowedTypes('label', ['null', 'bool', 'string', TranslatableInterface::class])
            ->setAllowedTypes('label_translation_parameters', 'array')
            ->setAllowedTypes('translation_domain', ['null', 'bool', 'string'])
            ->setAllowedTypes('query_path', ['null', 'string'])
            ->setAllowedTypes('form_type', 'string')
            ->setAllowedTypes('form_options', 'array')
            ->setAllowedTypes('operator_form_type', 'string')
            ->setAllowedTypes('operator_form_options', 'array')
            ->setAllowedTypes('active_filter_formatter', ['null', 'callable'])
            ->setAllowedTypes('empty_data', ['string', 'array'])
            ->setAllowedValues('translation_domain', function (mixed $value): bool {
                return is_null($value) || false === $value || is_string($value);
            })
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'filter';
    }

    public function getParent(): ?string
    {
        return null;
    }
}
