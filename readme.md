# How to use

Documenation soon!

```twig
{% set table = spreadsheetobject(asset) %}
```

This returns an array of items:
- title
- columnCount
- rows

```twig
{% if table %}
    Name of imported sheet: {{ table.title }}
    Total Columns: {{ table.columnCount }}
    <table>
        {% for row in table.rows %}
            <tr>
                {% for column in row %}
                    <td>{{ column }}</td>
                {% endfor %}
            </tr>
        {% endfor %}
    </table>
{% endif %}

```

## Options

Supports:
- `sheet` - integer of the sheet to import. Defaults to 1;
- `removeRow` - integer of row to delete. Defaults to false
- `removeColumn` - letter value of column to delete. Defaults to false
- `removeColumnByIndex` - index value, starting at 1, of column to delete. Defaults to false

### Example:
```twig
{% set table = asset | spreadsheetobject({
    'removeRow': 1,
    'removeColumnByIndex': 1
}) %} %}
```

## Configuration Options

If the table results should be stored in the database for subsequent reading.
```php
 'storeInDb' => true
```

## Commands

Plugin will watch for deleted and updated assets but if you want to flush all the records: 
```
craft spreadsheet-object/flush/all
```

## Helper Macro

A helper macro is exposed at `_table-helper` which is compatible with table, row and cells.

A simple table example: 
```
{% import '_table-helper' as tableHelper %}
{% set table = asset | spreadsheetobject %}

{{ tableHelper.table(table) }}
``` 

Full example: 
```
{% import '_table-helper' as tableHelper %}
{% set table = asset | spreadsheetobject %}

{{ tableHelper.table(table, {
    thead: false,
    tfoot: false,
    classes: {
        table: false,
        tr: false,
        td: false,
        th: false,
        thead: false,
        tfoot: false,
        first: {
            tr: false,
            td: false,
            th: false,
        },
        last: {
            tr: false,
            td: false,
            th: false
        }
    }
}) }}

```
See: [template code](/src/templates/macros/table.twig)


#### Row only example

```
{% import '_table-helper' as tableHelper %}
{% set table = asset | spreadsheetobject %}

<table>
{% for row in table.rows %}
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
        </tr>
    </thead>
    {{ tableHelper.tr(row) }}
{% endfor %}
</table>
```
_Row helper accepts same options object as full table_

See: [template code](/src/templates/macros/tr.twig)
