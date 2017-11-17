CREATE TABLE `option` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`key` VARCHAR(50) NOT NULL,
	`value` TEXT NOT NULL,
	`data_type` VARCHAR(50) NOT NULL DEFAULT 'string',
	PRIMARY KEY (`id`)
)
	COLLATE='latin1_swedish_ci'
	ENGINE=InnoDB
;
