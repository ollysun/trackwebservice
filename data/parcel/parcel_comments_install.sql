CREATE TABLE IF NOT EXISTS parcel_comments(
  id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  waybill_number VARCHAR(25) NOT NULL,
  comment TEXT,
  created_by INT(11) NOT NULL,
  `type` VARCHAR (50) NOT NULL,
  created_at DATETIME NOT NULL,
  KEY k_parcel_comments_waybill_number (waybill_number),
  KEY k_parcel_comments_created_by (created_by),
  KEY k_parcel_comments_type (type),
  CONSTRAINT FOREIGN KEY fk_parcel_parcel_comments_added_by (created_by) REFERENCES admin(id)
);