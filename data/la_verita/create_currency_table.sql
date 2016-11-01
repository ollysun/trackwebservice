CREATE TABLE `currencies` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`country_id` INT NOT NULL DEFAULT '0',
	`iso` VARCHAR(50) NOT NULL DEFAULT '0',
	`name` VARCHAR(50) NOT NULL DEFAULT '0',
	`conversion_rate` DECIMAL(10,0) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	INDEX `country_id` (`country_id`),
	CONSTRAINT `FK__country` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;