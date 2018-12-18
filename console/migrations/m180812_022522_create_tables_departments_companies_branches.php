<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180812_022522_create_tables_departments_companies_branches
 */
class m180812_022522_create_tables_departments_companies_branches extends Migration
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
        $this->createTable('companies', [
            'company_id' => $this->primaryKey(),
            'company_name' => $this->string(100)->notNull(),
            'company_email' => $this->string(100)->notNull(),
            'company_address' => $this->string(255)->notNull(),
            'company_created_dt' => $this->dateTime(),
            'company_status' => "ENUM('active', 'inactive')",
        ], $tableOptions);

        $this->createTable('branches', [
            'branch_id' => $this->primaryKey(),
            'companies_company_id' => Schema::TYPE_INTEGER.' NOT NULL',
            'branch_name' => $this->string(100)->notNull(),
            'branch_address' => $this->string(255)->notNull(),
            'branch_created_date' => $this->dateTime(),
            'branch_status' => "ENUM('active', 'inactive')"
        ], $tableOptions);
        $this->addForeignKey('fk_departments_branches', '{{%branches}}', 'companies_company_id', '{{%companies}}', 'company_id', 'CASCADE', 'CASCADE');

        $this->createTable('departments', [
            'department_id' => $this->primaryKey(),
            'branches_branch_id' => $this->integer()->notNull(),
            'department_name' => $this->string(100)->notNull(),
            'companies_company_id' => Schema::TYPE_INTEGER.' NOT NULL',
            'department_created_date' => $this->dateTime(),
            'department_status' => "ENUM('active', 'inactive')"

        ], $tableOptions);
        $this->addForeignKey('fk_branches_departments', '{{%departments}}', 'branches_branch_id', '{{%branches}}', 'branch_id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_companies_departments', '{{%departments}}', 'companies_company_id', '{{%companies}}', 'company_id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_branches_departments', '{{%departments}}');
        $this->dropForeignKey('fk_companies_departments', '{{%departments}}');
        $this->dropTable('departments');

        $this->dropForeignKey('fk_departments_branches', '{{%branches}}');
        $this->dropTable('branches');

        $this->dropTable('companies');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180812_022522_create_tables_departments_companies_branches cannot be reverted.\n";

        return false;
    }
    */
}
