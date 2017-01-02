<?php

use Phinx\Migration\AbstractMigration;

class InternationalTariff extends AbstractMigration
{
    public function up()
    {
        if (!$this->hasTable('intl_zone')) {
            $install_sql = file_get_contents(dirname(__FILE__) . '/../data/la_verita/international_tariff.sql');
            $this->execute($install_sql);
        }
    }
}
