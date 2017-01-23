<?php

use Phinx\Migration\AbstractMigration;

class BmCentreTable extends AbstractMigration
{
    public function up()
    {
        if (!$this->hasTable('bm_centre')) {
            $install_sql = file_get_contents(dirname(__FILE__) . '/../data/la_verita/bm_centre.sql');
            $this->execute($install_sql);
        }
    }
}
