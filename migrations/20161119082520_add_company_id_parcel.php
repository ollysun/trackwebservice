<?php

use Phinx\Migration\AbstractMigration;

class AddCompanyIdParcel extends AbstractMigration
{
    public function up()
    {
        return;
        if(!$this->table('company')->hasColumn('company_id')){
            $install_sql = file_get_contents(dirname(__FILE__) . '/../data/la_verita/add_company_id_to_parcel.sql');
            $this->execute($install_sql);
        }

    }
}
