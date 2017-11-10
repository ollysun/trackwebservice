<?php

use Phinx\Migration\AbstractMigration;

class CreateTransitInfoTable extends AbstractMigration
{
    public function up()
    {
        if (!$this->hasTable('transit_info')) {
            $sql = file_get_contents(dirname(__FILE__) . '/../data/la_verita/transit_info.sql');
            $this->execute($sql);
        }
    }
}
