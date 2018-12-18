<?php

use yii\db\Migration;

/**
 * Class m180903_150530_create_table_event
 */
class m180903_150530_create_table_event extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%event}}', [
            'id' => $this->primaryKey(),
            'subject' =>  $this->string(200)->notNull(),
            'description' =>  $this->text(),
            'group' =>  "ENUM('city', 'chamber', 'rec') NOT NULL",
            'start_dt' =>  $this->dateTime()->notNull(),
            'end_dt' =>  $this->dateTime()->notNull(),
            'last_edit_dt' =>  $this->dateTime()->notNull(),
            'user_id' =>  $this->integer()->notNull()
            
        ], $tableOptions);
        $this->addForeignKey('fk_user_event', '{{%event}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%event}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180903_150530_create_table_event cannot be reverted.\n";

        return false;
    }
    */
}
