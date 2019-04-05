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
            'slug' => $this->string(255)->notNull(),
            'fb_token' => $this->string(255),
            'fb_link' => $this->string(255),
            'sub_pages' =>  $this->integer()->notNull()->defaultValue(0),
            'last_edit_dt' =>  $this->dateTime()->notNull(),
            'user_id' =>  $this->integer()->notNull()
            
        ], $tableOptions);

        $this->createIndex(
            'idx_slug',
            'page',
            'slug'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            'idx-slug',
            'page'
        );
        
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
