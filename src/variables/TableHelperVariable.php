<?php

namespace wabisoft\spreadsheetobject\variables;

use craft\helpers\ArrayHelper;
use wabisoft\spreadsheetobject\services\CellType;
use wabisoft\spreadsheetobject\services\ProcessCell;
use wabisoft\spreadsheetobject\services\TableSort;
use wabisoft\spreadsheetobject\Plugin;

/**
 * Variable class for table helper functionality in templates
 */
class TableHelperVariable
{
    /**
     * Default table options configuration
     */
    public const DEFAULT_TABLE_OPTIONS = [
        'thead' => false,
        'tfoot' => false,
        'caption' => false,
        'autoCellDataAttribute' => true,
        'applyNextRowTypeToHeading' => true,
        'sortColumnIndex' => false,
        'removeColumns' => false,
        'removeRows' => false,
        'onlyColumns' => false,
        'onlyRows' => false,
        'classes' => [
            'table' => false,
            'tr' => false,
            'td' => false,
            'th' => false,
            'thead' => false,
            'tfoot' => false,
            'first' => [
                'tr' => false,
                'td' => false,
                'th' => false,
            ],
            'last' => [
                'tr' => false,
                'td' => false,
                'th' => false,
            ]
        ]
    ];

    /**
     * Gets the default options merged with plugin settings
     *
     * @return array The merged options
     */
    public static function getDefaultOptions(): array
    {
        $config = Plugin::getInstance()->getSettings()->tableOptions;
        return ArrayHelper::merge(self::DEFAULT_TABLE_OPTIONS, $config);
    }

    /**
     * Gets the data type of a string value
     *
     * @param string $string The string to analyze
     * @param bool $render Whether to render the type
     * @return string|false The data type or false if rendering is disabled
     */
    public static function getDataType(string $string, bool $render = true): string|false
    {
        return CellType::getDataType($string, $render);
    }

    /**
     * Gets sorted rows based on options
     *
     * @param array $rows The rows to sort
     * @param array $options Sorting options
     * @return array The sorted rows
     */
    public static function getSortedRows(array $rows, array $options): array
    {
        return TableSort::sortRows($rows, $options);
    }

    /**
     * Removes specified columns from the table
     *
     * @param array $rows The table rows
     * @param array $options Options containing removeColumns
     * @return array The modified rows
     */
    public static function removeColumns(array $rows, array $options): array
    {
        return TableSort::removeColumns($rows, $options['removeColumns'] ?? null);
    }

    /**
     * Keeps only specified columns in the table
     *
     * @param array $rows The table rows
     * @param array $options Options containing onlyColumns
     * @return array The modified rows
     */
    public static function onlyColumns(array $rows, array $options): array
    {
        return TableSort::onlyColumns($rows, $options['onlyColumns'] ?? null);
    }

    /**
     * Removes specified rows from the table
     *
     * @param array $rows The table rows
     * @param array $options Options containing removeRows
     * @return array The modified rows
     */
    public static function removeRows(array $rows, array $options): array
    {
        return TableSort::removeRows($rows, $options['removeRows'] ?? null);
    }

    /**
     * Keeps only specified rows in the table
     *
     * @param array $rows The table rows
     * @param array $options Options containing onlyRows
     * @return array The modified rows
     */
    public static function onlyRows(array $rows, array $options): array
    {
        return TableSort::onlyRows($rows, $options['onlyRows'] ?? null);
    }

    /**
     * Modifies a cell based on its properties
     *
     * @param string $cell The cell content to modify
     * @param array $properties Cell properties
     * @return string The modified cell content
     */
    public static function modifyCell(string $cell, array $properties = []): string
    {
        return ProcessCell::modifyCell($cell, $properties);
    }
}
