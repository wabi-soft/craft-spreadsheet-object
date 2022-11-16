# Advanced Usage

In some cases you may want to override specific cells, rows or columns programmatically in Twig templates. An example could be automatically searching for product numbers in a particular column. 

The plugin looks for override templates, paths based on your plugin config in the following sequence:

```twig
<style>/<row_number><tr_path>/<column_number><td_path>
<style>/<row_number><tr_path>
<style>/<column_number><td_path>
<style>/<tr_path>
<style>/<td_path>
<style>/<default>
<style>
<default>
```


As an example, if you haven't overridden the default `_cells` and a `style: "products"` template override path in the config, a file in the following directory:

```
_cells
   |-- products
       |-- row 
           |-- 2.twig       
```

where `2.twig` has the contents of:

```twig
<div class="text-left font-mono text-xs" x-data="{}">
    Customize this cell: {{ cell }}
</div>
```

would render all cells in row 2 with the above markup, with `{{ cell }}` rendering the content of the a actuall cell.

**Helpers**
This can get complicated fast and confusing. To help figure out where modifications are coming from or where you should apply them there are two URL parameters that you can append at the end of the URL on pages that are rendering the tables.

```
?show-template-hierarchy
```
If an override is being applied, shows the full hierarchy of where each cell is looking for overrides and highlighting the most specific match.


```
?show-template-path
```
If an override is being applied, this indicates the template path that the cell is currently matching.
