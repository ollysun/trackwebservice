<?php

use Phinx\Migration\AbstractMigration;

class AddExtraNoteParcelComment extends AbstractMigration
{
    public function up()
    {
        if (!$this->hasTable('company_billing_plan')) {
            $install_sql = file_get_contents(dirname(__FILE__) . '/../data/la_verita/add_extra_note_pacel_comment.sql');
            $this->execute($install_sql);
        }
    }
}
