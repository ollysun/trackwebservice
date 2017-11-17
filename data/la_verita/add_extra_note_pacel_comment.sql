ALTER TABLE `parcel_comments`
	ADD COLUMN `extra_note` TEXT NULL DEFAULT NULL AFTER `comment`;
