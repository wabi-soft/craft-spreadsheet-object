<!--- BEGIN HEADER -->
# Changelog

All notable changes to this project will be documented in this file.
<!--- END HEADER -->

## [2.2.0](https://github.com/wabi-soft/craft-spreadsheet-object/compare/v2.1.0...v2.2.0) (2026-02-12)

### Bug Fixes

* Fix XSS vulnerability in cell rendering via HtmlPurifier::process()
* Fix array_filter dropping zero-value cells and corrupting column alignment
* Fix sheet selection — getActiveSheet() now correctly uses getSheet() with index
* Fix rowCount/columnCount calculated from processed data instead of raw worksheet
* Add try/catch around IOFactory::load() for corrupt/protected files
* Fix multi-site cache collision by adding siteId to query
* Fix nl2br escaping HTML output in td.twig and th.twig

### Features

* Add plugin icon (table + curly braces)
* Add console output to flush command
* Add class-existence check for wabisoft/framework dependency
* Expand sourceData column to MEDIUMTEXT, configuration to TEXT via migration

### Maintenance

* Remove dead splitRange() method
* Consolidate branches to single main branch

---

## [2.1.0](https://github.com/wabi-soft/craft-spreadsheet-object/compare/v2.0.6...v2.1.0) (2026-02-03)


---

## [2.0.6](https://github.com/wabi-soft/craft-spreadsheet-object/compare/v2.0.5...v2.0.6) (2025-05-05)

### Bug Fixes

* Sorting by default ([7aadee](https://github.com/wabi-soft/craft-spreadsheet-object/commit/7aadee3267f6a88fa5ad318147386a542be184b7))


---

## [2.0.0](https://github.com/wabi-soft/craft-spreadsheet-object/compare/v1.0.1...v2.0.0) (2025-05-05)

### Bug Fixes

* Merge ([7d9274](https://github.com/wabi-soft/craft-spreadsheet-object/commit/7d92743c4704e95b317e2923c5f8ee80daf281c6))


---

## [1.0.1](https://github.com/wabi-soft/craft-spreadsheet-object/compare/v1.0.0...v1.0.1) (2023-06-23)


---

