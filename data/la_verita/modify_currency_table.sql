ALTER TABLE `currencies`
	CHANGE COLUMN `name` `name` VARCHAR(50) NOT NULL DEFAULT '0' AFTER `id`,
	ADD COLUMN `code` VARCHAR(8) NOT NULL DEFAULT '0' AFTER `name`,
	CHANGE COLUMN `iso` `decimal_unicode` VARCHAR(8) NOT NULL DEFAULT '0' AFTER `code`,
	ADD COLUMN `hexadecimal_unicode` VARCHAR(8) NOT NULL DEFAULT '0' AFTER `decimal_unicode`;
