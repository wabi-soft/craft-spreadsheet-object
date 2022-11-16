# Spreadsheet to Code


1. Spreadsheet Object wraps a standard Craft element. First get an asset element:
```twig
{% set asset = craft.assets.section('example').one() %}
```

2. Then apply the spreadsheetobject filter to the asset
```twig
{% set table = asset | spreadsheetobject() %}
```

At this point you have a twig object that you can loop through the rows and columns of the Spreadsheet's content. If you have the cache option enabled (default) this will also store the content's in the database. 

Example dump:

```php
array(4) { ["title"]=> string(20) "Sheet 1 - convertcsv" ["columnCount"]=> int(5) ["rowCount"]=> int(16) ["rows"]=> array(16) { [0]=> array(1) { [0]=> string(10) "convertcsv" } [1]=> array(6) { [0]=> string(7) "Country" [1]=> string(4) "Area" [2]=> string(10) "Population" [3]=> string(7) "Testing" [4]=> string(3) "GDP" [5]=> string(7) "Capital" } [2]=> array(6) { [0]=> string(7) "Austria" [1]=> string(6) "83,858" [2]=> string(9) "8,169,929" [3]=> string(5) "10.52" [4]=> string(3) "339" [5]=> string(6) "Vienna" } [3]=> array(6) { [0]=> string(7) "Belgium" [1]=> string(6) "30,528" [2]=> string(10) "11,007,000" [3]=> string(7) "1050.52" [4]=> string(3) "410" [5]=> string(8) "Brussels" } [4]=> array(6) { [0]=> string(7) "Denmark" [1]=> string(6) "43,094" [2]=> string(9) "5,564,219" [3]=> string(5) "10.52" [4]=> string(3) "271" [5]=> string(10) "Copenhagen" } [5]=> array(6) { [0]=> string(6) "France" [1]=> string(7) "547,030" [2]=> string(10) "66,104,000" [3]=> string(5) "10.52" [4]=> string(5) "2,181" [5]=> string(5) "Paris" } [6]=> array(6) { [0]=> string(7) "Germany" [1]=> string(7) "357,021" [2]=> string(10) "80,716,000" [3]=> string(4) "1.52" [4]=> string(5) "3,032" [5]=> string(6) "Berlin" } [7]=> array(6) { [0]=> string(6) "Greece" [1]=> string(7) "131,957" [2]=> string(10) "11,123,034" [3]=> string(4) "10.5" [4]=> string(3) "176" [5]=> string(6) "Athens" } [8]=> array(6) { [0]=> string(7) "Ireland" [1]=> string(6) "70,280" [2]=> string(9) "4,234,925" [3]=> string(7) "1050.52" [4]=> string(3) "255" [5]=> string(6) "Dublin" } [9]=> array(6) { [0]=> string(5) "Italy" [1]=> string(7) "301,230" [2]=> string(10) "60,655,464" [3]=> string(7) "1050.52" [4]=> string(5) "1,642" [5]=> string(4) "Rome" } [10]=> array(6) { [0]=> string(10) "Luxembourg" [1]=> string(5) "2,586" [2]=> string(7) "448,569" [3]=> string(7) "1050.52" [4]=> string(2) "51" [5]=> string(10) "Luxembourg" } [11]=> array(6) { [0]=> string(11) "Netherlands" [1]=> string(6) "41,526" [2]=> string(10) "16,902,103" [3]=> string(7) "1050.52" [4]=> string(3) "676" [5]=> string(9) "Amsterdam" } [12]=> array(6) { [0]=> string(8) "Portugal" [1]=> string(6) "91,568" [2]=> string(10) "10,409,995" [3]=> string(7) "1050.52" [4]=> string(3) "179" [5]=> string(6) "Lisbon" } [13]=> array(6) { [0]=> string(5) "Spain" [1]=> string(7) "504,851" [2]=> string(10) "47,059,533" [3]=> string(7) "1050.52" [4]=> string(5) "1,075" [5]=> string(6) "Madrid" } [14]=> array(6) { [0]=> string(6) "Sweden" [1]=> string(7) "449,964" [2]=> string(9) "9,090,113" [3]=> string(7) "1050.52" [4]=> string(3) "447" [5]=> string(9) "Stockholm" } [15]=> array(6) { [0]=> string(14) "United Kingdom" [1]=> string(7) "244,820" [2]=> string(10) "65,110,000" [3]=> string(7) "1050.52" [4]=> string(5) "2,727" [5]=> string(6) "London" } } }
```
