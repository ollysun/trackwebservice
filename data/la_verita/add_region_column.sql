ALTER TABLE `region`
	ADD COLUMN `manager_id` INT NULL DEFAULT NULL AFTER `active_fg`,
	ADD CONSTRAINT `region_manager` FOREIGN KEY (`manager_id`) REFERENCES `admin` (`id`);