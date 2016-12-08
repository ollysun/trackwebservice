CREATE TABLE `business_manager` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`staff_id` VARCHAR(50) NOT NULL,
	`region_id` INT(11) NOT NULL,
	`name` VARCHAR(128) NULL DEFAULT NULL,
	`region_name` VARCHAR(128) NULL DEFAULT NULL,
	`status` TINYINT(4) NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	INDEX `bm_staff` (`staff_id`),
	INDEX `bm_region` (`region_id`),
	INDEX `bm_status` (`status`),
	CONSTRAINT `bm_region` FOREIGN KEY (`region_id`) REFERENCES `region` (`id`),
	CONSTRAINT `bm_staff` FOREIGN KEY (`staff_id`) REFERENCES `admin` (`staff_id`),
	CONSTRAINT `bm_status` FOREIGN KEY (`status`) REFERENCES `status` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
AUTO_INCREMENT=3
;
