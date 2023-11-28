# Filters

Filters are defined using the filter type classes.

## Adding the filters

To add a filter, use the `addFilter()` method on the data table builder:

```php # src/DataTable/Type/ProductDataTableType.php
use Kreyu\Bundle\DataTableBundle\DataTableBuilderInterface;
use Kreyu\Bundle\DataTableBundle\Type\AbstractDataTableType;
use Kreyu\Bundle\DataTableDoctrineOrmBundle\Filter\Type\NumberFilterType;
use Kreyu\Bundle\DataTableDoctrineOrmBundle\Filter\Type\TextFilterType;

class ProductDataTableType extends AbstractDataTableType
{
    public function buildDataTable(DataTableBuilderInterface $builder, array $options): void
    {
        $builder
            ->addFilter('id', NumberFilterType::class)
            ->addFilter('name', TextFilterType::class)
        ;
    }
}
```

The builder's `addFilter()` method accepts _three_ arguments:

- filter name — which in most cases will represent a property path in the underlying entity;
- filter type — with a fully qualified class name;
- filter options — defined by the filter type, used to configure the filter;

For reference, see [built-in filter types](../reference/filters/types.md).

### Specifying the query path

The bundle will use the filter name as the path to perform filtration on.
However, if the path is different from the column name, provide it using the `query_path` option:

```php # src/DataTable/Type/ProductDataTableType.php
use Kreyu\Bundle\DataTableBundle\DataTableBuilderInterface;
use Kreyu\Bundle\DataTableBundle\Type\AbstractDataTableType;
use Kreyu\Bundle\DataTableDoctrineOrmBundle\Filter\Type\TextFilterType;

class ProductDataTableType extends AbstractDataTableType
{
    public function buildDataTable(DataTableBuilderInterface $builder, array $options): void
    {
        $builder
            ->addFilter('category', TextFilterType::class, [
                'query_path' => 'category.name',
            ])
        ;
    }
}
```

For reference, see [built-in filter types](../reference/filters/types.md).

## Filter operators

Each filter can support multiple operators, such as "equals", "contains", "starts with", etc.
Optionally, the filtration form can display the operator selector, letting the user select a desired filtration method.

### Default operator

The default operator can be configured using the `default_operator` option:

```php # src/DataTable/Type/ProductDataTableType.php
use Kreyu\Bundle\DataTableBundle\DataTableBuilderInterface;
use Kreyu\Bundle\DataTableBundle\Type\AbstractDataTableType;
use Kreyu\Bundle\DataTableBundle\Filter\Operator;
use Kreyu\Bundle\DataTableDoctrineOrmBundle\Filter\Type\NumberFilterType;
use Kreyu\Bundle\DataTableDoctrineOrmBundle\Filter\Type\TextFilterType;

class ProductDataTableType extends AbstractDataTableType
{
    public function buildDataTable(DataTableBuilderInterface $builder, array $options): void
    {
        $builder
            ->addFilter('id', NumberFilterType::class)
            ->addFilter('name', TextFilterType::class, [
                'default_operator' => Operator::Contains,
            ])
        ;
    }
}
```

If the operator **is** selectable by the user, the `default_operator` determines the initially selected operator.

If the operator **is not** selectable by the user, the operator provided by this option will be used.

### Displaying operator selector

The operator can be selectable by the user by setting the `operator_selectable` option to `true`:

```php # src/DataTable/Type/ProductDataTableType.php
use Kreyu\Bundle\DataTableBundle\DataTableBuilderInterface;
use Kreyu\Bundle\DataTableBundle\Type\AbstractDataTableType;
use Kreyu\Bundle\DataTableDoctrineOrmBundle\Filter\Type\NumberFilterType;
use Kreyu\Bundle\DataTableDoctrineOrmBundle\Filter\Type\TextFilterType;

class ProductDataTableType extends AbstractDataTableType
{
    public function buildDataTable(DataTableBuilderInterface $builder, array $options): void
    {
        $builder
            ->addFilter('id', NumberFilterType::class)
            ->addFilter('name', TextFilterType::class, [
                'operator_selectable' => true,
            ])
        ;
    }
}
```

### Restricting selectable operators

The operators selectable by the user can be restricted by using the `supported_operators` option:

```php # src/DataTable/Type/ProductDataTableType.php
use Kreyu\Bundle\DataTableBundle\DataTableBuilderInterface;
use Kreyu\Bundle\DataTableBundle\Type\AbstractDataTableType;
use Kreyu\Bundle\DataTableBundle\Filter\Operator;
use Kreyu\Bundle\DataTableDoctrineOrmBundle\Filter\Type\NumberFilterType;
use Kreyu\Bundle\DataTableDoctrineOrmBundle\Filter\Type\TextFilterType;

class ProductDataTableType extends AbstractDataTableType
{
    public function buildDataTable(DataTableBuilderInterface $builder, array $options): void
    {
        $builder
            ->addFilter('id', NumberFilterType::class)
            ->addFilter('name', TextFilterType::class, [
                'operator_selectable' => true,
                'supported_operators' => [
                    Operator::Equals,
                    Operator::Contains,
                ],
            ])
        ;
    }
}
```

Remember that each filter can support a different set of operators internally!

## Configuring form type

The filter form type can be configured using the `form_type` and `form_options` options.

```php # src/DataTable/Type/ProductDataTableType.php
use Kreyu\Bundle\DataTableBundle\DataTableBuilderInterface;
use Kreyu\Bundle\DataTableBundle\Type\AbstractDataTableType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Kreyu\Bundle\DataTableDoctrineOrmBundle\Filter\Type\NumberFilterType;
use Kreyu\Bundle\DataTableDoctrineOrmBundle\Filter\Type\TextFilterType;

class ProductDataTableType extends AbstractDataTableType
{
    public function buildDataTable(DataTableBuilderInterface $builder, array $options): void
    {
        $builder
            ->addFilter('id', NumberFilterType::class)
            ->addFilter('name', TextFilterType::class, [
                'form_type' => SearchType::class,
                'form_options' => [
                    'attr' => [
                        'placeholder' => 'Name', 
                    ],
                ],
            ])
        ;
    }
}
```

Similar configuration can be applied to the operator form type, using the `operator_form_type` and `operator_form_options` options:

```php # src/DataTable/Type/ProductDataTableType.php
use Kreyu\Bundle\DataTableBundle\DataTableBuilderInterface;
use Kreyu\Bundle\DataTableBundle\Type\AbstractDataTableType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Kreyu\Bundle\DataTableDoctrineOrmBundle\Filter\Type\NumberFilterType;
use Kreyu\Bundle\DataTableDoctrineOrmBundle\Filter\Type\TextFilterType;

class ProductDataTableType extends AbstractDataTableType
{
    public function buildDataTable(DataTableBuilderInterface $builder, array $options): void
    {
        $builder
            ->addFilter('id', NumberFilterType::class)
            ->addFilter('name', TextFilterType::class, [
                'operator_form_type' => ChoiceType::class,
                'operator_form_options' => [
                    'attr' => [
                        'placeholder' => 'Operator', 
                    ],
                ],
            ])
        ;
    }
}
```
