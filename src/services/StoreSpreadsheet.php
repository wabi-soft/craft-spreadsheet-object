<?php

namespace wabisoft\spreadsheetobject\services;

use wabisoft\spreadsheetobject\records\TableRecord;
use craft\helpers\DateTimeHelper;
use craft\elements\Asset;
use yii\db\ActiveRecord;

/**
 * Service class for managing spreadsheet data storage and retrieval
 */
class StoreSpreadsheet
{
    /**
     * Updates or creates a spreadsheet record in the database
     *
     * @param Asset $asset The asset containing the spreadsheet
     * @param array $table The table data to store
     * @param array $configId Configuration options
     * @return bool Whether the operation was successful
     */
    public static function update(Asset $asset, array $table, array $configId = []): bool
    {
        try {
            // Sort config for consistent JSON encoding
            ksort($configId);
            $record = self::findOrCreateRecord($asset->id, $configId);
            $now = DateTimeHelper::toIso8601(DateTimeHelper::currentTimeStamp());

            $record->assetId = $asset->id;
            $record->siteId = $asset->siteId;
            $record->title = $table['title'];
            $record->columnCount = $table['columnCount'];
            $record->rowCount = $table['rowCount'];
            $record->configuration = json_encode($configId);
            $record->sourceData = json_encode($table['rows']);
            $record->dateUpdated = $now;

            // Log validation errors if save fails
            if (!$record->save()) {
                \Craft::error(
                    "Failed to save spreadsheet record: " . json_encode($record->getErrors()),
                    'spreadsheet-object'
                );
                return false;
            }

            return true;
        } catch (\Exception $e) {
            \Craft::error(
                "Failed to update spreadsheet record: {$e->getMessage()}",
                'spreadsheet-object'
            );
            return false;
        }
    }

    /**
     * Fetches stored spreadsheet data for an asset
     *
     * @param Asset $asset The asset to fetch data for
     * @param array $configId Configuration options
     * @return array|false The stored data or false if not found/outdated
     */
    public static function fetch(Asset $asset, array $configId = []): array|false
    {
        try {
            $record = self::findRecord($asset->id, $configId);
            
            if (!$record || !self::isRecordCurrent($record, $asset)) {
                return false;
            }

            return [
                'title' => $record->title,
                'columnCount' => $record->columnCount,
                'rowCount' => $record->rowCount,
                'rows' => json_decode($record->sourceData)
            ];
        } catch (\Exception $e) {
            \Craft::error(
                "Failed to fetch spreadsheet record: {$e->getMessage()}",
                'spreadsheet-object'
            );
            return false;
        }
    }

    /**
     * Deletes all records for a specific asset
     *
     * @param int $assetId The ID of the asset
     * @return bool Whether the operation was successful
     */
    public static function deleteByAssetId(int $assetId): bool
    {
        try {
            return TableRecord::deleteAll(['assetId' => $assetId]) > 0;
        } catch (\Exception $e) {
            \Craft::error(
                "Failed to delete spreadsheet records: {$e->getMessage()}",
                'spreadsheet-object'
            );
            return false;
        }
    }

    /**
     * Removes all stored spreadsheet records
     *
     * @return bool Whether the operation was successful
     */
    public static function removeAll(): bool
    {
        try {
            return TableRecord::deleteAll() > 0;
        } catch (\Exception $e) {
            \Craft::error(
                "Failed to remove all spreadsheet records: {$e->getMessage()}",
                'spreadsheet-object'
            );
            return false;
        }
    }

    /**
     * Finds or creates a record for the given asset and configuration
     *
     * @param int $assetId The asset ID
     * @param array $configId The configuration options
     * @return TableRecord The found or new record
     */
    private static function findOrCreateRecord(int $assetId, array $configId): TableRecord
    {
        // Sort config for consistent JSON encoding
        ksort($configId);
        $record = self::findRecord($assetId, $configId);
        return $record ?: new TableRecord();
    }

    /**
     * Finds a record for the given asset and configuration
     *
     * @param int $assetId The asset ID
     * @param array $configId The configuration options
     * @return TableRecord|null The found record or null
     */
    private static function findRecord(int $assetId, array $configId): ?TableRecord
    {
        // Sort config for consistent JSON encoding
        ksort($configId);
        return TableRecord::find()
            ->where([
                'assetId' => $assetId,
                'configuration' => json_encode($configId)
            ])
            ->one();
    }

    /**
     * Checks if a record is current compared to its asset
     *
     * @param TableRecord $record The record to check
     * @param Asset $asset The asset to compare against
     * @return bool Whether the record is current
     */
    private static function isRecordCurrent(TableRecord $record, Asset $asset): bool
    {
        $recordDate = DateTimeHelper::toDateTime($record->dateUpdated);
        $assetDate = DateTimeHelper::toDateTime($asset->dateModified);
        
        return $recordDate >= $assetDate;
    }
}
