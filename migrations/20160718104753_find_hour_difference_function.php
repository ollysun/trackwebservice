<?php

use Phinx\Migration\AbstractMigration;

class FindHourDifferenceFunction extends AbstractMigration
{
    public function up()
    {
        $sql = file_get_contents(dirname(__FILE__) . '/../data/la_verita/find_hour_difference.sql');
        $this->execute($sql);
    }
}
