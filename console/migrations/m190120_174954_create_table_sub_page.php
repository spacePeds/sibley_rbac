<?php

use yii\db\Migration;

/**
 * Class m190120_174954_create_table_sub_page
 */
class m190120_174954_create_table_sub_page extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%sub_page}}', [
            'id' => $this->primaryKey(),
            'page_id' => $this->integer()->notNull(),
            'title' => $this->string(255)->notNull(),
            'body' =>  $this->text(),
            'type' => $this->string(25)->notNull(),
            'path' =>  $this->string(255),
            'sort_order' => $this->integer()->defaultValue(0),
            'last_edit' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'created_by' => $this->integer()->notNull(),
            
        ], $tableOptions);

        $this->addForeignKey('fk_sub_page', '{{%sub_page}}', 'page_id', '{{%page}}', 'id', 'CASCADE', 'CASCADE');

        // creates index for column `header_image`
        //$this->createIndex(
        //    'idx-sub-page-header-image',
        //    'sub_page',
        //    'header_image'
        //);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_sub_page', '{{%sub_page}}');
        $this->dropTable('{{%sub_page}}');
    }
}
