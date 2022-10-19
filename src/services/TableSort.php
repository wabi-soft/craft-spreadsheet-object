<?php

namespace wabisoft\spreadsheetobject\services;

use craft\helpers\ArrayHelper;
use craft\helpers\StringHelper;

class TableSort
{
    public static function sortRows($rows, $options) {
        $hasHeading = array_key_exists('thead', $options) && $options['thead'];
        $direction = SORT_ASC;
        $flag = SORT_REGULAR;

        if(is_array($options['sortColumnIndex'])) {
            $nested = $options['sortColumnIndex'];
            $key = array_key_exists('key', $nested) ? $nested['key'] : 0;
            $direction = array_key_exists('direction', $nested) ? $nested['direction'] : $direction;
            $flag = array_key_exists('sortFlag', $nested) ? $nested['sortFlag'] : $flag;
        } else {
            $key = $options['sortColumnIndex'];
        }
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

    public static function removeColumns($rows, $order = null) {
        $updated = [];
        if(!$order) {
            return $rows;
        }
        $orderArray = StringHelper::split($order, ',');
        foreach ($rows as $key => $row) {
            $updatedRow = null;
            foreach ($row as $colKey => $column) {
                if(!in_array(intval($colKey), $orderArray)) {
                    $updatedRow[] = $column;
                }
            }
            if($updatedRow) {
                $updated[] = $updatedRow;
            }
        }
        if(count($updated) == 0) {
            return $rows;
        }
        return $updated;
    }
}
