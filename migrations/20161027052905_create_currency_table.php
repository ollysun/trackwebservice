<?php

use Phinx\Migration\AbstractMigration;

class CreateCurrencyTable extends AbstractMigration
{
    public function up()
    {
        $install_sql = file_get_contents(dirname(__FILE__) . '/../data/la_verita/create_currency_table.sql');
        $this->execute($install_sql);
    }
}
