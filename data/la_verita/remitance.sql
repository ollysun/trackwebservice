CREATE TABLE `remittance` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`waybill_number` VARCHAR(50) NULL DEFAULT NULL,
	`company_registration_number` VARCHAR(50) NULL DEFAULT NULL,
	`payer_id` INT NULL DEFAULT NULL,
	`amount` DOUBLE NULL DEFAULT NULL,
	`date` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
	`ref` VARCHAR(128) NULL DEFAULT NULL,
	`status` TINYINT(4) NULL DEFAULT '1',
	PRIMARY KEY (`id`),
	UNIQUE INDEX `waybill_number` (`waybill_number`),
	INDEX `FK__remitance_parcel` (`waybill_number`),
	INDEX `FK__remitance_company` (`company_registration_number`),
	INDEX `FK__admin` (`payer_id`),
	INDEX `FK_remitance_status` (`status`),
	CONSTRAINT `FK__admin` FOREIGN KEY (`payer_id`) REFERENCES `admin` (`id`),
	CONSTRAINT `FK__remitance_company` FOREIGN KEY (`company_registration_number`) REFERENCES `company` (`reg_no`),
	CONSTRAINT `FK__remitance_parcel` FOREIGN KEY (`waybill_number`) REFERENCES `parcel` (`waybill_number`),
	CONSTRAINT `FK_remitance_status` FOREIGN KEY (`status`) REFERENCES `status` (`id`)
)
	COLLATE='latin1_swedish_ci'
	ENGINE=InnoDB
	AUTO_INCREMENT=3
;

INSERT INTO status(id, name) VALUE (25, 'remittance_awaiting_clearance');
INSERT INTO status(id, name) VALUE (26, 'remittance_ready_for_payout');
INSERT INTO status(id, name) VALUE (27, 'remittance_paid');