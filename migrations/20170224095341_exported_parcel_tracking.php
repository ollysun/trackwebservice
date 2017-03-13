<?php

use Phinx\Migration\AbstractMigration;

class ExportedParcelTracking extends AbstractMigration
{
    public function up()
    {
        if (!$this->hasTable('exported_parcel_tracking')) {
            $install_sql = file_get_contents(dirname(__FILE__) . '/../data/raphael/exported_parcel_tracking.sql');
            $this->execute($install_sql);
        }
    }
}
