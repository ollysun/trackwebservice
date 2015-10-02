DROP TABLE IF EXISTS corporate_shipment_requests;
CREATE TABLE IF NOT EXISTS corporate_shipment_requests (
  id                     INT(11) PRIMARY KEY  AUTO_INCREMENT NOT NULL,
  receiver_name          VARCHAR(100)                        NOT NULL,
  receiver_phone_number  VARCHAR(100)                        NOT NULL,
  receiver_email         VARCHAR(100)                        NOT NULL,
  receiver_address       TEXT                                NOT NULL,
  receiver_state_id      INT(11)                             NOT NULL,
  receiver_city_id       INT(11)                             NOT NULL,
  company_id              INT(11)                             NOT NULL,
  ecommerce_company_name VARCHAR(255) DEFAULT NULL,
  cash_on_delivery       VARCHAR(50)  DEFAULT NULL,
  reference_number       VARCHAR(100) DEFAULT NULL,
  estimated_weight       VARCHAR(50)                         NOT NULL,
  no_of_packages         INT(10)                             NOT NULL,
  shipment_value         INT(10)                             NOT NULL,
  shipping_cost          INT(10)                             NOT NULL,
  description            TEXT                           DEFAULT NULL,
  status            VARCHAR(50)                           NOT NULL,
  created_by            INT(11)                           NOT NULL,
  CONSTRAINT FOREIGN KEY fk_corporate_shipment_requests_state_state_id (receiver_state_id) REFERENCES state (id),
  CONSTRAINT FOREIGN KEY fk_corporate_shipment_requests_city_city_id (receiver_city_id) REFERENCES city (id),
  CONSTRAINT FOREIGN KEY fk_corporate_shipment_requests_company_company_id (company_id) REFERENCES company (id),
  CONSTRAINT FOREIGN KEY fk_corporate_shipment_requests_company_user_created_by (created_by) REFERENCES company_user (id)
);