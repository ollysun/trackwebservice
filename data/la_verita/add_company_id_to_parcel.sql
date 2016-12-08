ALTER TABLE `parcel`
	ADD COLUMN `company_id` INT(11) NULL DEFAULT NULL AFTER `weight_billing_plan_id`;