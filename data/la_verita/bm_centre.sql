CREATE TABLE `bm_centre` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`staff_id` VARCHAR(50) NOT NULL,
	`branch_id` INT NULL,
	PRIMARY KEY (`id`),
	CONSTRAINT `FK__bm_centre_admin` FOREIGN KEY (`staff_id`) REFERENCES `admin` (`staff_id`),
	CONSTRAINT `FK__bm_centre_branch` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;
