---
label: Number
order: 850
tags:
  - filters
  - doctrine orm
---

# Number filter type

The `NumberFilterType` represents a filter that operates on numeric values.

{{ include '_integration_bundle_required_note' }}

+---------------------+--------------------------------------------------------------+
| Parent type         | [DoctrineOrmFilterType](doctrine-orm.md)
+---------------------+--------------------------------------------------------------+
| Class               | [:icon-mark-github: NumberFilterType](https://github.com/Kreyu/data-table-doctrine-orm-bundle/blob/main/src/Filter/Type/NumberFilterType.php)
+---------------------+--------------------------------------------------------------+
| Form Type           | [TextType](https://symfony.com/doc/current/reference/forms/types/text.html)
+---------------------+--------------------------------------------------------------+
| Supported operators | Equals, NotEquals, GreaterThan, GreaterThanEquals, LessThan, LessThanEquals
+---------------------+--------------------------------------------------------------+
| Default operator    | Equals
+---------------------+--------------------------------------------------------------+

## Options

This filter type has no additional options.

## Inherited options

{{ option_form_type_default_value = '`\'Symfony\\Component\\Form\\Extension\\Core\\Type\\NumberType\'`' }}

{{ include '../_filter_options' }}
{{ include '_doctrine_orm_filter_options' }}
