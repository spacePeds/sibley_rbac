<?php

use yii\db\Migration;

/**
 * Class m180909_231434_create_table_page
 */
class m180909_231434_create_table_page extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%page}}', [
            'id' => $this->primaryKey(),
            'route' =>  $this->string(255)->notNull(),
            'title' =>  $this->string(255)->notNull(),
            'body' =>  $this->text()->notNull(),
            'last_edit_dt' =>  $this->dateTime()->notNull(),
            'user_id' =>  $this->integer()->notNull()
            
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%page}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180909_231434_create_table_page cannot be reverted.\n";

        return false;
    }
    */
}
