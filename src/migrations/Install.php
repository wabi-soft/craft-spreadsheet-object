<?php

namespace wabisoft\spreadsheetobject\migrations;

use craft\db\Migration;
use Craft;

class Install extends Migration
{
    public string $driver;
    public function safeUp() : bool {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        if ($this->createTables()) {
            $this->addForeignKeys();
            Craft::$app->db->schema->refresh();
        }
        return true;
    }
    public function safeDown() : bool
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        $this->removeTables();
        return true;
    }


    protected function createTables() : bool
    {
        $tablesCreated = false;
        $tableSchema = Craft::$app->db->schema->getTableSchema('{{%wabisoft_tables}}');
        if ($tableSchema === null) {
            $tablesCreated = true;
            $this->createTable(
                '{{%wabisoft_tables}}',
                [
                    'id' => $this->primaryKey(),
                    'title' => $this->string(255)->defaultValue(''),
                    'configuration' => $this->text(),
                    'columnCount' => $this->integer(),
                    'rowCount' => $this->integer(),
                    'assetId' => $this->integer()->notNull(),
                    'sourceData' => $this->mediumText(),
                    'dateCreated' => $this->dateTime()->notNull(),
                    'dateUpdated' => $this->dateTime()->notNull(),
                    'siteId' => $this->integer()->notNull(),
                ]
            );
        }
        return $tablesCreated;
    }
    protected function addForeignKeys()
    {
        $this->addForeignKey(
            $this->db->getForeignKeyName('{{%wabisoft_tables}}', 'assetId'),
            '{{%wabisoft_tables}}',
            'assetId',
            '{{%assets}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }
    protected function removeTables()
    {
        $this->dropTableIfExists('{{%wabisoft_tables}}');
    }

}
