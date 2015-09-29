ALTER TABLE `parcel` ADD COLUMN `route_id` INT NULL,
ADD INDEX `parcel_route_id` (`route_id`);
ALTER TABLE `parcel` ADD CONSTRAINT `fk_route_id` FOREIGN KEY (`route_id`) REFERENCES `routes` (`id`);