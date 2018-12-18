<?php

use yii\db\Migration;

/**
 * Class m180812_182936_create_table_contact_method
 */
class m180812_182936_create_table_contact_method extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%contact_method}}', [
            'id' => $this->primaryKey(),
            'business_id' => $this->integer()->notNull(),
            'method' => "ENUM('1', '0') NOT NULL",
            'contact' =>  $this->string(100)->notNull(),
            'description' =>  $this->string(255),
            'created_dt' => $this->dateTime(),
            
        ], $tableOptions);
        $this->addForeignKey('fk_business_contact', '{{%contact_method}}', 'business_id', '{{%business}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_business_contact', '{{%contact_method}}');
        $this->dropTable('contact_method');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180812_182936_create_table_contact_method cannot be reverted.\n";

        return false;
    }
    */
}
