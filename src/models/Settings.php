<?php

namespace wabisoft\spreadsheetobject\models;

use craft\base\Model;

class Settings extends Model
{
    public bool $storeInDb = true;
    public bool | string $cellModifierTemplates = false;
    public string $defaultTemplateName = 'default';
    public string $rowTemplates = 'row/';
    public string $columnTemplates = 'column/';
    public array $tableOptions = [];
}
