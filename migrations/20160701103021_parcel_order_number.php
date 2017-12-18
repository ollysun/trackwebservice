<?php

use Phinx\Migration\AbstractMigration;

class ParcelOrderNumber extends AbstractMigration
{
    public function up()
    {
        $this->table('parcel')
            ->addColumn('order_number', 'string', ['length' => 28, 'null' => true, 'default' => null, 'after' => 'others'])
            ->update();
    }

    public function down()
    {
        $this->table('parcel')
            ->removeColumn('order_number')
            ->update();
    }
}
