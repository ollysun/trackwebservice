<?php

use Phinx\Migration\AbstractMigration;

class RemitanceTable extends AbstractMigration
{
    public function up()
    {
        if (!$this->hasTable('remitance')) {
            $install_sql = file_get_contents(dirname(__FILE__) . '/../data/la_verita/remitance.sql');
            $this->execute($install_sql);
        }
    }
}
