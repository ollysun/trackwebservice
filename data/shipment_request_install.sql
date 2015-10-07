DROP TABLE IF EXISTS corporate_shipment_requests;
DROP TABLE IF EXISTS shipment_requests;
CREATE TABLE IF NOT EXISTS shipment_requests (
  id                    INT(11) PRIMARY KEY  AUTO_INCREMENT NOT NULL,
  receiver_firstname    VARCHAR(255)                        NOT NULL,
  receiver_lastname     VARCHAR(255) DEFAULT NULL,
  receiver_phone_number VARCHAR(100) DEFAULT NULL,
  receiver_email        VARCHAR(150) DEFAULT NULL,
  receiver_address      TEXT                                NOT NULL,
  receiver_state_id     INT(11)                             NOT NULL,
  receiver_city_id      INT(11)                             NOT NULL,
  receiver_company_name VARCHAR (255)      DEFAULT NULL,
  company_id            INT(11)                             NOT NULL,
  cash_on_delivery      VARCHAR(50)  DEFAULT NULL,
  reference_number      VARCHAR(100) DEFAULT NULL,
  estimated_weight      VARCHAR(50)                         NOT NULL,
  no_of_packages        INT(10)                             NOT NULL,
  parcel_value          INT(10)                             NOT NULL,
  description           TEXT         DEFAULT NULL,
  status                VARCHAR(50)  DEFAULT 'pending',
  created_by            INT(11)                             NOT NULL,
  KEY k_sr_receiver_firstname (receiver_firstname),
  KEY k_sr_receiver_lastname (receiver_lastname),
  KEY k_sr_receiver_phone_number (receiver_phone_number),
  KEY k_sr_receiver_email (receiver_email),
  KEY k_sr_receiver_city_id (receiver_city_id),
  KEY k_sr_receiver_state_id  (receiver_state_id),
  KEY k_sr_company_id (company_id),
  KEY k_sr_cash_on_delivery (cash_on_delivery),
  KEY k_sr_reference_number (reference_number),
  KEY k_sr_estimated_weight (estimated_weight),
  KEY k_sr_no_of_packages (no_of_packages),
  KEY k_sr_parcel_value (parcel_value),
  KEY k_sr_status (status),
  KEY k_sr_created_by (created_by),
  CONSTRAINT FOREIGN KEY fk_shipment_requests_state_state_id (receiver_state_id) REFERENCES state (id),
  CONSTRAINT FOREIGN KEY fk_shipment_requests_city_city_id (receiver_city_id) REFERENCES city (id),
  CONSTRAINT FOREIGN KEY fk_shipment_requests_company_company_id (company_id) REFERENCES company (id),
  CONSTRAINT FOREIGN KEY fk_shipment_requests_company_user_created_by (created_by) REFERENCES company_user (id)
);