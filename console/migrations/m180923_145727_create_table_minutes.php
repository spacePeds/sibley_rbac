<?php

use yii\db\Migration;

/**
 * Class m180923_145727_create_table_minutes
 */
class m180923_145727_create_table_minutes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%agenda_minutes}}', [
            'id' => $this->primaryKey(),
            'agenda_id' => $this->integer()->notNull(),
            'attend' =>  $this->string(255)->notNull(),
            'absent' =>  $this->string(255),
            'body' =>  $this->text()->notNull(),
            'create_dt' =>  $this->dateTime()->notNull(),
        ], $tableOptions);
        $this->addForeignKey('fk_agenda_minutes', '{{%agenda_minutes}}', 'agenda_id', '{{%agenda}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_agenda_minutes', '{{%agenda_minutes}}');
        $this->dropTable('agenda_minutes');
    }

    
}
