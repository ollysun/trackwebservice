<?php

use Phinx\Migration\AbstractMigration;
/**
 * Class BillingPlanModifications
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class BillingPlanModifications extends AbstractMigration
{

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function change()
    {
        $stored_procedure_install_sql = file_get_contents(dirname(__FILE__) . '/../data/onforwardingplan_sp.sql');
        $this->execute($stored_procedure_install_sql);
        $this->execute('CALL OnforwardingChargePlanBuild()');
    }
}
