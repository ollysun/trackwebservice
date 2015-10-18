
  ALTER TABLE shipment_requests ADD COLUMN waybill_number     VARCHAR (25)        DEFAULT NULL AFTER description;
  ALTER TABLE shipment_requests ADD UNIQUE KEY sr_waybill_number(waybill_number);
  ALTER TABLE shipment_requests ADD CONSTRAINT FOREIGN KEY fk_sr_parcel_waybill_number (waybill_number) REFERENCES parcel(waybill_number);