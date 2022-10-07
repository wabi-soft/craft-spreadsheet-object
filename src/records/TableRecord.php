<?php

namespace wabisoft\spreadsheetobject\records;
use craft\db\ActiveRecord;


class TableRecord extends ActiveRecord
{
    public static function tableName() : string {
        return '{{%wabisoft_tables}}';
    }
}
