---
order: a
---

# Global actions

Global actions are actions in the context of the whole data table, not tied to any specific row.

## Adding global actions

To add global action, use data table builder's `addAction()` method:

```php #15-18 src/DataTable/Type/ProductDataTableType.php
use Kreyu\Bundle\DataTableBundle\DataTableBuilderInterface;
use Kreyu\Bundle\DataTableBundle\Type\AbstractDataTableType;
use Kreyu\Bundle\DataTableBundle\Action\Type\ButtonActionType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProductDataTableType extends AbstractDataTableType
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }
    
    public function buildDataTable(DataTableBuilderInterface $builder, array $options): void
    {
        $builder->addAction('create', ButtonActionType::class, [
            'label' => 'Create new product',
            'href' => $this->urlGenerator->generate('app_product_create'),
        ]);
    }
}
```

The same method can also be used on already created data tables:

```php #20-23 src/Controller/ProductController.php
use App\DataTable\Type\ProductDataTableType;
use Kreyu\Bundle\DataTableBundle\DataTableFactoryAwareTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProductController extends AbstractController
{
    use DataTableFactoryAwareTrait;
    
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function index(Request $request)
    {
        $dataTable = $this->createDataTable(ProductDataTableType::class);
        
        $dataTable->addAction('create', ButtonActionType::class, [
            'label' => 'Create new product',
            'href' => $this->urlGenerator->generate('app_product_create'),
        ]);
    }
}
```

This method accepts _three_ arguments:

- action name;
- action type — with a fully qualified class name;
- action options — defined by the action type, used to configure the action;

For reference, see [built-in action types](../../reference/actions/types.md).

## Removing global actions

To remove existing global action, use the builder's `removeAction()` method:

```php #14 src/DataTable/Type/ProductDataTableType.php
use Kreyu\Bundle\DataTableBundle\DataTableBuilderInterface;
use Kreyu\Bundle\DataTableBundle\Type\AbstractDataTableType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProductDataTableType extends AbstractDataTableType
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }
    
    public function buildDataTable(DataTableBuilderInterface $builder, array $options): void
    {
        $builder->removeAction('create');
    }
}
```

The same method can also be used on already created data tables:

```php #14 src/Controller/ProductController.php
use App\DataTable\Type\ProductDataTableType;
use Kreyu\Bundle\DataTableBundle\DataTableFactoryAwareTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{
    use DataTableFactoryAwareTrait;
    
    public function index(Request $request)
    {
        $dataTable = $this->createDataTable(ProductDataTableType::class);
        
        $dataTable->removeAction('create');
    }
}
```

Any attempt of removing the non-existent action will silently fail.

## Retrieving global actions

To retrieve already defined global actions, use the builder's `getActions()` or `getAction()` method:

```php # src/DataTable/Type/ProductDataTableType.php
use Kreyu\Bundle\DataTableBundle\DataTableBuilderInterface;
use Kreyu\Bundle\DataTableBundle\Type\AbstractDataTableType;

class ProductDataTableType extends AbstractDataTableType
{
    public function buildDataTable(DataTableBuilderInterface $builder, array $options): void
    {
        // retrieve all previously defined actions:
        $actions = $builder->getActions();
        
        // or specific action:
        $action = $builder->getAction('create');
        
        // or simply check whether the action is defined:
        if ($builder->hasAction('create')) {
            // ...
        }
    }
}
```

The same methods are accessible on already created data tables:

```php # src/Controller/ProductController.php
use App\DataTable\Type\ProductDataTableType;
use Kreyu\Bundle\DataTableBundle\DataTableFactoryAwareTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{
    use DataTableFactoryAwareTrait;
    
    public function index(Request $request)
    {
        $dataTable = $this->createDataTable(ProductDataTableType::class);
        
        // retrieve all previously defined actions:
        $actions = $dataTable->getActions();
        
        // or specific action:
        $action = $dataTable->getAction('create');
        
        // or simply check whether the action is defined:
        if ($dataTable->hasAction('create')) {
            // ...
        }
    }
}
```

!!!warning Warning
Any attempt of retrieving a non-existent action will result in an `OutOfBoundsException`.  
To check whether the global action of given name exists, use the `hasAction()` method.
!!!

!!!danger Important
Within the data table builder, the actions are still in their build state!
Therefore, actions retrieved by the methods:

- `DataTableBuilderInterface::getActions()`
- `DataTableBuilderInterface::getAction(string $name)`

...are instance of `ActionBuilderInterface`, whereas methods:

- `DataTableInterface::getActions()`
- `DataTableInterface::getAction(string $name)`

...return instances of `ActionInterface` instead.
!!!

## Adding action confirmation

Actions can be configured to require confirmation (by the user) before being executed.

![Action confirmation modal with the Tabler theme](../../static/action_confirmation_modal.png)

To enable confirmation in the quickest way, set the action's `confirmation` option to `true`:

```php #10 src/DataTable/Type/ProductDataTableType.php
use Kreyu\Bundle\DataTableBundle\DataTableBuilderInterface;
use Kreyu\Bundle\DataTableBundle\Type\AbstractDataTableType;
use Kreyu\Bundle\DataTableBundle\Action\Type\ButtonActionType;

class ProductDataTableType extends AbstractDataTableType
{
    public function buildDataTable(DataTableBuilderInterface $builder, array $options): void
    {
        $builder->addAction('create', ButtonActionType::class, [
            'confirmation' => true,
        ]);
    }
}
```

To configure the confirmation modal, pass the array as the `confirmation` option:

```php #10-17 src/DataTable/Type/ProductDataTableType.php
use Kreyu\Bundle\DataTableBundle\DataTableBuilderInterface;
use Kreyu\Bundle\DataTableBundle\Type\AbstractDataTableType;
use Kreyu\Bundle\DataTableBundle\Action\Type\ButtonActionType;

class ProductDataTableType extends AbstractDataTableType
{
    public function buildDataTable(DataTableBuilderInterface $builder, array $options): void
    {
        $builder->addAction('create', ButtonActionType::class, [
            'confirmation' => [
                'translation_domain' => 'KreyuDataTable',
                'label_title' => 'Action confirmation',
                'label_description' => 'Are you sure you want to execute this action?',
                'label_confirm' => 'Confirm',
                'label_cancel' => 'Cancel',
                'type' => 'danger', // "danger", "warning" or "info"
            ],
        ]);
    }
}
```

For reference, see [action's `confirmation` option documentation](../../reference/actions/types/action/#confirmation).

## Conditionally rendering the action

Action visibility can be configured using its [`visible` option](../../reference/actions/types/action/#visible):

```php
use Kreyu\Bundle\DataTableBundle\Action\Type\ButtonActionType;

$builder
    ->addAction('create', ButtonActionType::class, [
        'visible' => $this->isGranted('ROLE_ADMIN'),
    ])
;
```

Another approach would be simply not adding the action at all:

```php
use Kreyu\Bundle\DataTableBundle\Action\Type\ButtonActionType;

if ($this->isGranted('ROLE_ADMIN')) {
    $builder->addAction('create', ButtonActionType::class);
}
```

What differentiates those two methods, is that by using the `visible` option, the action is still defined in the data table, but is not rendered in the view.
It may be useful in some cases, for example, when the actions can be modified outside the data table builder.
