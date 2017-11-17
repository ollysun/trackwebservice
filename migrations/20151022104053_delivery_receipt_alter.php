<?php

use Phinx\Migration\AbstractMigration;

class DeliveryReceiptAlter extends AbstractMigration
{
    /**
     * Add name, phone_number and email to table if they don't exist.
     * Change receipt_path to allow null
     *
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function change()
    {
        $table = $this->table('delivery_receipts');

        //add columns if they are not existing
        if (!$table->hasColumn('name')) {
            $table->addColumn('name', 'string', ['limit' => 255, 'null' => false]);
            $table->addColumn('phone_number', 'string', ['limit' => 50, 'null' => false]);
            $table->addColumn('email', 'string', ['limit' => 200, 'null' => true]);
        }

        //allow null for receipt_path column
        $table->changeColumn('receipt_path', 'string', ['limit' => 255, 'null' => true]);

        $table->update();
    }
}
