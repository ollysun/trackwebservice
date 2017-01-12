<?php

use Phinx\Migration\AbstractMigration;

class ReturnedShipmentTeller extends AbstractMigration
{
    public function up()
    {
        if (!$this->hasTable('returned_shipment_teller')) {
            $install_sql = file_get_contents(dirname(__FILE__) . '/../data/la_verita/returned_shipment_teller.sql');
            $this->execute($install_sql);
        }
    }
}
