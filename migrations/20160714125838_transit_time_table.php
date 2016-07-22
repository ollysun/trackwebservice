<?php

use Phinx\Migration\AbstractMigration;

class TransitTimeTable extends AbstractMigration
{
    public function up()
    {
        if (!$this->hasTable('transit_time')) {
            $sql = file_get_contents(dirname(__FILE__) . '/../data/la_verita/transit_time.sql');
            $this->execute($sql);
        }
    }
}
