<?php

use yii\db\Migration;

/**
 * Class m180812_185052_create_table_business_category
 */
class m180812_185052_create_table_business_category extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%business_category}}', [
            'id' => $this->primaryKey(),
            'business_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->addForeignKey('fk_business_category', '{{%business_category}}', 'business_id', '{{%business}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_category_business', '{{%business_category}}', 'category_id', '{{%category}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_category_business', '{{%business_category}}');
        $this->dropForeignKey('fk_business_category', '{{%business_category}}');
        $this->dropTable('business_category');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180812_185052_create_table_business_category cannot be reverted.\n";

        return false;
    }
    */
}
