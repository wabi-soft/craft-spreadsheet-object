<?php

namespace wabisoft\spreadsheetobject\services;

use wabisoft\spreadsheetobject\records\TableRecord;
use craft\helpers\DateTimeHelper;

class StoreSpreadsheet
{
    public static function update($asset, $table, $configId = []) {
        $configId = json_encode($configId);
        $data = json_encode($table['rows']);
        $id = $asset->id;
        $record = TableRecord::find()
            ->where(["assetId" => $id, "configuration" => $configId])
            ->one();
        if(!$record) {
            $record = new TableRecord();
        }
        $now = DateTimeHelper::currentTimeStamp();
        $now = DateTimeHelper::toIso8601($now);

        $record->setAttribute('assetId', $id);
        $record->setAttribute('siteId', $asset->siteId);
        $record->setAttribute('title', $table['title']);
        $record->setAttribute('columnCount', $table['columnCount']);
        $record->setAttribute('rowCount', $table['rowCount']);
        $record->setAttribute('configuration', $configId);
        $record->setAttribute('sourceData', $data);
        $record->setAttribute('dateUpdated', $now);
        $record->save();
        return true;
    }

    public static function fetch($asset, $configId = []) {
        $configId = json_encode($configId);
        $id = $asset->id;
        $record = TableRecord::find()
            ->where(["assetId" => $id, "configuration" => $configId])
            ->one();
        if(!$record) {
            return false;
        }

        $recordDate = DateTimeHelper::toDateTime($record->dateUpdated);
        $assetDate = DateTimeHelper::toDateTime($asset->dateModified);

        if($recordDate < $assetDate) {
           return false;
        }

        return [
            "title" => $record->title,
            "columnCount" => $record->columnCount,
            "rowCount" => $record->rowCount,
            "rows"=>json_decode($record->sourceData)
        ];
    }

    public static function deleteByAssetId($id) {
        $records = TableRecord::find()
            ->where(["assetId" => $id])
            ->all();
        if(!$records) {
            return true;
        }
        foreach($records as $record) {
            $record->delete();
        }
        return true;
    }

    public static function removeAll() {
        $records = TableRecord::find()
            ->all();

        foreach ($records as $record) {
            $record->delete();
        }
        return true;
    }
}
