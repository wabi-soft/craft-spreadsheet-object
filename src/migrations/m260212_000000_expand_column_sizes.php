<?php

namespace wabisoft\spreadsheetobject\migrations;

use craft\db\Migration;

class m260212_000000_expand_column_sizes extends Migration
{
    public function safeUp(): bool
    {
        if ($this->db->tableExists('{{%wabisoft_tables}}')) {
            $this->alterColumn('{{%wabisoft_tables}}', 'sourceData', $this->mediumText());
            $this->alterColumn('{{%wabisoft_tables}}', 'configuration', $this->text());
        }

        return true;
    }

    public function safeDown(): bool
    {
        if ($this->db->tableExists('{{%wabisoft_tables}}')) {
            $this->alterColumn('{{%wabisoft_tables}}', 'sourceData', $this->text());
            $this->alterColumn('{{%wabisoft_tables}}', 'configuration', $this->string(255)->defaultValue(''));
        }

        return true;
    }
}
