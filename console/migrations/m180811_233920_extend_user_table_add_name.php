<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180811_233920_extend_user_table_add_name
 */
class m180811_233920_extend_user_table_add_name extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }
        $this->addColumn('{{%user}}','first_name',$this->string(100));
        $this->addColumn('{{%user}}','last_name',$this->string(100));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}','first_name');
        $this->dropColumn('{{%user}}','last_name');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180811_233920_extend_user_table_add_name cannot be reverted.\n";

        return false;
    }
    */
}
