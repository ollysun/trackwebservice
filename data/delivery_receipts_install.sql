DROP TABLE IF EXISTS delivery_receipts;
CREATE TABLE IF NOT EXISTS delivery_receipts(
  id INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
  waybill_number VARCHAR(20) NOT NULL,
  receipt_type VARCHAR(50) NOT NULL,
  created_at DATETIME NOT NULL,
  delivered_by INT(11) NOT NULL,
  receipt_path VARCHAR(255) NOT NULL,
  CONSTRAINT fk_delivery_receipts_parcel_delivered_by FOREIGN KEY (delivered_by) REFERENCES admin(id)
);