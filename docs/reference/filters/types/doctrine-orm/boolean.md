---
label: Boolean
order: 800
tags:
  - filters
  - doctrine orm
---

# Boolean filter type

The `BooleanFilterType` represents a filter that operates on boolean values.

{{ include '_integration_bundle_required_note' }}

+---------------------+--------------------------------------------------------------+
| Parent type         | [DoctrineOrmFilterType](doctrine-orm.md)
+---------------------+--------------------------------------------------------------+
| Class               | [:icon-mark-github: BooleanFilterType](https://github.com/Kreyu/data-table-doctrine-orm-bundle/blob/main/src/Filter/Type/BooleanFilterType.php)
+---------------------+--------------------------------------------------------------+
| Form Type           | [ChoiceType](https://symfony.com/doc/current/reference/forms/types/choice.html)
+---------------------+--------------------------------------------------------------+
| Supported operators | Equals, NotEquals
+---------------------+--------------------------------------------------------------+
| Default operator    | Equals
+---------------------+--------------------------------------------------------------+

## Options

This filter type has no additional options.

## Inherited options

{{ option_form_type_default_value = '`\'Symfony\\Component\\Form\\Extension\\Core\\Type\\ChoiceType\'`' }}

{{ include '../_filter_options' }}
{{ include '_doctrine_orm_filter_options' }}
