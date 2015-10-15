CREATE TABLE `request_type` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(45) NOT NULL
);

ALTER TABLE `parcel` ADD `request_type` int(11) unsigned NOT NULL DEFAULT '1';

ALTER TABLE `parcel` ADD CONSTRAINT `fk_request_type` FOREIGN KEY (`request_type`) REFERENCES `request_type` (`id`);