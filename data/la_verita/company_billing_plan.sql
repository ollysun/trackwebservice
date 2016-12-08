CREATE TABLE `company_billing_plan` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`company_id` INT(11) NOT NULL,
	`billing_plan_id` INT(11) NOT NULL,
	`is_default` TINYINT NOT NULL,
	`status` TINYINT NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `FK__company` (`company_id`),
	INDEX `FK__billing_plan` (`billing_plan_id`),
	CONSTRAINT `FK__billing_plan` FOREIGN KEY (`billing_plan_id`) REFERENCES `billing_plan` (`id`),
	CONSTRAINT `FK__company` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`),
	CONSTRAINT `FK_company_billing_plan_status` FOREIGN KEY (`status`) REFERENCES `status` (`id`)
)
ENGINE=InnoDB
;
