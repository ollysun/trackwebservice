<?php

use Phinx\Migration\AbstractMigration;

class SettingsTable extends AbstractMigration
{
  public function change() {

    $statusNotification = $this->table('setting');
    $statusNotification->addColumn('name', 'string')
      ->addColumn('value', 'text')
      ->addColumn('last_updated', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
      ->create();
  }
}
