DROP TABLE IF EXISTS shipment_request_comments;

CREATE TABLE IF NOT EXISTS shipment_request_comments (
  `id` INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `shipment_request_id` INT(11) NOT NULL,
  `comment` TEXT NOT NULL,
  `type` VARCHAR(25) NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  `status` TINYINT(1) DEFAULT 1,
  KEY k_shipment_request_id (shipment_request_id),
  KEY k_type (`type`),
  KEY k_status (`status`),
  CONSTRAINT fk_src_sr_shipment_request_id FOREIGN KEY (shipment_request_id) REFERENCES shipment_requests(id)
);