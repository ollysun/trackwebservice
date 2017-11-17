<?php

use Phinx\Migration\AbstractMigration;

class ApiToken extends AbstractMigration
{
    public function up()
    {
        if (!$this->hasTable('company_access')) {
            $install_sql = file_get_contents(dirname(__FILE__) . '/../data/la_verita/api_token.sql');
            $this->execute($install_sql);
        }
    }
}
