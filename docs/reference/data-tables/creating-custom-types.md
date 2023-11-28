---
label: Creating custom types
---

# Creating custom data table types

The data table types allow you to define all the column, filters, actions, exporters etc. for the data table.

## Defining the type

Data table types are PHP classes that implement [`DataTableTypeInterface`](https://), however, it is recommended to extend from [`AbstractDataTableType`](https://).
By convention, they are stored in the `src/DataTable/Type/` directory:

```php # src/DataTable/Type/ProductDataTableType.php
namespace App\DataTable\Type;

use Kreyu\Bundle\DataTableBundle\AbstractDataTableType;

class ProductDataTableType extends AbstractDataTableType
{
}
```

The data table type class can define following methods:

`getParent()`
:   If your custom type is based on another type (i.e. they share some functionality), add this method to return the fully-qualified class name of that original type.
    Do **not** use PHP inheritance for this. The bundle will call all the data table type methods (`buildDataTable()`, `buildView()`, etc.) and type extensions of the parent before calling the ones defined in your custom type. By default, the [`AbstractDataTableType`](https://) class returns the generic [`DataTableType`](https://) type, which is the root parent for all data table types.

`configureOptions()`
:   It defines the option configurable when using the data table type, which are also the options that can be used in the following methods.
    Options are inherited from the parent types and parent type extensions, but you can create any custom option you need.

`buildDataTable()`
:   It configures the data table and may add columns, filters, actions and exporters.

`buildView()`
:   It sets any extra variables you'll need when rendering the data table in a theme template.

## Registering the type as a service

Data table types must be [registered as services](https://) and [tagged](https://) with the `kreyu_data_table.type` tag:

```yaml config/services.yaml
services:
  App\DataTable\Type\ProductDataTableType:
    tags: ['kreyu_data_table.type']
```

If you're using the [default `services.yaml` configuration](https://), this is already done for you, thanks to [autoconfiguration](https://).

## Defining the type

To define the data table, add the `buildDataTable()` method to configure all the columns, filters, etc. included in the product data table:

```php # src/DataTable/Type/ProductDataTableType.php
namespace App\DataTable\Type;

use Kreyu\Bundle\DataTableBundle\DataTableBuilderInterface;
use Kreyu\Bundle\DataTableBundle\Column\Type\TextColumnType;
use Kreyu\Bundle\DataTableBundle\Column\Type\NumberColumnType;
use Kreyu\Bundle\DataTableBundle\Type\AbstractDataTableType;

class ProductDataTableType extends AbstractDataTableType
{
	public function buildDataTable(DataTableBuilderInterface $builder, array $options): void
	{
		$builder
			->addColumn('name', TextColumnType::class)
			->addColumn('quantity', NumberColumnType::class)
		;
	}
}
```

## Adding type configuration options

Imagine, that your project requires to display multiple product-oriented data tables:
- the logisticians should be able to see only the basic details of the product - just a name and the quantity
- the administrators should be able to see extended version of the data table, which includes product identifiers

This is solved with "data table type options", which allow to configure the behavior of the data table types, making them flexible enough to cover multiple use-cases.
The options are defined in the `configureOptions()` method and you can use all the [OptionsResolver component features](https://symfony.com/doc/current/components/options_resolver.html) to define, validate and process their values:

```php # src/DataTable/Type/ProductDataTableType.php
namespace App\DataTable\Type;

use Kreyu\Bundle\DataTableBundle\AbstractDataTableType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductDataTableType extends AbstractDataTableType
{
	// ...
	
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver
			->setDefault('extended', false)
			->setAllowedTypes('extended', 'bool')
		;
	}
}
```

Now you can use this option when using the data table type:

```php # src/Controller/ProductController.php
use App\DataTable\Type\ProductDataTableType;
use Kreyu\Bundle\DataTableBundle\DataTableFactoryAwareTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
	use DataTableFactoryAwareTrait;
	
	public function index(): void
	{
		$dataTable = $this->createDataTable(ProductDataTableType::class, [
			'extended' => $this->getUser()->isAdmin(),
		]);
	}
}
```

The last step is to use these options in the data table type itself:

```php # src/DataTable/Type/ProductDataTableType.php
namespace App\DataTable\Type;

use Kreyu\Bundle\DataTableBundle\DataTableBuilderInterface;
use Kreyu\Bundle\DataTableBundle\Column\Type\TextColumnType;
use Kreyu\Bundle\DataTableBundle\Column\Type\NumberColumnType;
use Kreyu\Bundle\DataTableBundle\Type\AbstractDataTableType;

class ProductDataTableType extends AbstractDataTableType
{
	public function buildDataTable(DataTableBuilderInterface $builder, array $options): void
	{
		if ($options['extended']) {
			$builder->addColumn('id', NumberColumnType::class);
		}
		
		$builder
			->addColumn('name', TextColumnType::class)
			->addColumn('quantity', NumberColumnType::class)
		;
	}

	// ...
}
```
