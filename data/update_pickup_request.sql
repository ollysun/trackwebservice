
  ALTER TABLE pickup_requests ADD COLUMN parcel_id     BIGINT(20)        DEFAULT NULL AFTER shipment_description;
  ALTER TABLE pickup_requests ADD CONSTRAINT FOREIGN KEY fk_pickup_requests_parcel_parcel_id (parcel_id) REFERENCES parcel(id);