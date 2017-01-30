<?php

use Phinx\Migration\AbstractMigration;

class TellerApprovalAudits extends AbstractMigration
{
    public function up()
    {
        $install_sql = file_get_contents(dirname(__FILE__) . '/../data/la_verita/teller_approval_audit.sql');
        $this->execute($install_sql);
    }
}
