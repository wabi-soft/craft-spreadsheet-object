# Table Helper

The table helper template variable can be used to quickly form an HTML table from the parsed contents of a spreadsheet file.


## Basic Usage

1. Getting the contents of the asset:
```twig
{% set asset = craft.assets.section('example').one() %}
{% set table = asset | spreadsheetobject() %}
```

2. Feed it to the template variable
```twig
{{ tableHelper.table(table) }}
```

Results in:

```html
<<table data-table-typed="">
  <tbody>
  <tr>
    <td class="px-6 py-1" data-cell-type="text">Austria</td>
    <td class="px-6 py-1" data-cell-type="number--comma">83,858</td>
    <td class="px-6 py-1" data-cell-type="number--comma">8,169,929</td>
    <td class="px-6 py-1" data-cell-type="number--decimal">10.52</td>
    <td class="px-6 py-1" data-cell-type="number">339</td>
    <td class="px-6 py-1" data-cell-type="text">Vienna</td>
  </tr>
  <tr>
    <td class="px-6 py-1" data-cell-type="text">Belgium</td>
    <td class="px-6 py-1" data-cell-type="number--comma">30,528</td>
    <td class="px-6 py-1" data-cell-type="number--comma">11,007,000</td>
    <td class="px-6 py-1" data-cell-type="number--decimal">1050.52</td>
    <td class="px-6 py-1" data-cell-type="number">410</td>
    <td class="px-6 py-1" data-cell-type="text">Brussels</td>
  </tr>
  <tr>
    <td class="px-6 py-1" data-cell-type="text">Country</td>
    <td class="px-6 py-1" data-cell-type="text">Area</td>
    <td class="px-6 py-1" data-cell-type="text">Population</td>
    <td class="px-6 py-1" data-cell-type="text">Testing</td>
    <td class="px-6 py-1" data-cell-type="text">GDP</td>
    <td class="px-6 py-1" data-cell-type="text">Capital</td>
  </tr>
  ....
  </tbody>
</table>
```

## Customizing at Template

By default, each cell gets a data-cell-type attribute and default classes. These can be adjusted on a per-project basis in the config file or on a per table basis by passing an options object.

Full example of overriding the classes:

```twig
{% set asset = craft.assets.section('example').one() %}
{% set table = asset | spreadsheetobject() %}
{% set options = {
    classes: {
        table: 'text-lg my-16',
        tr: 'border-b',
        td: 'px-6 py-2',
        th: 'px-6 py-2',
        thead: 'font-bold text-left',
        tfoot: 'text-sm',
        first: {
            tr: 'border-b-2',
            td: 'pl-0',
            th: 'pl-0',
        },
        last: {
            tr: 'border-0',
            td: false,
            th: false,
        }
    }
} %}
{{ tableHelper.table(table, options) }}
```

## Manipulating Render

In addition to adding classes, the template variable can manipulate what is rendered. You can:
- Remove rows
- Pull specific rows
- Add first row as thead
- Sort columns based on index 


### Remove Rows:
```twig
{{ tableHelper.table(table, {
    removeRows: '1',
}) }}
```


### Specific Rows:
```twig
{{ tableHelper.table(table, {
    onlyRows: '1,2,3',
}) }}
```

### First Row is Heading Row:
```twig
{{ tableHelper.table(table, {
    thead: true,
}) }}
```


### Sort Columns by Index
```twig
{{ tableHelper.table(table, {
    sortColumnIndex: 1
}) }}
```

## Additional Options

### Last Row as Footer

```twig
{% set options = {
    tfoot: true
} %}
```


### Add Footer

```twig
{% set footer %}
   <p>Footer Content</p>
{% endset %}

{% set options = {
    tfoot: footer
} %}
```

### Table Style | string

```twig
{% set options = {
    style: 'fancy',
} %}
```

### Add Caption
```twig

{% set caption %}
   <p>Footer Content</p>
{% endset %}

{% set options = {
    caption: caption,
} %}
```

### Apply Cell-Type Attribute | bool
```twig
{% set options = {
    autoCellDataAttribute: true,
} %}
```

### Apply Cell-Type to Heading Based on Next Row

The first row that is a heading may not represent the next row's type so this looks at the next row to base the cell type on.

```twig
{% set options = {
    applyNextRowTypeToHeading: true,
} %}
```


## All Options

```twig
{% set options = {
    thead: true,
    tfoot: footer
    style: 'fancy',
    caption: caption,
    autoCellDataAttribute: true,
    applyNextRowTypeToHeading: true,
    removeRows: 1,
    classes: {
        table: 'text-lg my-16',
        tr: 'border-b',
        td: 'px-6 py-2',
        th: 'px-6 py-2',
        thead: 'font-bold text-left',
        tfoot: 'text-sm',
        first: {
            tr: 'border-b-2',
            td: 'pl-0',
            th: 'pl-0',
        },
        last: {
            tr: 'border-0',
            td: false,
            th: false,
        }
    }
} %}

```
