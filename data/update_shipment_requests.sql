
  ALTER TABLE shipment_requests ADD COLUMN parcel_id     BIGINT(20)        DEFAULT NULL AFTER description;
  ALTER TABLE shipment_requests ADD CONSTRAINT FOREIGN KEY fk_shipment_requests_parcel_parcel_id (parcel_id) REFERENCES parcel(id);