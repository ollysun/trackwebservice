<?php

use Phinx\Migration\AbstractMigration;

class AddNotificationToParcel extends AbstractMigration
{
    public function up()
    {
        $this->table('parcel')
            ->addColumn('notification_status', 'integer', ['null' => true, 'default' => 0, 'after' => 'pickup_date'])
            ->update();
    }

    public function down()
    {
        $this->table('parcel')
            ->removeColumn('notification_status')
            ->update();
    }
}
