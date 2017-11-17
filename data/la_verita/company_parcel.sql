CREATE TABLE `company_parcel` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`company_id` INT(11) NOT NULL,
	`waybill_number` VARCHAR(50) NOT NULL,
	`company_admin_id` INT(11) NULL DEFAULT NULL,
	`creation_source` TINYINT(4) NOT NULL DEFAULT '1' COMMENT '1 = CourierPlus Admin, 2 = Company Admin, 3 = API ',
	PRIMARY KEY (`id`),
	INDEX `FK__company_company_parcel` (`company_id`),
	INDEX `FK__parcel_company_parcel` (`waybill_number`),
	INDEX `FK__company_user_company_parcel` (`company_admin_id`),
	CONSTRAINT `FK__company_company_parcel` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`),
	CONSTRAINT `FK__company_user_company_parcel` FOREIGN KEY (`company_admin_id`) REFERENCES `company_user` (`id`),
	CONSTRAINT `FK__parcel_company_parcel` FOREIGN KEY (`waybill_number`) REFERENCES `parcel` (`waybill_number`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;
