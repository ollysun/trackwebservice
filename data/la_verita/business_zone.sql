CREATE TABLE `business_zone` (
	`id` INT NOT NULL,
	`name` INT NULL,
	`region_id` INT NULL,
	PRIMARY KEY (`id`),
	CONSTRAINT `FK__region` FOREIGN KEY (`region_id`) REFERENCES `region` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;
