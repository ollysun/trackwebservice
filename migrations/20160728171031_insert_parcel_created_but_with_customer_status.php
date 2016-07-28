<?php

use Phinx\Migration\AbstractMigration;

class InsertParcelCreatedButWithCustomerStatus extends AbstractMigration
{
    public function up()
    {
        $sql = file_get_contents(dirname(__FILE__) . '/../data/la_verita/insert_parcel_created_with_c_st.sql');
        $this->execute($sql);
    }
}
