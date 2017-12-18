<?php

use Phinx\Migration\AbstractMigration;

class CreateImportedParcelHistoriesTable extends AbstractMigration
{
    public function up()
    {
        if (!$this->hasTable('imported_parcel_histories')) {
            $install_sql = file_get_contents(dirname(__FILE__) . '/../data/la_verita/imported_parcel_histories_table.sql');
            $this->execute($install_sql);
        }
    }
}
