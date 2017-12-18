CREATE TABLE `business_zone` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`region_id` INT NOT NULL DEFAULT '0',
	`name` VARCHAR(50) NOT NULL DEFAULT '0',
	`description` VARCHAR(500) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	CONSTRAINT `FK__region` FOREIGN KEY (`region_id`) REFERENCES `region` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;

ALTER TABLE `business_zone`
	ADD COLUMN `status` TINYINT(4) NOT NULL AFTER `description`,
	ADD CONSTRAINT `FK_business_zone_status` FOREIGN KEY (`status`) REFERENCES `status` (`id`);

ALTER TABLE `company`
	ADD COLUMN `business_zone_id` INT NULL AFTER `status`;