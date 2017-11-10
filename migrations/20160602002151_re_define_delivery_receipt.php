<?php

use Phinx\Migration\AbstractMigration;

class ReDefineDeliveryReceipt extends AbstractMigration
{
    public function change()
    {
        $this->table('delivery_receipts')->addIndex('waybill_number', ['name' => 'k_delivery_receipts_waybill_number'])->update();
        $this->table('delivery_receipts')->addIndex('created_at', ['name' => 'k_delivery_receipts_created_at'])->update();
        $this->table('delivery_receipts')->addIndex('delivered_at', ['name' => 'k_delivery_receipts_delivered_at'])->update();
        $this->table('delivery_receipts')->addIndex('email', ['name' =>'k_delivery_receipts_email'])->update();
    }
}