ALTER TABLE `intl_tariff`
	CHANGE COLUMN `base_amount` `base_amount` DECIMAL(10,2) NOT NULL DEFAULT '0' AFTER `parcel_type_id`,
	CHANGE COLUMN `increment` `increment` DECIMAL(10,2) NOT NULL DEFAULT '0' AFTER `base_amount`;
