<?php

use Phinx\Migration\AbstractMigration;

class AddCreditBalanceColumn extends AbstractMigration
{
  public function change()
  {
    $table = $this->table('company');
    $table->addColumn('credit_balance', 'float', ['default' => 0])
      ->addColumn('credit_reset_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
      ->addColumn('override_credit', 'boolean', ['default' => 1])
      ->update();
  }
}
