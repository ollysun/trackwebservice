<?php

use Phinx\Migration\AbstractMigration;

class AddImportExtraPercentageField extends AbstractMigration
{
    public function change()
    {
      $table = $this->table('intl_zone');
      $table->addColumn('extra_percent_on_import', 'float')
      ->addColumn('sign', 'boolean')
      ->update();
    }
}
