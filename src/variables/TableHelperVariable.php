<?php

namespace wabisoft\spreadsheetobject\variables;

use craft\helpers\ArrayHelper;
use craft\helpers\StringHelper;

class TableHelperVariable
{
    const DEFAULT_TABLE_OPTIONS = [
        'thead' => false,
        'tfoot' => false,
        'caption' => false,
        'autoCellDataAttribute' => true,
        'applyNextRowTypeToHeading' => true,
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
      return ArrayHelper::merge($config, self::DEFAULT_TABLE_OPTIONS);
    }
    public static function getDataType($string, $render = true) {
        if(!$render) {
            return false;
        }
        $string = StringHelper::trim($string);
        if($string === '') {
            return 'empty';
        }

        $removeNumberChars = StringHelper::replace($string, ',', '');
        if($removeNumberChars === '') {
            return 'empty';
        }
        if(is_numeric($removeNumberChars)) {
            if(StringHelper::contains($string, ',', false)) {
                return 'number--comma';
            }
            if(StringHelper::contains($string, '.', false)) {
                return 'number--decimal';
            }
            return 'number';
        }
        if(StringHelper::isAlpha($string)) {
            return 'text';
        }
        return 'text';
    }
}
