<?php

use Phinx\Migration\AbstractMigration;

class BillingPlanCloneStoredProcedureUpdate extends AbstractMigration
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
    public function up()
    {
        $stored_procedure_install_sql = file_get_contents(dirname(__FILE__) . '/../data/populate_weight_sp.sql');
        $this->execute($stored_procedure_install_sql);
        $stored_procedure_install_sql = file_get_contents(dirname(__FILE__) . '/../data/populate_onforwarding_sp.sql');
        $this->execute($stored_procedure_install_sql);
    }
}
