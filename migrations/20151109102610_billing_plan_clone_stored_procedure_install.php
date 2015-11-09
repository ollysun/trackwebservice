<?php

use Phinx\Migration\AbstractMigration;



/**
 * Class BillingPlanCloneStoredProcedureInstall
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class BillingPlanCloneStoredProcedureInstall extends AbstractMigration
{

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function change()
    {
        $stored_procedure_install_sql = file_get_contents(dirname(__FILE__) . '/../data/populate_plan_sp.sql');
        $this->execute($stored_procedure_install_sql);
    }
}
