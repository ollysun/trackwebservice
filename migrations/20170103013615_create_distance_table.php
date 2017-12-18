<?php

use Phinx\Migration\AbstractMigration;

class CreateDistanceTable extends AbstractMigration
{
    public function up()
    {
        if (!$this->hasTable('distance')) {
            $install_sql = file_get_contents(dirname(__FILE__) . '/../data/la_verita/distance_table.sql');
            $this->execute($install_sql);
        }
    }
}
