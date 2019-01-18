<?php

use yii\db\Migration;

/**
 * Class m180903_211845_create_table_document
 */
class m180903_211845_create_table_document extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%document}}', [
            'id' => $this->primaryKey(),
            'path' =>  $this->string(1024)->notNull(),
            'type' =>  $this->string(255)->notNull(),
            'size' =>  $this->integer()->notNull(),
            'name' =>  $this->string(255)->notNull(),
            'table_record' => $this->string(255)->notNull(),
            'sort_order' =>  $this->integer()->notNull()
            
        ], $tableOptions);
        
        // creates index for column `author_id`
        $this->createIndex(
            'idx-document-table-record',
            'document',
            'table_record'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `table_record`
        $this->dropIndex(
            'idx-document-table-record',
            'document'
        );
        
        $this->dropTable('{{%document}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180903_211845_create_table_document cannot be reverted.\n";

        return false;
    }
    */
}
