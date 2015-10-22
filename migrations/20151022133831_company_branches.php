<?php

use Phinx\Migration\AbstractMigration;

class CompanyBranches extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        if ($this->hasTable('company') && $this->hasTable('branch')) {
            $install_sql = file_get_contents(dirname(__FILE__) . '/../data/corporate/companies_ecs.sql');
            $this->execute($install_sql);
        }
    }
}
