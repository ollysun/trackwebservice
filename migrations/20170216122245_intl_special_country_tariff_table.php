<?php

use Phinx\Migration\AbstractMigration;

class IntlSpecialCountryTariffTable extends AbstractMigration
{
    public function up()
    {
        if($this->hasTable('intl_special_country_tariff')) return;
        $install_sql = "CREATE TABLE `intl_special_country_tariff` (
            `id` INT NOT NULL AUTO_INCREMENT,
            `country_id` INT NOT NULL DEFAULT '0',
            `weight` DOUBLE NOT NULL DEFAULT '0',
            `price` DOUBLE NOT NULL DEFAULT '0',
            PRIMARY KEY (`id`),
            CONSTRAINT `FK__intl_special_country_tariff_country` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`)
        )";
        $this->execute($install_sql);
    }
}