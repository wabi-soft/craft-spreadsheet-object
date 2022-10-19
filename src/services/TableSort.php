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
        return self::modifyColumns($rows, $order);
    }
    public static function removeRows($rows, $order = null) {
        return self::modifyRows($rows, $order);
    }

    public static function onlyRows($rows, $order = null) {
        return self::modifyRows($rows, $order, 'only');
    }
    public static function onlyColumns($rows, $order = null) {
        return self::modifyColumns($rows, $order, 'only');
    }

    private static function modifyRows($rows, $order, $type = 'remove') {
        $updated = [];
        if(!$order) {
            return $rows;
        }
        $orderArray = StringHelper::split($order);
        foreach ($rows as $key => $row) {
            $checkKey = intval($key) + 1;
            $shouldModify = $type === 'remove' ? !in_array($checkKey, $orderArray) : in_array($checkKey, $orderArray);
            if($shouldModify) {
                $updated[] = $row;
            }
        }
        if(count($updated) == 0) {
            return $rows;
        }
        return $updated;
    }

    private static function modifyColumns($rows, $order, $type = 'remove') {
        $updated = [];
        if(!$order) {
            return $rows;
        }
        $orderArray = StringHelper::split($order);
        foreach ($rows as $row) {
            $updatedRow = null;
            foreach ($row as $colKey => $column) {
                $checkKey = intval($colKey) + 1;
                $shouldModify = $type === 'remove' ? !in_array($checkKey, $orderArray) : in_array($checkKey, $orderArray);
                if($shouldModify) {
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
