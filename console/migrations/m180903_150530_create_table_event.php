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
            'location' =>  $this->string(255),
            'group' =>  "ENUM('city', 'chamber', 'rec', 'hol') NOT NULL",
            'start_dt' =>  $this->dateTime()->notNull(),
            'end_dt' =>  $this->dateTime()->notNull(),
            'all_day' =>  $this->integer()->notNull(),
            'repeat_interval' =>  $this->integer()->notNull(),
            'last_edit_dt' =>  $this->dateTime()->notNull(),
            'user_id' =>  $this->integer()->notNull()
            
        ], $tableOptions);
        $this->addCommentOnColumn('{{%event}}','repeat_interval','0-no, 1-weekly, 2=monthly, 3-annualy');

        $this->addForeignKey('fk_user_event', '{{%event}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%event}}');
    }
}
