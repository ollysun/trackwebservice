CREATE TABLE `distance` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`from_city_id` INT NOT NULL,
	`to_city_id` INT NOT NULL,
	`lenght` INT NOT NULL,
	PRIMARY KEY (`id`),
	CONSTRAINT `FK__distance_from_city` FOREIGN KEY (`from_city_id`) REFERENCES `city` (`id`),
	CONSTRAINT `FK__distance_to_city` FOREIGN KEY (`to_city_id`) REFERENCES `city` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;
