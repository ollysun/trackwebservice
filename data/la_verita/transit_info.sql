CREATE TABLE `transit_info` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `parcel_id` BIGINT NOT NULL ,
  `start_date_time` DATETIME NOT NULL,
  `end_date_time` DATETIME NULL DEFAULT NULL ,
  `held_by` INT NOT NULL , `admin` INT NOT NULL ,
  `from_branch_id` INT NOT NULL ,
  `to_branch_id` INT NOT NULL ,
  `transit_time` INT NOT NULL ,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`parcel_id`) REFERENCES `parcel`(`id`),
  FOREIGN KEY (`held_by`) REFERENCES `admin`(`id`),
  FOREIGN KEY (`from_branch_id`) REFERENCES `branch`(`id`),
  FOREIGN KEY (`to_branch_id`) REFERENCES `branch`(`id`)
) ENGINE = InnoDB;