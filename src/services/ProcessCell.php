<?php

namespace wabisoft\spreadsheetobject\services;

use wabisoft\spreadsheetobject\Plugin;

/**
 * Service class for processing and modifying spreadsheet cells
 */
class ProcessCell
{
    /**
     * Modifies a cell based on its properties and available templates
     *
     * @param string $cell The cell content to modify
     * @param array $properties Cell properties including row, column, and style information
     * @return string The modified cell content
     */
    public static function modifyCell(string $cell, array $properties): string
    {
        $rowProperties = $properties['tr'] ?? false;
        $columnProperties = $properties['td'] ?? false;
        $style = $properties['style'] ?? false;

        $templatePaths = self::getTemplatePaths($rowProperties, $columnProperties, $style);

        if (!$templatePaths) {
            return $cell;
        }

        if (!class_exists('wabisoft\framework\services\TemplateLoader')) {
            return $cell;
        }

        $modifiedCell = \wabisoft\framework\services\TemplateLoader::load($templatePaths, ['cell' => $cell]);

        return $modifiedCell ?: $cell;
    }

    /**
     * Gets the template paths based on row, column, and style properties
     *
     * @param array|false $rowProperties Row properties
     * @param array|false $columnProperties Column properties
     * @param string|false $style Style identifier
     * @return array|false Array of template paths or false if no templates available
     */
    private static function getTemplatePaths($rowProperties, $columnProperties, $style)
    {
        $settings = Plugin::getInstance()->getSettings();
        $templates = $settings->cellModifierTemplates;
        $defaultTemplate = $settings->defaultTemplateName;
        $rowTemplatePrefix = $settings->rowTemplates;
        $columnTemplatePrefix = $settings->columnTemplates;

        if (!$templates || (!$rowProperties && !$columnProperties && !$style)) {
            return false;
        }

        // Extract row properties
        $rowIndex = $rowProperties['index'] ?? '';
        $isFirstRow = $rowProperties['first'] ?? false;
        $isLastRow = $rowProperties['last'] ?? false;

        // Extract column properties
        $columnIndex = $columnProperties['index'] ?? '';
        $isFirstColumn = $columnProperties['first'] ?? false;
        $isLastColumn = $columnProperties['last'] ?? false;

        // Determine row path
        $rowPath = $rowIndex;
        if ($isFirstRow) {
            $rowPath = 'first';
        } elseif ($isLastRow) {
            $rowPath = 'last';
        }

        // Determine column path
        $columnPath = $columnIndex;
        if ($isFirstColumn) {
            $columnPath = 'first';
        } elseif ($isLastColumn) {
            $columnPath = 'last';
        }

        // Build template paths in order of specificity
        $paths = [
            self::assemblePaths([$style, $rowTemplatePrefix . $rowPath, $columnTemplatePrefix . $columnPath]),
            self::assemblePaths([$style, $rowTemplatePrefix . $rowPath]),
            self::assemblePaths([$style, $columnTemplatePrefix . $columnPath]),
            self::assemblePaths([$rowTemplatePrefix . $rowPath, $columnTemplatePrefix . $columnPath]),
            self::assemblePaths([$rowTemplatePrefix . $rowPath]),
            self::assemblePaths([$columnTemplatePrefix . $columnPath]),
            self::assemblePaths([$style, $defaultTemplate]),
            self::assemblePaths([$style]),
            self::assemblePaths([$defaultTemplate])
        ];

        return $paths;
    }

    /**
     * Assembles template paths from segments
     *
     * @param array $segments Path segments to combine
     * @return string The assembled template path
     */
    private static function assemblePaths(array $segments): string
    {
        $templates = Plugin::getInstance()->getSettings()->cellModifierTemplates;
        return $templates . '/' . implode('/', $segments);
    }
}
