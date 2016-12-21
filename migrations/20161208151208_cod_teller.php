<?php

use Phinx\Migration\AbstractMigration;

class CodTeller extends AbstractMigration
{
    public function up()
    {
        if (!$this->hasTable('cod_teller')) {
            $install_sql = file_get_contents(dirname(__FILE__) . '/../data/la_verita/cod_teller.sql');
            $this->execute($install_sql);
        }
    }
}
