<?php

use Phinx\Migration\AbstractMigration;

class AlterExportAgent extends AbstractMigration
{
    public function up()
    {
        if($this->table('export_agent')->hasColumn('agentapi')) return;
        $install_sql = file_get_contents(dirname(__FILE__) . '/../data/raphael/alter_export_agent.sql');
        $this->execute($install_sql);
    }
}
