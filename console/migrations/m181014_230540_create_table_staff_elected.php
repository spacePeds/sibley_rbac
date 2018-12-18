<?php

use yii\db\Migration;

/**
 * Class m181014_230540_create_table_staff_elected
 */
class m181014_230540_create_table_staff_elected extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%staff_elected}}', [
            'id' => $this->primaryKey(),
            'staff_id' => $this->integer()->notNull(),
            'term_start' =>  $this->dateTime()->notNull(),
            'term_end' =>  $this->dateTime()->notNull(),
        ], $tableOptions);
        $this->addForeignKey('fk_staff_elected', '{{%staff_elected}}', 'staff_id', '{{%staff}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_staff_elected', '{{%staff_elected}}');
        $this->dropTable('staff_elected');
    }
}
