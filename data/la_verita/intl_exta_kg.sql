CREATE TABLE `intl_extra_kg` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`shipping_type_id` TINYINT NULL DEFAULT NULL,
	`weight` DECIMAL(10,2) NULL DEFAULT NULL,
	`zone_id` INT(11) NULL DEFAULT NULL,
	`amount` DOUBLE NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	CONSTRAINT `FK_intl_extra_kg_shipping_type` FOREIGN KEY (`shipping_type_id`) REFERENCES `shipping_type` (`id`),
	CONSTRAINT `FK_intl_extra_kg_intl_zone` FOREIGN KEY (`zone_id`) REFERENCES `intl_zone` (`id`)
)
	COLLATE='latin1_swedish_ci'
	ENGINE=InnoDB
;