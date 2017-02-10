ALTER TABLE `business_manager`
	ADD COLUMN `business_zone_id` INT(11) NULL AFTER `region_id`,

ALTER TABLE `branch`
	ADD COLUMN `business_zone_id` INT NULL AFTER `branch_type`;
