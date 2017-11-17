<?php

use Phinx\Migration\AbstractMigration;

class AddDiscountAndDiscountedAmountDue extends AbstractMigration
{
  public function change()
  {
    $table = $this->table('parcel');
    $table->addColumn('discounted_amount_due', 'float', ['default' => 0])
      ->addColumn('initial_discount', 'float', ['default' => 0])
      ->update();
  }
}
