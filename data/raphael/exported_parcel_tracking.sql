CREATE TABLE `exported_parcel_tracking` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`commentdate` VARCHAR(50) NULL,
	`comment` VARCHAR(255) NULL,
	`created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
	`admin_id` INT NULL,
	`exportedparcel_id` INT NULL,
	PRIMARY KEY (`id`),
	CONSTRAINT `FK__admintrack` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`),
	CONSTRAINT `FK__exported_parceltrack` FOREIGN KEY (`exportedparcel_id`) REFERENCES `exported_parcel` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;
