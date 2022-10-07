<?php

namespace wabisoft\spreadsheetobject\services;

use Craft;
use craft\errors\VolumeException;
use craft\helpers\HtmlPurifier;
use PhpOffice\PhpSpreadsheet\Exception;
use yii\base\Component;
use craft\elements\Asset;
use yii\base\InvalidConfigException;
use craft\helpers\StringHelper;
use yii\log\Logger;
use PhpOffice\PhpSpreadsheet\IOFactory;
use craft\helpers\ArrayHelper;


/**
 *
 * @property-read void $object
 */
class ProcessSpreadsheet extends Component
{
    const SUPPORTED_EXTENSIONS = [
      'csv',
      'xls',
      'xlsx'
    ];

    /**
     * @throws InvalidConfigException
     * @throws VolumeException
     */
    public static function getArrayFromAsset(Asset $file = null, array $options = []): bool|array
    {
        if(!$file) {
            return false;
        }
        if(! in_array(StringHelper::toLowerCase($file->extension), self::SUPPORTED_EXTENSIONS) ) {
            Craft::getLogger()->log($file->filename . ' has an unsupported extension of: ' . $file->extension, Logger::LEVEL_ERROR, 'spreadsheet-object');
            return false;
        }

        $storeInDb = \wabisoft\spreadsheetobject\Plugin::getInstance()->getSettings()->storeInDb;
        $record = StoreSpreadsheet::fetch($file, $options);
        if($record && $storeInDb) {
            return $record;
        }
        $fullPath = $file->getCopyOfFile();
        $table = self::readRows($fullPath, $options);
        if($storeInDb) {
            StoreSpreadsheet::update($file, $table, $options);
        }
        return $table;
    }

    /**
     * @throws Exception
     */
    private static function readRows($file, array $options = []): array
    {

        $sheet = array_key_exists('sheet', $options) ? $options['sheet'] : 1;
        $removeRow =  array_key_exists('removeRow', $options) ? $options['removeRow'] : false;
        $removeColumn =  array_key_exists('removeColumn', $options) ? $options['removeColumn'] : false;
        $removeColumnByIndex = array_key_exists('removeColumnByIndex', $options) ? $options['removeColumnByIndex'] : false;

        $spreadsheet = IOFactory::load($file);
        $worksheet = $spreadsheet->getActiveSheet($sheet);
        if($removeRow) {
            $worksheet = self::removeRow($worksheet, $removeRow);
        }
        if($removeColumn) {
            $worksheet = self::removeColumn($worksheet, $removeColumn);
        }
        if($removeColumnByIndex) {
            $worksheet = self::removeColumnByIndex($worksheet, $removeColumnByIndex);
        }

        $worksheet = $worksheet->removeAutoFilter();
        $rowCount = $worksheet->getHighestDataRow();

        $title = $worksheet->getTitle();
        $rows = [];
        $columnCount = 0;
        foreach($worksheet->toArray() as $row ) {
            $cells = [];
            $columnNumber = 0;
            foreach($row as $key => $column) {
                if($column) {
                    $columnNumber = $key++;
                    $cells[] = self::cleanCell($column);
                }
            }
            $rows[] = $cells;
            $columnCount = max($columnCount, $columnNumber);
        }
        return [
            "title" => $title,
            "rows" => $rows,
            "columnCount" => $columnCount,
            "rowCount" => $rowCount,
        ];
    }

    private static function removeRow($sheet, $row) {
        $amount = 1;
        $rows = self::splitMultiple($row);
        foreach($rows as $row) {
            try {
                $sheet->removeRow(intval($row), $amount);
            } catch(exception $e) {
                Craft::getLogger()->log($e, Logger::LEVEL_ERROR, 'spreadsheet-object');
            }
        }
        return $sheet;
    }

    private static function removeColumn($sheet, $column) {
        $columns = self::splitMultiple($column);
        foreach($columns as $col) {
            try {
                $sheet->removeColumn($col);
            } catch(exception $e) {
                Craft::getLogger()->log($e, Logger::LEVEL_ERROR, 'spreadsheet-object');
            }
        }
        return $sheet;
    }
    private static function removeColumnByIndex($sheet, $column) {
        $amount = 1;
        $columns = self::splitMultiple($column);
        foreach($columns as $col) {
            try {
                $sheet->removeColumnByIndex($col, $amount);
            } catch (exception $e) {
                Craft::getLogger()->log($e, Logger::LEVEL_ERROR, 'spreadsheet-object');
            }
        }
        return $sheet;
    }

    private static function splitMultiple($string) {
        $columnsArray = StringHelper::explode($string, ',');
        $columnsArray = array_unique($columnsArray);
        arsort($columnsArray);
        return $columnsArray;
    }

    private static function splitRange($string) {
        $options = explode(',', $string);
        $first = $options[0];
        $amount = $options[1];
        if(!is_numeric($first) || !is_numeric($amount)) {
            return false;
        }
        return [
            'start' => intval($first),
            'amount' => intval($amount),
        ];
    }

    private static function cleanCell($data) {
        $data = StringHelper::trim($data);
        $data = HtmlPurifier::cleanUtf8($data);
        return preg_replace('/^<style>.*?<\\/style>/is', '', $data);
    }
}
