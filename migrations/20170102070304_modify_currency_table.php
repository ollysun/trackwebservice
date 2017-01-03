<?php

use Phinx\Migration\AbstractMigration;

class ModifyCurrencyTable extends AbstractMigration
{
    public function up()
    {
        if ($this->hasTable('currencies')) {
            $install_sql = file_get_contents(dirname(__FILE__) . '/../data/la_verita/modify_currency_table.sql');
            $this->execute($install_sql);
        }
    }
}
