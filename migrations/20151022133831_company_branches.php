<?php

use Phinx\Migration\AbstractMigration;

class CompanyBranches extends AbstractMigration
{
    public function up()
    {
        if ($this->hasTable('company') && $this->hasTable('branch')) {
            $install_sql = file_get_contents(dirname(__FILE__) . '/../data/corporate/companies_ecs.sql');
            $this->execute($install_sql);
        }
    }

    public function down()
    {
        $this->dropTable("company_branches");
    }
}
