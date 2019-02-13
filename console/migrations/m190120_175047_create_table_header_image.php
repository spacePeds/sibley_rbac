<?php

use yii\db\Migration;

/**
 * Class m190120_175047_create_table_header_image
 */
class m190120_175047_create_table_header_image extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%header_image}}', [
            'id' => $this->primaryKey(),
            'image_path' =>  $this->string(255),
            'image_idx' =>  $this->string(255),         //name_pageId_subPageId
            'display' => $this->string(255)->notNull(),
            'brightness' => $this->decimal(3,1),  //alpha
            'offset' =>  $this->integer(),
            'height' => $this->integer()->notNull(),
            'position' =>  $this->string(255),
            'sequence' =>  $this->integer()->notNull(),
            'last_edit' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'created_by' => $this->integer()->notNull(),
            
        ], $tableOptions);

        // creates index for column `header_image`
        $this->createIndex(
            'idx-header-image',
            'header_image',
            'image_idx'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            'idx-header-image',
            'header_image'
        );
        $this->dropTable('{{%header_image}}');
    }
}
