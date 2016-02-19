<?php

use Phinx\Migration\AbstractMigration;

class AlterParcelTableForAdminEdit extends AbstractMigration
{
    public function change()
    {
        if($this->hasTable('parcel')){
            $sql = file_get_contents(dirname(__FILE__) . '/../data/parcel/parcel_admin_edit_alter.sql');
            $this->execute($sql);
        }
    }
}
