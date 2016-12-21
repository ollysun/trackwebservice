CREATE TABLE `cod_teller` (
	`id` BIGINT(20) NOT NULL AUTO_INCREMENT,
	`bank_id` INT(10) UNSIGNED NOT NULL,
	`account_name` VARCHAR(255) NULL DEFAULT NULL,
	`account_no` VARCHAR(20) NOT NULL DEFAULT '',
	`teller_no` VARCHAR(60) NOT NULL,
	`amount_paid` BIGINT(20) NOT NULL,
	`paid_by` INT(11) NOT NULL,
	`created_by` INT(11) NOT NULL,
	`created_date` DATETIME NULL DEFAULT NULL,
	`modified_date` DATETIME NULL DEFAULT NULL,
	`status` TINYINT(4) NULL DEFAULT NULL,
	`branch_id` INT(11) NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	INDEX `teller_ibfk2_idx` (`paid_by`),
	INDEX `teller_ibfk1` (`bank_id`),
	INDEX `created_by` (`created_by`),
	INDEX `branch_id` (`branch_id`),
	CONSTRAINT `cod_teller_ibfk2` FOREIGN KEY (`paid_by`) REFERENCES `admin` (`id`),
	CONSTRAINT `cod_teller_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `admin` (`id`),
	CONSTRAINT `cod_teller_ibfk_2` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
AUTO_INCREMENT=6
;



CREATE TABLE `cod_teller_parcel` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`parcel_id` BIGINT(20) NOT NULL,
	`teller_id` BIGINT(20) NOT NULL,
	`created_date` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	INDEX `parcel_teller_fk2_idx` (`teller_id`),
	INDEX `parcel_id` (`parcel_id`),
	CONSTRAINT `cod_parcel_teller_fk2` FOREIGN KEY (`teller_id`) REFERENCES `cod_teller` (`id`),
	CONSTRAINT `cod_teller_parcel_ibfk_1` FOREIGN KEY (`parcel_id`) REFERENCES `parcel` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
AUTO_INCREMENT=6
;
