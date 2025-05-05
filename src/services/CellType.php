<?php

namespace wabisoft\spreadsheetobject\services;

use craft\helpers\StringHelper;

/**
 * Service class for determining cell data types in spreadsheets.
 */
class CellType
{
    /**
     * Determines the data type of a string value.
     *
     * @param string $string The input string to analyze
     * @param bool $render Whether to render the type (default: true)
     * @return string|false The data type or false if rendering is disabled
     */
    public static function getDataType(string $string, bool $render = true): string|false
    {
        if (!$render) {
            return false;
        }

        $string = StringHelper::trim($string);
        
        if ($string === '') {
            return 'empty';
        }

        // Remove commas for number validation
        $numberString = StringHelper::replace($string, ',', '');
        
        if ($numberString === '') {
            return 'empty';
        }

        if (is_numeric($numberString)) {
            if (StringHelper::contains($string, ',', false)) {
                return 'number--comma';
            }
            
            if (StringHelper::contains($string, '.', false)) {
                return 'number--decimal';
            }
            
            return 'number';
        }

        return 'text';
    }
}
