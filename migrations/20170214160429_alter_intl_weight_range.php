<?php

use Phinx\Migration\AbstractMigration;

class AlterIntlWeightRange extends AbstractMigration
{
    public function up()
    {
        $install_sql = file_get_contents(dirname(__FILE__) . '/../data/la_verita/alter_intl_weight_range.sql');
        $this->execute($install_sql);
    }
}
