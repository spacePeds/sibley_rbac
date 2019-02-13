<?php

use yii\db\Migration;

/**
 * Class m190120_175111_create_table_sub_page_document
 */
class m190120_175111_create_table_sub_page_document extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%sub_page_document}}', [
            'id' => $this->primaryKey(),
            'sub_page_id' => $this->integer()->notNull(),
            'document_id' => $this->integer()->notNull(),
            'allignment' => $this->string(25)->notNull()
        ], $tableOptions);
        $this->addForeignKey('fk_sub_page_id', '{{%sub_page_document}}', 'sub_page_id', '{{%sub_page}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_document_id', '{{%sub_page_document}}', 'document_id', '{{%document}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_document_id', '{{%sub_page_document}}');
        $this->dropForeignKey('fk_sub_page_id', '{{%sub_page_document}}');
        $this->dropTable('{{%sub_page_document}');
    }
}
