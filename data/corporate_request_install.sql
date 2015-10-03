DROP TABLE IF EXISTS corporate_shipment_requests;
CREATE TABLE IF NOT EXISTS corporate_shipment_requests (
  id                     INT(11) PRIMARY KEY  AUTO_INCREMENT NOT NULL,
  receiver_name          VARCHAR(255)                        NOT NULL,
  receiver_phone_number  VARCHAR(100)                        NOT NULL,
  receiver_email         VARCHAR(150)                        NOT NULL,
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
  KEY k_csr_receiver_name (receiver_name),
  KEY k_csr_receiver_phone_number (receiver_phone_number),
  KEY k_csr_receiver_email (receiver_email),
  KEY k_csr_receiver_city_id (receiver_city_id),
  KEY k_csr_receiver_state_id  (receiver_state_id),
  KEY k_csr_company_id (company_id),
  KEY k_csr_ecommerce_company_name (ecommerce_company_name),
  KEY k_csr_cash_on_delivery (cash_on_delivery),
  KEY k_csr_reference_number (reference_number),
  KEY k_csr_estimated_weight (estimated_weight),
  KEY k_csr_no_of_packages (no_of_packages),
  KEY k_csr_shipment_value (shipment_value),
  KEY k_csr_shipping_cost (shipping_cost),
  KEY k_csr_status (status),
  KEY k_csr_created_by (created_by),
  CONSTRAINT FOREIGN KEY fk_corporate_shipment_requests_state_state_id (receiver_state_id) REFERENCES state (id),
  CONSTRAINT FOREIGN KEY fk_corporate_shipment_requests_city_city_id (receiver_city_id) REFERENCES city (id),
  CONSTRAINT FOREIGN KEY fk_corporate_shipment_requests_company_company_id (company_id) REFERENCES company (id),
  CONSTRAINT FOREIGN KEY fk_corporate_shipment_requests_company_user_created_by (created_by) REFERENCES company_user (id)
);