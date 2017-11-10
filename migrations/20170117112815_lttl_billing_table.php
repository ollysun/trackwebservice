<?php

use Phinx\Migration\AbstractMigration;

class LttlBillingTable extends AbstractMigration
{
    public function up()
    {
        if (!$this->hasTable('lttl_billing')) {
            $install_sql = file_get_contents(dirname(__FILE__) . '/../data/la_verita/lttl_billing_table.sql');
            $this->execute($install_sql);
        }
    }
}
