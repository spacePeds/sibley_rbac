<?php

use yii\db\Migration;

/**
 * Class m180909_232240_create_table_page_category
 */
class m180909_232240_create_table_page_category extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%page_category}}', [
            'id' => $this->primaryKey(),
            'page_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->addForeignKey('fk_page_category', '{{%page_category}}', 'page_id', '{{%page}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_category_page', '{{%page_category}}', 'category_id', '{{%category}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_category_page', '{{%page_category}}');
        $this->dropForeignKey('fk_page_category', '{{%page_category}}');
        $this->dropTable('page_category');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180909_232240_create_table_page_category cannot be reverted.\n";

        return false;
    }
    */
}
