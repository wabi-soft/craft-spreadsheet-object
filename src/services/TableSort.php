<?php

namespace wabisoft\spreadsheetobject\services;

use craft\helpers\ArrayHelper;

class TableSort
{
    public static function sortRows($rows, $options) {
        var_dump('SORTING');
        $hasHeading = array_key_exists('thead', $options) && $options['thead'];

        var_dump($hasHeading);
        $direction = SORT_ASC;
        $flag = SORT_NUMERIC;
//       if(is_array($options['sort'])) {
//
//       }
        $key = $options['sortColumnIndex'];
        $key = is_numeric($key) ? $key - 1 : $key;
        $heading = [];
        if($hasHeading) {
            $heading[] = $rows[0];
            array_shift($rows);
        }
        ArrayHelper::multisort($rows, $key, $direction, $flag);
        if($hasHeading) {
            $rows = ArrayHelper::merge($heading, $rows);
        }
        return $rows;
    }
}
