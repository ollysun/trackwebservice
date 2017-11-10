ALTER TABLE delivery_receipts ADD COLUMN `name` VARCHAR (255) NOT NULL;
ALTER TABLE delivery_receipts ADD COLUMN `phone_number` VARCHAR (50) NOT NULL;
ALTER TABLE delivery_receipts ADD COLUMN `email` VARCHAR (255) DEFAULT NULL;