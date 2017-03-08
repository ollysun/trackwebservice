<?php

use Phinx\Migration\AbstractMigration;

class CompanyApiEmail extends AbstractMigration
{
    public function up()
    {
        return;
        $install_sql = file_get_contents(dirname(__FILE__) . '/../data/la_verita/insert_api_access_email_template.sql');
        $this->execute($install_sql);
    }
}
