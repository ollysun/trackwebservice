<?php

use Phinx\Migration\AbstractMigration;

class ShipmentException extends AbstractMigration
{
    public function up()
    {
        if (!$this->hasTable('shipment_exception')) {
            $install_sql = file_get_contents(dirname(__FILE__) . '/../data/la_verita/shipment_exception.sql');
            $this->execute($install_sql);
        }
    }
}
