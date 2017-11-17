<?php

use Phinx\Migration\AbstractMigration;

class SetParcelCreateByToNull extends AbstractMigration
{
    public function up()
    {
        $sql = file_get_contents(dirname(__FILE__) . '/../data/la_verita/update_parcel_set_created_admin_null.sql');
        $this->execute($sql);
    }
}
