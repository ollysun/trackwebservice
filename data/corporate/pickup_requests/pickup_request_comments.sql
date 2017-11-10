DROP TABLE IF EXISTS pickup_request_comments;

CREATE TABLE IF NOT EXISTS pickup_request_comments (
  `id` INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `pickup_request_id` INT(11) NOT NULL,
  `comment` TEXT NOT NULL,
  `type` VARCHAR(25) NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  `status` TINYINT(1) DEFAULT 1,
  KEY k_pickup_request_id (pickup_request_id),
  KEY k_type (`type`),
  KEY k_status (`status`),
  CONSTRAINT fk_src_sr_pickup_request_id FOREIGN KEY (pickup_request_id) REFERENCES pickup_requests(id)
);