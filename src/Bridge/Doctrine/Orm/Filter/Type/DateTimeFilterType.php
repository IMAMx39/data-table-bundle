<?php

declare(strict_types=1);

namespace Kreyu\Bundle\DataTableBundle\Bridge\Doctrine\Orm\Filter\Type;

use Doctrine\ORM\Query\Expr;
use Kreyu\Bundle\DataTableBundle\Exception\InvalidArgumentException;
use Kreyu\Bundle\DataTableBundle\Filter\FilterData;
use Kreyu\Bundle\DataTableBundle\Filter\FilterInterface;
use Kreyu\Bundle\DataTableBundle\Filter\Operator;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateTimeFilterType extends AbstractFilterType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'value_form_type' => DateTimeType::class,
                'supported_operators' => [
                    Operator::Equal,
                    Operator::NotEqual,
                    Operator::GreaterThan,
                    Operator::GreaterThanEqual,
                    Operator::LessThan,
                    Operator::LessThanEqual,
                ],
                'active_filter_formatter' => $this->getFormattedActiveFilterString(...),
            ])
            ->addNormalizer('value_form_options', function (OptionsResolver $resolver, array $value): array {
                return $value + ['widget' => 'single_text'];
            })
            ->addNormalizer('empty_data', function (OptionsResolver $resolver, string|array $value): string|array {
                if (DateTimeType::class !== $resolver['value_form_type']) {
                    return $value;
                }

                // Note: because choice and text widgets are split into three fields under "date" index,
                //       we have to return an array with three empty "date" values to properly set the empty data.
                return match ($resolver['value_form_options']['widget'] ?? null) {
                    'choice', 'text' => [
                        'date' => ['day' => '', 'month' => '', 'year' => ''],
                    ],
                    default => '',
                };
            })
        ;
    }

    protected function getFilterValue(FilterData $data): \DateTimeInterface
    {
        $value = $data->getValue();

        if ($value instanceof \DateTimeInterface) {
            return $value;
        }

        if (is_string($value)) {
            return \DateTime::createFromFormat('Y-m-d\TH:i', $value);
        }

        if (is_array($value)) {
            return (new \DateTime())
                ->setDate(
                    year: (int) $value['date']['year'] ?: 0,
                    month: (int) $value['date']['month'] ?: 0,
                    day: (int) $value['date']['day'] ?: 0,
                )
                ->setTime(
                    hour: (int) $value['time']['hour'] ?: 0,
                    minute: (int) $value['time']['minute'] ?: 0,
                    second: (int) $value['time']['second'] ?: 0,
                )
            ;
        }

        throw new \InvalidArgumentException(sprintf('Unable to convert data of type "%s" to DateTime object.', get_debug_type($value)));
    }

    protected function getOperatorExpression(string $queryPath, string $parameterName, Operator $operator, Expr $expr): object
    {
        $expression = match ($operator) {
            Operator::Equal => $expr->eq(...),
            Operator::NotEqual => $expr->neq(...),
            Operator::GreaterThan => $expr->gt(...),
            Operator::GreaterThanEqual => $expr->gte(...),
            Operator::LessThan => $expr->lt(...),
            Operator::LessThanEqual => $expr->lte(...),
            default => throw new InvalidArgumentException('Operator not supported'),
        };

        return $expression($queryPath, ":$parameterName");
    }

    private function getFormattedActiveFilterString(FilterData $data, FilterInterface $filter, array $options): string
    {
        $value = $data->getValue();

        if ($value instanceof \DateTimeInterface) {
            $format = $options['value_form_options']['input_format'] ?? null;

            if (null === $format) {
                $format = 'Y-m-d H';

                if ($options['value_form_options']['with_minutes'] ?? true) {
                    $format .= ':i';
                }

                if ($options['value_form_options']['with_seconds'] ?? true) {
                    $format .= ':s';
                }
            }

            return $value->format($format);
        }

        return (string) $value;
    }
}
