<?php

use Phinx\Migration\AbstractMigration;

class IntlExtraKg extends AbstractMigration
{
    public function up()
    {
        $install_sql = file_get_contents(dirname(__FILE__) . '/../data/la_verita/intl_exta_kg.sql');
        $this->execute($install_sql);
    }
}
