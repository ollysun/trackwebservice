<?php

use Phinx\Migration\AbstractMigration;

class OptionTable extends AbstractMigration
{
    public function up()
    {
        if (!$this->hasTable('option')) {
            $install_sql = file_get_contents(dirname(__FILE__) . '/../data/la_verita/option.sql');
            $this->execute($install_sql);
        }
    }
}
