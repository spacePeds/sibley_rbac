<?php

use yii\db\Migration;

/**
 * Class m181222_005050_image_asset
 */
class m181222_005050_image_asset extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%image_asset}}', [
            'id' => $this->primaryKey(),
            'path' =>  $this->string(1024)->notNull(),
            'type' =>  $this->string(255)->notNull(),
            'size' => $this->integer()->notNull(),
            'name' =>  $this->string(255)->notNull(),
            'created_dt' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('image_asset');
    }

}