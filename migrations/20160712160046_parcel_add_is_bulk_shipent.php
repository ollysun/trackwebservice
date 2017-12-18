<?php

use Phinx\Migration\AbstractMigration;

class ParcelAddIsBulkShipent extends AbstractMigration
{
    public function up()
    {
        $this->table('parcel')
            ->addColumn('is_bulk_shipment', 'integer', ['null' => true, 'default' => 0, 'after' => 'notification_status'])
            ->update();
    }

    public function down()
    {
        $this->table('parcel')
            ->removeColumn('is_bulk_shipment')
            ->update();
    }
}
