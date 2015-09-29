ALTER TABLE `parcel` ADD COLUMN `created_branch_id` INT NULL,
ADD INDEX `parcel_created_brach_id` (`created_branch_id`);
ALTER TABLE `parcel` ADD CONSTRAINT `fk_parcel_created_branch_id` FOREIGN KEY (`created_branch_id`) REFERENCES `branch` (`id`);