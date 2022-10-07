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
    public static function getArrayFromAsset(Asset $file = null, array $options = []) {
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
    private static function readRows($file, array $options = []) {

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
            "columns" => $columnCount
        ];
    }

    private static function removeRow($sheet, $row) {
        try {
            return $sheet->removeRow($row);
        } catch(exception $e) {
            Craft::getLogger()->log($e, Logger::LEVEL_ERROR, 'spreadsheet-object');
        }
        return $sheet;
    }
    private static function removeColumn($sheet, $column) {
        try {
            return $sheet->removeColumn($column);
        } catch(exception $e) {
            Craft::getLogger()->log($e, Logger::LEVEL_ERROR, 'spreadsheet-object');
        }
        return $sheet;
    }
    private static function removeColumnByIndex($sheet, $column) {
        try {
            return $sheet->removeColumnByIndex($column);
        } catch(exception $e) {
            Craft::getLogger()->log($e, Logger::LEVEL_ERROR, 'spreadsheet-object');
        }
        return $sheet;
    }

    private static function cleanCell($data) {
        $data = StringHelper::trim($data);
        $data = HtmlPurifier::cleanUtf8($data);
        return preg_replace('/^<style>.*?<\\/style>/is', '', $data);
    }
}
