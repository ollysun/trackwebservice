<?php

use Phinx\Migration\AbstractMigration;

class AclTables extends AbstractMigration
{
    public function up()
    {
        if (!$this->hasTable('resource')) {
            $install_sql = file_get_contents(dirname(__FILE__) . '/../data/kalu/acl_tables.sql');
            $this->execute($install_sql);
        }
    }
}
