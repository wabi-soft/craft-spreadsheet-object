<?php

namespace wabisoft\spreadsheetobject\console\controllers;

use wabisoft\spreadsheetobject\services\StoreSpreadsheet;
use yii\console\Controller;
use yii\console\ExitCode;

class FlushController extends Controller
{
    public function actionAll () {
        StoreSpreadsheet::removeAll();
        return ExitCode::OK;
    }
}
