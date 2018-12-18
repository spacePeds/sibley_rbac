<?php

use yii\db\Migration;

/**
 * Class m180923_145736_create_table_audit
 */
class m180923_145736_create_table_audit extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%audit}}', [
            'id' => $this->primaryKey(),
            'record_id' => $this->integer()->notNull(),
            'field' =>  $this->string(255)->notNull(),
            'old_value' =>  $this->text()->notNull(),
            'new_value' =>  $this->text()->notNull(),
            'date' =>  $this->dateTime()->notNull(),
            'update_user' =>  $this->integer()->notNull(),
            
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%audit}}');
    }

}
