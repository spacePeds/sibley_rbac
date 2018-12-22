<?php

use yii\db\Migration;

/**
 * Class m181222_160427_rbac_tables
 * INSTEAD! uae: yii migrate --migrationPath=@yii/rbac/migrations/
 */
class m181222_160427_rbac_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%auth_rule}}', [
            'name' =>  $this->string(64)->notNull(),
            'data' =>  $this->text()->notNull(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),    
            'PRIMARY KEY(name)',        
        ], $tableOptions);

        $this->createTable('{{%auth_item}}', [
            'name' =>  $this->string(64)->notNull(),
            'type' => $this->integer()->notNull(),
            'description' =>  $this->text(),
            'rule_name' =>  $this->string(64),
            'data' =>  $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),    
            'PRIMARY KEY(name)',       
        ], $tableOptions);
        $this->addForeignKey('fk_auth_item_auth_rule', '{{%auth_item}}', 'rule_name', '{{%auth_rule}}', 'name', 'NULL', 'CASCADE');
        
        $this->createTable('{{%auth_item_child}}', [
            'parent' =>  $this->string(64)->notNull(),
            'child' =>  $this->string(64)->notNull(),    
            'PRIMARY KEY(parent, child)',       
        ], $tableOptions);
        $this->addForeignKey('fk_parent', '{{%auth_item_child}}', 'parent', '{{%auth_item}}', 'name', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_child', '{{%auth_item_child}}', 'child', '{{%auth_item}}', 'name', 'CASCADE', 'CASCADE');

        $this->createTable('{{%auth_assignment}}', [
            'item_name' =>  $this->string(64)->notNull(),
            'user_id' =>  $this->string(64)->notNull(),     
            'created_at' => $this->integer(), 
            'PRIMARY KEY(item_name)',
        ], $tableOptions);
        $this->addForeignKey('fk_item_name', '{{%auth_assignment}}', 'item_name', '{{%auth_item}}', 'name', 'CASCADE', 'CASCADE');
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_item_name', '{{%auth_assignment}}');
        $this->dropForeignKey('fk_parent', '{{%auth_item_parent}}');
        $this->dropForeignKey('fk_child', '{{%auth_item_child}}');
        $this->dropForeignKey('fk_auth_item_auth_rule', '{{%auth_item}}');

        $this->dropTable('auth_assignment');
        $this->dropTable('auth_item_child');
        $this->dropTable('auth_item');
        $this->dropTable('auth_rule');
    }

    
}
