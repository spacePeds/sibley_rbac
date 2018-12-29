<?php

use yii\db\Migration;

/**
 * Class m181226_174441_create_table_link
 */
class m181226_174441_create_table_link extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%link}}', [
            'id' => $this->primaryKey(),
            'type' =>  $this->string(10)->notNull(),
            'group' => $this->string(255)->notNull(),
            'src_table' =>  $this->string(255),
            'src_id' => $this->integer(),
            'name' =>  $this->string(255)->notNull(),
            'label' =>  $this->string(255)->notNull(),
            'description' =>  $this->text(),
            'last_edit' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'created_by' => $this->integer()->notNull(),
            
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('link');
    }
}
