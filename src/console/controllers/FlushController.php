<?php

namespace wabisoft\spreadsheetobject\console\controllers;

use wabisoft\spreadsheetobject\records\TableRecord;
use wabisoft\spreadsheetobject\services\StoreSpreadsheet;
use yii\console\Controller;
use yii\console\ExitCode;

class FlushController extends Controller
{
    public function actionAll(): int
    {
        $count = TableRecord::find()->count();

        if ($count === 0) {
            $this->stdout("No cached spreadsheet records found.\n");
            return ExitCode::OK;
        }

        StoreSpreadsheet::removeAll();
        $this->stdout("Flushed {$count} cached spreadsheet record(s).\n");

        return ExitCode::OK;
    }
}
