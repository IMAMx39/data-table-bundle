---
label: Callback
order: 850
tags:
  - filters
---

# Callback filter type

The `CallbackFilterType` represents a filter that operates on identifier values.

Displayed as a selector, allows the user to select a specific entity loaded from the database, to query by its identifier.

+---------------------+--------------------------------------------------------------+
| Parent type         | [FilterType](filter.md)
+---------------------+--------------------------------------------------------------+
| Class               | [:icon-mark-github: CallbackFilterType](https://github.com/Kreyu/data-table-bundle/blob/main/src/Filter/Type/CallbackFilterType.php)
+---------------------+--------------------------------------------------------------+
| Form Type           | [TextType](https://symfony.com/doc/current/reference/forms/types/text.html)
+---------------------+--------------------------------------------------------------+
| Supported operators | Supports all operators
+---------------------+--------------------------------------------------------------+
| Default operator    | Equals
+---------------------+--------------------------------------------------------------+

## Options

### `callback`

**type**: `callable`

Sets callable that operates on the query passed as a first argument:

```php #
use Kreyu\Bundle\DataTableBundle\Filter\FilterData;
use Kreyu\Bundle\DataTableBundle\Filter\FilterInterface;
use Kreyu\Bundle\DataTableBundle\Filter\Type\CallbackFilterType;
use Kreyu\Bundle\DataTableBundle\Query\ProxyQueryInterface;

$builder
    ->addFilter('type', CallbackFilterType::class, [
        'callback' => function (ProxyQueryInterface $query, FilterData $data, FilterInterface $filter): void {
            // ...
        } 
    ])
```

## Inherited options

{{ include '../_filter_options' }}
