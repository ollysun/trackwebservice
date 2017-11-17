ALTER TABLE `intl_weight_range`
	ALTER `min_weight` DROP DEFAULT,
	ALTER `max_weight` DROP DEFAULT;
ALTER TABLE `intl_weight_range`
	CHANGE COLUMN `min_weight` `min_weight` DECIMAL(10,2) NOT NULL AFTER `id`,
	CHANGE COLUMN `max_weight` `max_weight` DECIMAL(10,2) NOT NULL AFTER `min_weight`;
