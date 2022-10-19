<?php

namespace wabisoft\spreadsheetobject\variables;

use craft\helpers\ArrayHelper;
use wabisoft\spreadsheetobject\services\CellType;
use wabisoft\spreadsheetobject\services\ProcessCell;
use wabisoft\spreadsheetobject\services\TableSort;

class TableHelperVariable
{
    const DEFAULT_TABLE_OPTIONS = [
        'thead' => false,
        'tfoot' => false,
        'caption' => false,
        'autoCellDataAttribute' => true,
        'applyNextRowTypeToHeading' => true,
        'sortColumnIndex' => false,
        'removeColumns' => false,
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

    public static function getDefaultOptions() {
      $config =  \wabisoft\spreadsheetobject\Plugin::getInstance()->getSettings()->tableOptions;
      return ArrayHelper::merge( self::DEFAULT_TABLE_OPTIONS, $config);
    }
    public static function getDataType($string, $render = true) {
        return CellType::getDataType($string, $render);
    }
    public static function getSortedRows($rows, $options) {
        return TableSort::sortRows($rows, $options);
    }
    public static function removeColumns($rows, $options) {
        $order = $options['removeColumns'];

        return TableSort::removeColumns($rows, $order);
    }
    public static function modifyCell($cell, $properties = []) {
        return ProcessCell::modifyCell($cell, $properties);
    }
}
