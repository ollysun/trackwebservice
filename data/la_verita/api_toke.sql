CREATE TABLE `company_access` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`registration_number` VARCHAR(128) NULL DEFAULT NULL,
	`token` VARCHAR(256) NULL DEFAULT NULL,
	`auth_username` VARCHAR(128) NULL DEFAULT NULL,
	`allow_portal_login` TINYINT(4) NULL DEFAULT NULL,
	`allow_api_call` TINYINT(4) NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	INDEX `FK__api_token_company` (`registration_number`),
	CONSTRAINT `FK__api_token_company` FOREIGN KEY (`registration_number`) REFERENCES `company` (`reg_no`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
AUTO_INCREMENT=2
;
