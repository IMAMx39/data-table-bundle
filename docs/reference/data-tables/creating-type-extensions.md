---
label: Creating type extensions
---

# Creating data table type extensions

The data table type extensions allow you to modify any existing data table type across the entire system.

## Defining the type extension

Data table type extensions are PHP classes that implement [`DataTableTypeExtensionInterface`](https://), however, it is recommended to extend from [`AbstractDataTableTypeExtension`](https://).
By convention, they are stored in the `src/DataTable/Extension/` directory:

```php # src/DataTable/Extension/TimestampsDataTableTypeExtension.php
namespace App\DataTable\Extension;

use App\DataTable\Type\ProductDataTableType;
use Kreyu\Bundle\DataTableBundle\Extension\AbstractDataTableTypeExtension;

class TimestampsDataTableTypeExtension extends AbstractDataTableTypeExtension
{
	public static function getExtendedTypes(): iterable
	{
		// return [DataTableType::class] to modify every data table in the system
		return [ProductDataTableType::class];
	}
}
```

The only method you **must** implement is `getExtendedTypes()`, which is used to configure which data table types you want to modify.

Depending on your case, you may want to override some of the following methods:

- `buildDataTable()`
- `buildView()`
- `configureOptions()`

For more information on what those methods do, see the [custom data table type](creating-custom-types.md) article.

## Registering the type extension as a service

Data table type extensions must be [registered as services](https://symfony.com/doc/current/service_container.html#service-container-creating-service) and [tagged](https://symfony.com/doc/current/service_container/tags.html) with the `kreyu_data_table.type_extension` tag:

```yaml # config/services.yaml
services:
  App\DataTable\Extension\TimestampsDataTableTypeExtension:
    tags: ['kreyu_data_table.type_extension']
```

If you're using the [default `services.yaml` configuration](https://symfony.com/doc/current/service_container.html#service-container-services-load-example), this is already done for you, thanks to [autoconfiguration](https://symfony.com/doc/current/service_container.html#services-autoconfigure).

!!! Note
There is an optional tag attribute called `priority`, which defaults to 0 and controls the order in which the data table type extensions are loaded 
(the higher the priority, the earlier an extension is loaded). This is useful when you need to guarantee that one extension 
is loaded before or after another extension. Using this attribute requires you to add the service configuration explicitly.
!!!

Once the extension is registered, any method that you've overridden (e.g. `buildDataTable()`) will be called whenever any data table of the given type is built.

## Adding the type extension logic

The goal of the example extension will be to automatically add timestamp columns to timestampable model data tables,
with an option to disable this behavior regardless whether the underlying model is timestampable.

Let's assume, that your application contains a `Product` model which implements `TimestampableInterface`:

```php # src/Model/Product.php
namespace App\Model;

interface TimestampableInterface
{
	public function getCreatedAt(): DateTimeInterface;
	public function getUpdatedAt(): DateTimeInterface;
}

class Product implements TimestampableInterface 
{
	// Timestamp getters required by the TimestampableInterface
}
```

The `ProductDataTableType` has `data_class` option set to the fully-qualified class name of the `Product` model:

```php # src/DataTable/Type/ProductDataTableType.php
namespace App\DataTable\Type;

use App\Model\Product;
use Kreyu\Bundle\DataTableBundle\AbstractDataTableType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductDataTableType extends AbstractDataTableType
{
	// ...
	
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefault('data_class', Product::class);
	}
}
```

The goal of this example extension will be to automatically add `createdAt` and `updatedAt` column to the data tables having `data_class` option set to class that implements the `TimestampableInterface`. Additionally, a `timestampable` option should be available to disable this behavior.

1. override the `configureOptions()` method so that any extended data table type can have an `timestampable` option
2. override the `buildDataTable()` method to check whether the `data_class` equals to fully qualified name of the class that implements the `TimestampableInterface`, and whether the `timestampable` option equals `true` - if both conditions are true, add `createdAt` and `updatedAt` columns

```php # src/DataTable/Extension/TimestampableDataTableTypeExtension.php
namespace App\DataTable\Extension;

use App\DataTable\Type\ProductDataTableType;
use Kreyu\Bundle\DataTableBundle\Column\Type\DateTimeColumnType;
use Kreyu\Bundle\DataTableBundle\Extension\AbstractDataTableTypeExtension;
use Kreyu\Bundle\DataTableBundle\Type\DataTableType;

class TimestampableDataTableTypeExtension extends AbstractDataTableTypeExtension
{
	public static function getExtendedTypes(): iterable
	{
		return [DataTableType::class];
	}

	public function buildDataTable(DataTableBuilderInterface $builder, array $options): void
	{
		$timestampable = $options['timestampable'] && is_a($options['data_class'], TimestampableInterface::class, true);
		
		if (!$timestampable) {
			return;
		}
		
		$builder
			->addColumn('createdAt', DateTimeColumnType::class)
			->addColumn('updatedAt', DateTimeColumnType::class)
		;
	}
	
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver
			->setDefault('timestampable', true)
			->setAllowedTypes('timestampable', 'bool')
		;
	}
}
```

## Using the data table type extension

From now on, when creating a data table of type `ProductDataTableType`, you can specify an `timestampable` option, that will be used to automatically add timestamp columns. For example:

```php # App\Controller\ProductController.php
use App\Model\Product;
use App\DataTable\Type\ProductDataTableType;
use Kreyu\Bundle\DataTableBundle\DataTableFactoryAwareTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
	use DataTableFactoryAwareTrait;
	
	public function index(): void
	{
		$products = [
			new Product(),
			new Product(),
			new Product(),
		];
		
		$dataTable = $this->createDataTable(ProductDataTableType::class, $products);
		
		// $dataTable->hasColumn('createdAt') // true
		// $dataTable->hasColumn('updatedAt') // true
	
		$dataTable = $this->createDataTable(ProductDataTableType::class, $products, [
			'timestampable' => false,
		]);
		
		// $dataTable->hasColumn('createdAt') // false
		// $dataTable->hasColumn('updatedAt') // false
	}
}
```