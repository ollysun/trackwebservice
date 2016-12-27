<?php

use Phinx\Migration\AbstractMigration;

class BusinessManager extends AbstractMigration
{
    public function up()
    {
        if (!$this->hasTable('business_manager')) {
            $install_sql = file_get_contents(dirname(__FILE__) . '/../data/la_verita/business_manager.sql');
            $this->execute($install_sql);
        }
    }
}
