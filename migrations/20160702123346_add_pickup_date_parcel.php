<?php

use Phinx\Migration\AbstractMigration;

class AddPickupDateParcel extends AbstractMigration
{
    public function up()
    {
        $this->table('parcel')
            ->addColumn('pickup_date', 'date', ['null' => true, 'default' => null, 'after' => 'order_number'])
            ->update();
    }

    public function down()
    {
        $this->table('parcel')
            ->removeColumn('pickup_date')
            ->update();
    }
}
