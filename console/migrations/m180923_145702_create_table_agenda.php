<?php

use yii\db\Migration;

/**
 * Class m180923_145702_create_table_agenda
 */
class m180923_145702_create_table_agenda extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%agenda}}', [
            'id' => $this->primaryKey(),
            'type' =>  $this->string(100)->notNull(),
            'date' =>  $this->dateTime()->notNull(),
            'body' =>  $this->text()->notNull(),
            'slug' =>  $this->text()->notNull(),
            'create_dt' =>  $this->dateTime()->notNull(),
            
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%agenda}}');
    }

    
}
