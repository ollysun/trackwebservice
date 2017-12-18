<?php

use Phinx\Migration\AbstractMigration;

class AddCompanyExtraNote extends AbstractMigration
{
    public function up()
    {
        $install_sql = file_get_contents(dirname(__FILE__) . '/../data/la_verita/add_extra_note_to_company.sql');
        $this->execute($install_sql);
    }
}
