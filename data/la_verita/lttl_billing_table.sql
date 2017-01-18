CREATE TABLE `lttl_billing` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`zone_id` INT(11) NOT NULL,
	`min_charge` INT(11) NOT NULL,
	`billing_plan_id` INT(11) NULL DEFAULT NULL,
	`charge_per_kg` INT(11) NOT NULL,
	`onforwarding_charge` INT(11) NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	INDEX `FK__lttl_billing_zone` (`zone_id`),
	CONSTRAINT `FK__lttl_billing_zone` FOREIGN KEY (`zone_id`) REFERENCES `zone` (`id`),
	CONSTRAINT `FK_lttl_billing_billing_plan` FOREIGN KEY (`billing_plan_id`) REFERENCES `billing_plan` (`id`)
)
	COLLATE='latin1_swedish_ci'
	ENGINE=InnoDB
;
