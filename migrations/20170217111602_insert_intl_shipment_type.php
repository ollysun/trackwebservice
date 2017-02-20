<?php

use Phinx\Migration\AbstractMigration;

class InsertIntlShipmentType extends AbstractMigration
{
    public function up()
    {
        $install_sql = file_get_contents(dirname(__FILE__) . '/../data/la_verita/intl_insert_service_types.sql');
        $this->execute($install_sql);
    }
}
