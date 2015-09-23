ALTER TABLE `parcel` ADD COLUMN `created_branch_id` INT NULL
ADD INDEX `parcel_ibfk_13_idx` (`created_branch_id` ASC);
ALTER TABLE `parcel` ADD CONSTRAINT `parcel_ibfk_13` FOREIGN KEY (`created_branch_id`) REFERENCES `staging_tnt`.`branch` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;