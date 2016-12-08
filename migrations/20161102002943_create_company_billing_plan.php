<?php

use Phinx\Migration\AbstractMigration;

class CreateCompanyBillingPlan extends AbstractMigration
{
    public function up()
    {
        if (!$this->hasTable('company_billing_plan')) {
            $install_sql = file_get_contents(dirname(__FILE__) . '/../data/la_verita/company_billing_plan.sql');
            $this->execute($install_sql);
        }
    }
}
