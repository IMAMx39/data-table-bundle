---
label: Entity
order: f
tags:
  - filters
  - doctrine orm
---

# Entity filter type

The `EntityFilterType` represents a filter that operates on identifier values.

Displayed as a selector, allows the user to select a specific entity loaded from the database, to query by its identifier.

+---------------------+--------------------------------------------------------------+
| Parent type         | [DoctrineOrmFilterType](doctrine-orm.md)
+---------------------+--------------------------------------------------------------+
| Class               | [:icon-mark-github: EntityFilterType](https://github.com/Kreyu/data-table-bundle/blob/main/src/Filter/Type/EntityFilterType.php)
+---------------------+--------------------------------------------------------------+
| Form Type           | [EntityType](https://symfony.com/doc/current/reference/forms/types/entity.html)
+---------------------+--------------------------------------------------------------+
| Supported operators | Equals, NotEquals, Contains, NotContains
+---------------------+--------------------------------------------------------------+
| Default operator    | Equals
+---------------------+--------------------------------------------------------------+

## Options

This filter type has no additional options.

## Inherited options

{{ option_form_type_default_value = '`\'Symfony\\Bridge\\Doctrine\\Form\\Type\\EntityType\'`' }}

{{ include '../_filter_options' }}
{{ include '_doctrine_orm_filter_options' }}