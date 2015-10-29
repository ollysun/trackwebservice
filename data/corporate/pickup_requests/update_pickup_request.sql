
  ALTER TABLE pickup_requests ADD COLUMN waybill_number     VARCHAR (25)        DEFAULT NULL AFTER shipment_description;
  ALTER TABLE pickup_requests ADD UNIQUE KEY pr_waybill_number(waybill_number);
  ALTER TABLE pickup_requests ADD CONSTRAINT FOREIGN KEY fk_pr_parcel_waybill_number (waybill_number) REFERENCES parcel(waybill_number);