<?php

namespace wabisoft\spreadsheetobject\services;

use craft\helpers\ArrayHelper;
use craft\helpers\StringHelper;

/**
 * Service class for sorting and modifying table rows and columns
 */
class TableSort
{
    /**
     * Sorts table rows based on specified column and options
     *
     * @param array $rows The rows to sort
     * @param array $options Sorting options including thead, sortColumnIndex, etc.
     * @return array The sorted rows
     */
    public static function sortRows(array $rows, array $options): array
    {
        if (empty($rows)) {
            return $rows;
        }

        $hasHeading = $options['thead'] ?? false;
        $sortConfig = self::parseSortConfig($options['sortColumnIndex'] ?? 0);
        
        // Extract and remove heading if present
        $heading = $hasHeading ? [array_shift($rows)] : [];
        
        // Sort the data rows
        ArrayHelper::multisort(
            $rows,
            $sortConfig['key'],
            $sortConfig['direction'],
            $sortConfig['flag']
        );

        // Reattach heading if it existed
        return $hasHeading ? ArrayHelper::merge($heading, $rows) : $rows;
    }

    /**
     * Removes specified columns from the table
     *
     * @param array $rows The table rows
     * @param string|array|null $order The columns to remove
     * @return array The modified rows
     */
    public static function removeColumns(array $rows, string|array|null $order = null): array
    {
        return self::modifyColumns($rows, $order);
    }

    /**
     * Removes specified rows from the table
     *
     * @param array $rows The table rows
     * @param string|array|null $order The rows to remove
     * @return array The modified rows
     */
    public static function removeRows(array $rows, string|array|null $order = null): array
    {
        return self::modifyRows($rows, $order);
    }

    /**
     * Keeps only specified rows in the table
     *
     * @param array $rows The table rows
     * @param string|array|null $order The rows to keep
     * @return array The modified rows
     */
    public static function onlyRows(array $rows, string|array|null $order = null): array
    {
        return self::modifyRows($rows, $order, 'only');
    }

    /**
     * Keeps only specified columns in the table
     *
     * @param array $rows The table rows
     * @param string|array|null $order The columns to keep
     * @return array The modified rows
     */
    public static function onlyColumns(array $rows, string|array|null $order = null): array
    {
        return self::modifyColumns($rows, $order, 'only');
    }

    /**
     * Modifies rows based on specified order and type
     *
     * @param array $rows The rows to modify
     * @param string|array|null $order The order specification
     * @param string $type The type of modification ('remove' or 'only')
     * @return array The modified rows
     */
    private static function modifyRows(array $rows, string|array|null $order, string $type = 'remove'): array
    {
        if (empty($order)) {
            return $rows;
        }

        $orderArray = self::parseOrder($order);
        $updated = array_filter(
            $rows,
            fn($row, $key) => self::shouldModify($key + 1, $orderArray, $type),
            ARRAY_FILTER_USE_BOTH
        );

        return empty($updated) ? $rows : array_values($updated);
    }

    /**
     * Modifies columns based on specified order and type
     *
     * @param array $rows The rows to modify
     * @param string|array|null $order The order specification
     * @param string $type The type of modification ('remove' or 'only')
     * @return array The modified rows
     */
    private static function modifyColumns(array $rows, string|array|null $order, string $type = 'remove'): array
    {
        if (empty($order)) {
            return $rows;
        }

        $orderArray = self::parseOrder($order);
        
        return array_map(
            fn($row) => self::filterColumns($row, $orderArray, $type),
            $rows
        );
    }

    /**
     * Parses the sort configuration from options
     *
     * @param mixed $sortConfig The sort configuration
     * @return array The parsed configuration
     */
    private static function parseSortConfig(mixed $sortConfig): array
    {
        if (is_array($sortConfig)) {
            return [
                'key' => $sortConfig['key'] ?? 0,
                'direction' => $sortConfig['direction'] ?? SORT_ASC,
                'flag' => $sortConfig['sortFlag'] ?? SORT_REGULAR
            ];
        }

        return [
            'key' => is_numeric($sortConfig) ? $sortConfig - 1 : $sortConfig,
            'direction' => SORT_ASC,
            'flag' => SORT_REGULAR
        ];
    }

    /**
     * Parses the order specification into an array
     *
     * @param string|array $order The order specification
     * @return array The parsed order array
     */
    private static function parseOrder(string|array $order): array
    {
        return is_array($order) ? $order : StringHelper::split($order);
    }

    /**
     * Determines if an item should be modified based on order and type
     *
     * @param int $index The item index
     * @param array $orderArray The order array
     * @param string $type The modification type
     * @return bool Whether the item should be modified
     */
    private static function shouldModify(int $index, array $orderArray, string $type): bool
    {
        return $type === 'remove' ? !in_array($index, $orderArray) : in_array($index, $orderArray);
    }

    /**
     * Filters columns in a row based on order and type
     *
     * @param array $row The row to filter
     * @param array $orderArray The order array
     * @param string $type The modification type
     * @return array The filtered row
     */
    private static function filterColumns(array $row, array $orderArray, string $type): array
    {
        $filtered = array_filter(
            $row,
            fn($_, $key) => self::shouldModify($key + 1, $orderArray, $type),
            ARRAY_FILTER_USE_BOTH
        );

        return empty($filtered) ? $row : array_values($filtered);
    }
}
