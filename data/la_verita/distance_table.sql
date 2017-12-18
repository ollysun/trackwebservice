CREATE TABLE `distance` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`from_branch_id` INT(11) NOT NULL,
	`to_branch_id` INT(11) NOT NULL,
	`lenght` INT(11) NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `FK__distance_from_city` (`from_branch_id`),
	INDEX `FK__distance_to_city` (`to_branch_id`),
	CONSTRAINT `FK__distance_from_branch` FOREIGN KEY (`from_branch_id`) REFERENCES `branch` (`id`),
	CONSTRAINT `FK__distance_to_branch` FOREIGN KEY (`to_branch_id`) REFERENCES `branch` (`id`)
)
	COLLATE='latin1_swedish_ci'
	ENGINE=InnoDB
;
