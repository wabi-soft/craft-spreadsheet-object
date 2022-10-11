<?php

namespace wabisoft\spreadsheetobject\models;

use craft\base\Model;

class Settings extends Model
{
    public bool $storeInDb = true;
    public array $tableOptions = [];
}
