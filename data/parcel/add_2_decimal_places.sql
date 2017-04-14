ALTER TABLE `parcel`
	CHANGE COLUMN `insurance` `insurance` DECIMAL(10,2) NULL DEFAULT NULL AFTER `qty_metrics`,
	CHANGE COLUMN `storage_demurrage` `storage_demurrage` DECIMAL(10,2) NULL DEFAULT NULL AFTER `insurance`,
	CHANGE COLUMN `handling_charge` `handling_charge` DECIMAL(10,2) NULL DEFAULT NULL AFTER `storage_demurrage`,
	CHANGE COLUMN `duty_charge` `duty_charge` DECIMAL(10,2) NULL DEFAULT NULL AFTER `handling_charge`,
	CHANGE COLUMN `cost_of_crating` `cost_of_crating` DECIMAL(10,2) NULL DEFAULT NULL AFTER `duty_charge`,
	CHANGE COLUMN `others` `others` DECIMAL(10,2) NULL DEFAULT NULL AFTER `cost_of_crating`,
	CHANGE COLUMN `base_price` `base_price` DECIMAL(10,2) NULL DEFAULT NULL AFTER `is_bulk_shipment`;
