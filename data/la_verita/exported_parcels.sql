CREATE TABLE `export_agent` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(50) NULL DEFAULT NULL,
	`website` VARCHAR(50) NULL DEFAULT NULL,
	`phone_number` VARCHAR(50) NULL DEFAULT NULL,
	`email` VARCHAR(50) NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
	COLLATE='latin1_swedish_ci'
	ENGINE=InnoDB
;

INSERT INTO export_agent(id, name) VALUE (1, "ARAMEX");
INSERT INTO export_agent(id, name) VALUE (2, "GMC");

CREATE TABLE `exported_parcel` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`parcel_id` BIGINT NOT NULL DEFAULT '0',
	`export_agent_id` INT NOT NULL DEFAULT '0',
	`agent_tracking_number` VARCHAR(50) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	CONSTRAINT `FK__exported_parcel_parcel` FOREIGN KEY (`parcel_id`) REFERENCES `parcel` (`id`),
	CONSTRAINT `FK__export_agent_exported_parcel` FOREIGN KEY (`export_agent_id`) REFERENCES `export_agent` (`id`)
)
	COLLATE='latin1_swedish_ci'
	ENGINE=InnoDB
;
