<?php

use Phinx\Migration\AbstractMigration;

class StatusNotificationsTable extends AbstractMigration
{
    public function change() {

      $statusNotification = $this->table('status_notification_message');
      $statusNotification->addColumn('status_id', 'integer')
        ->addColumn('email_message', 'text', ['null' => 1])
        ->addColumn('subject', 'string', ['limit' => 200, 'null' => 1])
        ->addColumn('text_message', 'string', ['limit' => 500, 'null' => 1])
        ->create();
    }
}
