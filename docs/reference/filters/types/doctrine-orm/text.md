---
label: Text
order: 900
tags:
  - filters
  - doctrine orm
---

# Text filter type

The `TextFilterType` represents a filter that operates on string values.

{{ include '_integration_bundle_required_note' }}

+---------------------+--------------------------------------------------------------+
| Parent type         | [DoctrineOrmFilterType](doctrine-orm.md)
+---------------------+--------------------------------------------------------------+
| Class               | [:icon-mark-github: TextFilterType](https://github.com/Kreyu/data-table-doctrine-orm-bundle/blob/main/src/Filter/Type/TextFilterType.php)
+---------------------+--------------------------------------------------------------+
| Form Type           | [TextType](https://symfony.com/doc/current/reference/forms/types/text.html)
+---------------------+--------------------------------------------------------------+
| Supported operators | Equals, NotEquals, Contains, NotContains, StartsWith, EndsWith
+---------------------+--------------------------------------------------------------+
| Default operator    | Contains
+---------------------+--------------------------------------------------------------+

## Options

This filter type has no additional options.

## Inherited options

{{ include '../_filter_options' }}
{{ include '_doctrine_orm_filter_options' }}
