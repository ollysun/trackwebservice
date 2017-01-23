CREATE TABLE `remitance` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`waybill_number` VARCHAR(50) NULL,
	`company_registration_number` VARCHAR(50) NULL,
	`payer_id` VARCHAR(50) NULL,
	`status` TINYINT NULL,
	`amount` DOUBLE NULL,
	`date` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	CONSTRAINT `FK__remitance_parcel` FOREIGN KEY (`waybill_number`) REFERENCES `parcel` (`waybill_number`),
	CONSTRAINT `FK__remitance_company` FOREIGN KEY (`company_registration_number`) REFERENCES `company` (`reg_no`),
	CONSTRAINT `FK__remitance_admin` FOREIGN KEY (`payer_id`) REFERENCES `admin` (`staff_id`),
	CONSTRAINT `FK__status` FOREIGN KEY (`status`) REFERENCES `status` (`id`)
)
	COLLATE='latin1_swedish_ci'
	ENGINE=InnoDB
;
