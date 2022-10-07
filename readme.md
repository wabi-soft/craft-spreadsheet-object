# How to use

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
