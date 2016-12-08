<?php

use Phinx\Migration\AbstractMigration;

class CreateImportedParcelsTable extends AbstractMigration
{
    public function up()
    {
        if (!$this->hasTable('imported_parcels')) {
            $install_sql = file_get_contents(dirname(__FILE__) . '/../data/la_verita/imported_parcels_table.sql');
            $this->execute($install_sql);
        }
    }
}
