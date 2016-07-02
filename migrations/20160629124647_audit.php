<?php

use Phinx\Migration\AbstractMigration;

class Audit extends AbstractMigration
{
    public function up()
    {
        if (!$this->hasTable('audit')) {
            $install_sql = file_get_contents(dirname(__FILE__) . '/../data/la_verita/audit.sql');
            $this->execute($install_sql);
        }
    }
}
