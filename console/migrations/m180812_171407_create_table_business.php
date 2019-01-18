<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180812_171407_create_table_business
 */
class m180812_171407_create_table_business extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%business}}', [
            'id' => Schema::TYPE_PK,
            'name' =>  $this->string(100)->notNull(),
            'address1' =>  $this->string(100)->notNull(),
            'address2' =>  $this->string(100),
            'city' =>  $this->string(100)->notNull(),
            'state' =>  $this->string(2)->notNull(),
            'zip' =>  $this->string(10)->notNull(),
            'url' =>  $this->string(255),
            'note' => $this->text(),
            'member' => "ENUM('1', '0')",
            'image' =>  $this->string(100),
            'created_dt' => $this->dateTime(),
            
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%business}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180812_171407_create_table_business cannot be reverted.\n";

        return false;
    }
    */
}
