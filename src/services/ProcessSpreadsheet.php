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
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * @property-read void $object
 */
class ProcessSpreadsheet extends Component
{
    private const SUPPORTED_EXTENSIONS = ['csv', 'xls', 'xlsx'];

    /**
     * @throws InvalidConfigException
     * @throws VolumeException
     */
    public static function getArrayFromAsset(?Asset $file = null, array $options = []): bool|array
    {
        if (!$file) {
            return false;
        }

        $extension = StringHelper::toLowerCase($file->extension);
        if (!in_array($extension, self::SUPPORTED_EXTENSIONS)) {
            Craft::getLogger()->log(
                "{$file->filename} has an unsupported extension: {$file->extension}",
                Logger::LEVEL_ERROR,
                'spreadsheet-object'
            );
            return false;
        }

        $storeInDb = \wabisoft\spreadsheetobject\Plugin::getInstance()->getSettings()->storeInDb;
        $record = StoreSpreadsheet::fetch($file, $options);
        
        if ($record && $storeInDb) {
            return $record;
        }

        $fullPath = $file->getCopyOfFile();
        $table = self::readRows($fullPath, $options);
        
        if ($storeInDb) {
            StoreSpreadsheet::update($file, $table, $options);
        }
        
        return $table;
    }

    /**
     * @throws Exception
     */
    private static function readRows(string $file, array $options = []): array
    {
        $spreadsheet = IOFactory::load($file);
        $worksheet = $spreadsheet->getActiveSheet($options['sheet'] ?? 1);
        
        // Apply transformations
        $worksheet = self::applyTransformations($worksheet, $options);
        
        // Process rows
        $rows = [];
        $columnCount = 0;
        
        foreach ($worksheet->toArray() as $row) {
            if (!array_filter($row)) {
                continue;
            }
            
            $cells = array_map(
                fn($column) => self::cleanCell($column),
                array_filter($row)
            );
            
            if (!empty($cells)) {
                $rows[] = $cells;
                $columnCount = max($columnCount, count($cells));
            }
        }

        return [
            'title' => $worksheet->getTitle(),
            'rows' => $rows,
            'columnCount' => $columnCount,
            'rowCount' => $worksheet->getHighestDataRow(),
        ];
    }

    private static function applyTransformations(Worksheet $worksheet, array $options): Worksheet
    {
        $worksheet = $worksheet->removeAutoFilter();

        if (!empty($options['removeRow'])) {
            $worksheet = self::removeRow($worksheet, $options['removeRow']);
        }

        if (!empty($options['removeColumn'])) {
            $worksheet = self::removeColumn($worksheet, $options['removeColumn']);
        }

        if (!empty($options['removeColumnByIndex'])) {
            $worksheet = self::removeColumnByIndex($worksheet, $options['removeColumnByIndex']);
        }

        return $worksheet;
    }

    private static function removeRow(Worksheet $sheet, string|int $row): Worksheet
    {
        foreach (self::splitMultiple($row) as $rowNumber) {
            try {
                $sheet->removeRow((int)$rowNumber, 1);
            } catch (Exception $e) {
                Craft::getLogger()->log($e, Logger::LEVEL_ERROR, 'spreadsheet-object');
            }
        }
        return $sheet;
    }

    private static function removeColumn(Worksheet $sheet, string|int $column): Worksheet
    {
        foreach (self::splitMultiple($column) as $col) {
            try {
                $sheet->removeColumn($col);
            } catch (Exception $e) {
                Craft::getLogger()->log($e, Logger::LEVEL_ERROR, 'spreadsheet-object');
            }
        }
        return $sheet;
    }

    private static function removeColumnByIndex(Worksheet $sheet, string|int $column): Worksheet
    {
        foreach (self::splitMultiple($column) as $col) {
            try {
                $sheet->removeColumnByIndex((int)$col, 1);
            } catch (Exception $e) {
                Craft::getLogger()->log($e, Logger::LEVEL_ERROR, 'spreadsheet-object');
            }
        }
        return $sheet;
    }

    private static function splitMultiple(string|int $string): array
    {
        $items = StringHelper::explode((string)$string, ',');
        $items = array_unique($items);
        rsort($items);
        return $items;
    }

    /**
     * Splits a range string into start and amount values
     * 
     * @param string $string The range string in format "start,amount"
     * @return array{start: int, amount: int}|false Returns array with start and amount, or false if invalid
     */
    private static function splitRange(string $string): array|false
    {
        $options = explode(',', $string);
        
        if (count($options) !== 2) {
            return false;
        }

        [$start, $amount] = $options;

        if (!is_numeric($start) || !is_numeric($amount)) {
            return false;
        }

        return [
            'start' => (int)$start,
            'amount' => (int)$amount,
        ];
    }

    private static function cleanCell($data): string
    {
        $data = StringHelper::trim($data);
        $data = HtmlPurifier::cleanUtf8($data);
        return preg_replace('/^<style>.*?<\/style>/is', '', $data);
    }
}
