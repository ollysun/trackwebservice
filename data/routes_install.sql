DROP TABLE IF EXISTS `routes`;
CREATE TABLE IF NOT EXISTS `routes` (
  `id` INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `code` VARCHAR(128) NULL,
  `branch_id` INT(11) NOT NULL,
  `created_date` DATETIME NOT NULL,
  `updated_date` DATETIME NOT NULL,
  `status` TINYINT(4) NOT NULL,
  KEY(`id`),
  UNIQUE KEY(`code`),
  KEY(`branch_id`),
  CONSTRAINT fk_routes_branch_branch_id FOREIGN KEY (`branch_id`) REFERENCES branch(`id`)
);