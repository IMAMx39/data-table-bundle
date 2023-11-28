---
label: DatePeriod
order: f
tags:
  - columns
---

# DatePeriod column type

The `DatePeriodColumnType` represents a column with value displayed as a date (and with time by default).

+-------------+---------------------------------------------------------------------+
| Parent type | [ColumnType](column)
+-------------+---------------------------------------------------------------------+
| Class       | [:icon-mark-github: DatePeriodColumnType](https://github.com/Kreyu/data-table-bundle/blob/main/src/Column/Type/DatePeriodColumnType.php)
+-------------+---------------------------------------------------------------------+

## Options

### `format`

- **type**: `string`
- **default**: `'d.m.Y H:i:s'`

The format supported by [date PHP function](https://www.php.net/date).

## Inherited options

{{ include '_column_options' }}
