<?php

use yii\db\Migration;

/**
 * Class m180923_154801_create_table_alert
 */
class m180923_154801_create_table_alert extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%alert}}', [
            'id' => $this->primaryKey(),
            'group' => $this->string(100)->notNull(),
            'type' =>  $this->string(100)->notNull(),
            'title' => $this->string(255)->notNull(),
            'message' =>  $this->text(),
            'start_dt' =>  $this->dateTime()->notNull(),
            'end_dt' =>  $this->dateTime()->notNull(),
            'created_dt' =>  $this->dateTime()->notNull(),
            'created_by' =>  $this->integer()->notNull(),
            
        ], $tableOptions);

        // creates index for column `group`
        $this->createIndex(
            'idx-alert-group',
            'alert',
            'group'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `group`
        $this->dropIndex(
            'idx-alert-group',
            'alert'
        );
        $this->dropTable('{{%alert}}');
    }
    
}
