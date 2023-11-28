---
label: DateRange
order: 650
tags:
  - filters
  - doctrine orm
---

# DateRange filter type

The `DateRangeFilterType` represents a filter that operates on a date range.

{{ include '_integration_bundle_required_note' }}

+---------------------+--------------------------------------------------------------+
| Parent type         | [DoctrineOrmFilterType](doctrine-orm.md)
+---------------------+--------------------------------------------------------------+
| Class               | [:icon-mark-github: DateRangeFilterType](https://github.com/Kreyu/data-table-doctrine-orm-bundle/blob/main/src/Filter/Type/DateRangeFilterType.php)
+---------------------+--------------------------------------------------------------+
| Form Type           | [:icon-mark-github: DateRangeType](https://github.com/Kreyu/data-table-bundle/blob/main/src/Filter/Form/Type/DateRangeType.php)
+---------------------+--------------------------------------------------------------+
| Supported operators | Equals
+---------------------+--------------------------------------------------------------+
| Default operator    | Equals
+---------------------+--------------------------------------------------------------+

## Options

This filter type has no additional options.

## Inherited options

{{ option_form_type_default_value = '`\'Symfony\\Component\\Form\\Extension\\Core\\Type\\DateTimeType\'`' }}

{% capture option_empty_data_note %}
If form option `widget` equals `'choice'` or `'text'` then the normalizer changes default value to: 
```
[
    'date' => [
        'day' => '', 
        'month' => '', 
        'year' => ''
    ]
]
```
{% endcapture %}

{% capture option_form_options_notes %}
!!!
**Note**: If the `form_type` is `DateTimeType`, the normalizer adds a default `['widget' => 'single_text']`.
!!!
{% endcapture %}

{{ include '../_filter_options' }}
{{ include '_doctrine_orm_filter_options' }}