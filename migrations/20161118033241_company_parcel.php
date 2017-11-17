<?php

use Phinx\Migration\AbstractMigration;

class CompanyParcel extends AbstractMigration
{
    public function up()
    {
        if (!$this->hasTable('company_parcel')) {
            $install_sql = file_get_contents(dirname(__FILE__) . '/../data/la_verita/company_parcel.sql');
            $this->execute($install_sql);
        }
    }
}
