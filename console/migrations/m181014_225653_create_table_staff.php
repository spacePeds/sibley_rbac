<?php

use yii\db\Migration;

/**
 * Class m181014_225653_create_table_staff
 */
class m181014_225653_create_table_staff extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%staff}}', [
            'id' => $this->primaryKey(),
            'first_name' => $this->string(50)->notNull(),
            'last_name' =>  $this->string(50)->notNull(),
            'position' =>  $this->string(100)->notNull(),
            'elected' => "ENUM('1', '0')",
            'email' =>  $this->string(255),
            'phone' =>  $this->string(10),
            'image_asset' => $this->integer(),
            
        ], $tableOptions);

        // creates index for column `group`
        $this->createIndex(
            'idx-staff-elected',
            'staff',
            'elected'
        );

        $this->createIndex(
            'idx-staff-image_asset',
            'staff',
            'image_asset'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `elected`
        $this->dropIndex(
            'idx-staff-elected',
            'staff'
        );
        $this->dropIndex(
            'idx-staff-image_asset',
            'staff'
        );
        $this->dropTable('{{%staff}}');
    }

    
}
