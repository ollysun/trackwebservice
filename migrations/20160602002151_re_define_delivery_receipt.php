<?php

use Phinx\Migration\AbstractMigration;

class ReDefineDeliveryReceipt extends AbstractMigration
{
    public function change()
    {
        $this->table('delivery_receipts')->addIndex('waybill_number')->update();;
        $this->table('delivery_receipts')->addIndex('created_at')->update();
        $this->table('delivery_receipts')->addIndex('delivered_at')->update();
        $this->table('delivery_receipts')->addIndex('email')->update();
        $this->table('delivery_receipts')->addForeignKey('waybill_number', 'parcel', 'waybill_number', ['constraint' => 'fk_delivery_receipts_parcel_waybill_number'])->update();
    }
}
