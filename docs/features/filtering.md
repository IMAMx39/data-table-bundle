---
order: 800
---

# Filtering

The data tables can be _filtered_, with use of the [filters](../reference/filters.md).

## Toggling the feature

By default, the filtration feature is **enabled** for every data table.
This can be configured with the `filtration_enabled` option:

+++ Globally (YAML)
```yaml # config/packages/kreyu_data_table.yaml
kreyu_data_table:
  defaults:
    filtration:
      enabled: true
```
+++ Globally (PHP)
```php # config/packages/kreyu_data_table.php
use Symfony\Config\KreyuDataTableConfig;

return static function (KreyuDataTableConfig $config) {
    $defaults = $config->defaults();
    $defaults->filtration()->enabled(true);
};
```
+++ For data table type
```php # src/DataTable/Type/ProductDataTable.php
use Kreyu\Bundle\DataTableBundle\Type\AbstractDataTableType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductDataTableType extends AbstractDataTableType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'filtration_enabled' => true,
        ]);
    }
}
```
+++ For specific data table
```php # src/Controller/ProductController.php
use App\DataTable\Type\ProductDataTableType;
use Kreyu\Bundle\DataTableBundle\DataTableFactoryAwareTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    use DataTableFactoryAwareTrait;
    
    public function index()
    {
        $dataTable = $this->createDataTable(
            type: ProductDataTableType::class, 
            query: $query,
            options: [
                'filtration_enabled' => true,
            ],
        );
    }
}
```
+++

!!! Enabling the feature does not mean that any columns will be filterable by itself.
The filters have to be [added separately by the data table builder](../reference/filters.md#adding-the-filters).
!!!

## Configuring the feature persistence

By default, the filtration feature [persistence](persistence.md) is **disabled** for every data table.

You can configure the [persistence](persistence.md) globally using the package configuration file, or its related options:

+++ Globally (YAML)
```yaml # config/packages/kreyu_data_table.yaml
kreyu_data_table:
  defaults:
    filtration:
      persistence_enabled: true
      # if persistence is enabled and symfony/cache is installed, null otherwise
      persistence_adapter: kreyu_data_table.filtration.persistence.adapter.cache
      # if persistence is enabled and symfony/security-bundle is installed, null otherwise
      persistence_subject_provider: kreyu_data_table.persistence.subject_provider.token_storage
```
+++ Globally (PHP)
```php # config/packages/kreyu_data_table.php
use Symfony\Config\KreyuDataTableConfig;

return static function (KreyuDataTableConfig $config) {
    $defaults = $config->defaults();
    $defaults->filtration()
        ->persistenceEnabled(true)
        // if persistence is enabled and symfony/cache is installed, null otherwise
        ->persistenceAdapter('kreyu_data_table.filtration.persistence.adapter.cache')
        // if persistence is enabled and symfony/security-bundle is installed, null otherwise
        ->persistenceSubjectProvider('kreyu_data_table.persistence.subject_provider.token_storage')
    ;
};
```
+++ For data table type
```php # src/DataTable/Type/ProductDataTable.php
use Kreyu\Bundle\DataTableBundle\Persistence\PersistenceAdapterInterface;
use Kreyu\Bundle\DataTableBundle\Persistence\PersistenceSubjectProviderInterface;
use Kreyu\Bundle\DataTableBundle\Type\AbstractDataTableType;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductDataTableType extends AbstractDataTableType
{
    public function __construct(
        #[Autowire(service: 'kreyu_data_table.filtration.persistence.adapter.cache')]
        private PersistenceAdapterInterface $persistenceAdapter,
        #[Autowire(service: 'kreyu_data_table.persistence.subject_provider.token_storage')]
        private PersistenceSubjectProviderInterface $persistenceSubjectProvider,
    ) {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'filtration_persistence_enabled' => true,
            'filtration_persistence_adapter' => $this->persistenceAdapter,
            'filtration_persistence_subject_provider' => $this->persistenceSubjectProvider,
        ]);
    }
}
```
+++ For specific data table
```php # src/Controller/ProductController.php
use App\DataTable\Type\ProductDataTableType;
use Kreyu\Bundle\DataTableBundle\DataTableFactoryAwareTrait;
use Kreyu\Bundle\DataTableBundle\Persistence\PersistenceAdapterInterface;
use Kreyu\Bundle\DataTableBundle\Persistence\PersistenceSubjectProviderInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    use DataTableFactoryAwareTrait;
    
    public function __construct(
        #[Autowire(service: 'kreyu_data_table.filtration.persistence.adapter.cache')]
        private PersistenceAdapterInterface $persistenceAdapter,
        #[Autowire(service: 'kreyu_data_table.persistence.subject_provider.token_storage')]
        private PersistenceSubjectProviderInterface $persistenceSubjectProvider,
    ) {
    }
    
    public function index()
    {
        $dataTable = $this->createDataTable(
            type: ProductDataTableType::class, 
            query: $query,
            options: [
                'filtration_persistence_enabled' => true,
                'filtration_persistence_adapter' => $this->persistenceAdapter,
                'filtration_persistence_subject_provider' => $this->persistenceSubjectProvider,
            ],
        );
    }
}
```
+++

## Configuring default filtration

The default filtration data can be overridden using the data table builder's `setDefaultFiltrationData()` method:

```php # src/DataTable/Type/ProductDataTableType.php
use Kreyu\Bundle\DataTableBundle\DataTableBuilderInterface;
use Kreyu\Bundle\DataTableBundle\Type\AbstractDataTableType;
use Kreyu\Bundle\DataTableBundle\Filter\FiltrationData;
use Kreyu\Bundle\DataTableBundle\Filter\FilterData;
use Kreyu\Bundle\DataTableBundle\Filter\Operator;

class ProductDataTableType extends AbstractDataTableType
{
    public function buildDataTable(DataTableBuilderInterface $builder, array $options): void
    {
        $builder->setDefaultFiltrationData(new FiltrationData([
            'id' => new FilterData(value: 1, operator: Operator::Contains),
        ]));
        
        // or by creating the filtration data from an array:
        $builder->setDefaultFiltrationData(FiltrationData::fromArray([
            'id' => ['value' => 1, 'operator' => 'contains'],
        ]));
    }
}
```

## Events

The following events are dispatched when [:icon-mark-github: DataTableInterface::filter()](https://github.com/Kreyu/data-table-bundle/blob/main/src/DataTableInterface.php) is called:

[:icon-mark-github: DataTableEvents::PRE_FILTER](https://github.com/Kreyu/data-table-bundle/blob/main/src/Event/DataTableEvents.php)
:   Dispatched before the filtration data is applied to the query.
    Can be used to modify the filtration data, e.g. to force filtration on some columns.

[:icon-mark-github: DataTableEvents::POST_FILTER](https://github.com/Kreyu/data-table-bundle/blob/main/src/Event/DataTableEvents.php)
:   Dispatched after the filtration data is applied to the query and saved if the filtration persistence is enabled;
    Can be used to execute additional logic after the filters are applied.

The listeners and subscribers will receive an instance of the [:icon-mark-github: DataTableFiltrationEvent](https://github.com/Kreyu/data-table-bundle/blob/main/src/Event/DataTableFiltrationEvent.php)