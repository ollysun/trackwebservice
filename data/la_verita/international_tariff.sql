CREATE TABLE `intl_zone` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`code` TINYINT NOT NULL DEFAULT '0',
	`description` VARCHAR(500) NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
ENGINE=InnoDB
;


CREATE TABLE `intl_zone_country_map` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`country_id` INT NULL,
	`zone_id` INT NULL,
	PRIMARY KEY (`id`),
	CONSTRAINT `FK__intl_zone_country_map_country` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`),
	CONSTRAINT `FK__intl_zone_country_map_intl_zone` FOREIGN KEY (`zone_id`) REFERENCES `intl_zone` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;


CREATE TABLE `intl_weight_range` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`min_weight` DECIMAL(10,0) NOT NULL,
	`max_weight` DECIMAL(10,0) NOT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;

CREATE TABLE `intl_parcel_type` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`name` INT NULL,
	PRIMARY KEY (`id`)
)
	COLLATE='latin1_swedish_ci'
	ENGINE=InnoDB
;



CREATE TABLE `intl_tariff` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`weight_range_id` INT(11) NOT NULL DEFAULT '0',
	`zone_id` INT(11) NOT NULL DEFAULT '0',
	`parcel_type_id` TINYINT(4) NOT NULL DEFAULT '0',
	`base_amount` DECIMAL(10,2) NOT NULL DEFAULT '0',
	`increment` DECIMAL(10,2) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	INDEX `FK__intl_tariff_weight_range` (`weight_range_id`),
	INDEX `FK__intl_tariff_intl_zone` (`zone_id`),
	CONSTRAINT `FK__intl_tariff_intl_zone` FOREIGN KEY (`zone_id`) REFERENCES `intl_zone` (`id`),
	CONSTRAINT `FK__intl_tariff_weight_range` FOREIGN KEY (`weight_range_id`) REFERENCES `intl_weight_range` (`id`),
	CONSTRAINT `FK_intl_tariff_parcel_type` FOREIGN KEY (`parcel_type_id`) REFERENCES `parcel_type` (`id`)
)
	COLLATE='latin1_swedish_ci'
	ENGINE=InnoDB
;
