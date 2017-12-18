<?php

use Phinx\Migration\AbstractMigration;

class BusinessZone extends AbstractMigration
{
    public function up()
    {
        if (!$this->hasTable('business_zone')) {
            $install_sql = file_get_contents(dirname(__FILE__) . '/../data/la_verita/business_zone.sql');
            $this->execute($install_sql);
        }
    }
}
