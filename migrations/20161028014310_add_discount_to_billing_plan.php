<?php

use Phinx\Migration\AbstractMigration;

class AddDiscountToBillingPlan extends AbstractMigration
{
    public function up()
    {
        if (!$this->hasTable('audit')) {
            $install_sql = file_get_contents(dirname(__FILE__) . '/../data/la_verita/add_discount_to_billing_plan.sql');
            $this->execute($install_sql);
        }
    }
}
