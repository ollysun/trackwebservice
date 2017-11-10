CREATE TABLE `payment_advice` (
	`id` INT(11) NOT NULL,
	`company_id` INT(11) NOT NULL,
	`amount` DOUBLE NOT NULL,
	`created_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`status` TINYINT(4) NOT NULL,
	`created_by` INT(11) NULL DEFAULT NULL,
	`reference_number` INT(11) NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `FK__payment_advice_company` (`company_id`),
	INDEX `FK__payment_advice_admin` (`created_by`),
	CONSTRAINT `FK__payment_advice_admin` FOREIGN KEY (`created_by`) REFERENCES `admin` (`id`),
	CONSTRAINT `FK__payment_advice_company` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`),
	CONSTRAINT `FK_payment_advice_status` FOREIGN KEY (`status`) REFERENCES `status` (`id`)
)
	ENGINE=InnoDB
;