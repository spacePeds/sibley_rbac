<?php

use yii\db\Migration;

/**
 * Class m180812_184734_create_table_category
 */
class m180812_184734_create_table_category extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
            'category' =>  $this->string(100)->notNull(),
            'description' =>  $this->text(),
            'created_dt' => $this->dateTime(),
            
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('category');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180812_184734_create_table_category cannot be reverted.\n";

        return false;
    }
    */
}
