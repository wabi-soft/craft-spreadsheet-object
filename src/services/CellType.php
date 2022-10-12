<?php

namespace wabisoft\spreadsheetobject\services;

use craft\helpers\StringHelper;

class CellType
{
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
