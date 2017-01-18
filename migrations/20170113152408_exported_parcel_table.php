<?php

use Phinx\Migration\AbstractMigration;

class ExportedParcelTable extends AbstractMigration
{
    public function up()
    {
        if (!$this->hasTable('export_agent')) {
            $install_sql = file_get_contents(dirname(__FILE__) . '/../data/la_verita/exported_parcels.sql');
            $this->execute($install_sql);
        }
    }
}
