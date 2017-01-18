<?php

use Phinx\Migration\AbstractMigration;

class RegionManagerId extends AbstractMigration
{
    public function up()
    {
        $install_sql = 'ALTER TABLE `region`
                        ADD COLUMN `manager_id` INT NULL DEFAULT NULL AFTER `active_fg`,
                        ADD CONSTRAINT `FK_region_admin` FOREIGN KEY (`manager_id`) REFERENCES `admin` (`id`);';
        $this->execute($install_sql);
    }

}
