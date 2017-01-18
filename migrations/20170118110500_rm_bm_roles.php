<?php

use Phinx\Migration\AbstractMigration;

class RmBmRoles extends AbstractMigration
{
    public function up()
    {
        $install_sql = file_get_contents(dirname(__FILE__) . '/../data/la_verita/insert_rm_bm_roles.sql');
        $this->execute($install_sql);
    }
}
