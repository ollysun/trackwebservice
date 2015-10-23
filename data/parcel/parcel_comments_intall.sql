CREATE TABLE IF NOT EXIST parcel_comments(
id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
waybill_number VARCHAR(25) NOT NULL,
comment TEXT,
added_by INT(11) NOT NULL,
`type` VARCHAR (50) NOT NULL,
created_at DATETIME NOT NULL,
CONSTRAINT FOREIGN KEY fk_parcel_parcel_comments_waybill_number (waybill_number) REFERENCES parcel(waybill_number),
CONSTRAINT FOREIGN KEY fk_parcel_parcel_comments_added_by (added_by) REFERENCES admin(id)
);