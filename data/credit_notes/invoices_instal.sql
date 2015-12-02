CREATE TABLE IF NOT EXISTS invoices (
  id             INT(11) UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL,
  address        VARCHAR(255)                                NOT NULL,
  to_address     VARCHAR(255)                                NOT NULL,
  account_number VARCHAR(15) DEFAULT NULL,
  stamp_duty     DECIMAL(10, 2) DEFAULT NULL,
  currency       VARCHAR(10)                                 NOT NULL,
  reference      VARCHAR(255)                                NOT NULL,
  created_at     DATETIME                                    NOT NULL,
  updated_at     DATETIME                                    NOT NULL,
  status         TINYINT(4)                                  NOT NULL,
  KEY k_account_number(`account_number`),
  KEY k_address(`address`),
  KEY k_to_address(`to_address`),
  KEY k_currency(`currency`)
);

CREATE TABLE IF NOT EXISTS invoice_parcels (
  id             INT(11) UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL,
  invoice_id     INT(11) UNSIGNED                            NOT NULL,
  waybill_number VARCHAR(25)                                 NOT NULL,
  discount       DECIMAL(1, 2)                               NOT NULL,
  net_amount     DECIMAL(10, 2)                              NOT NULL,
  created_at     DATETIME                                    NOT NULL,
  updated_at     DATETIME                                    NOT NULL,
  status         TINYINT(4) DEFAULT 1                        NOT NULL,
  KEY k_invoice_id(`invoice_id`),
  KEY k_waybill_number(`waybill_number`),
  KEY k_created_at(`created_at`),
  KEY k_updated_at(`updated_at`),
  UNIQUE u_waybill_number(`waybill_number`),
  FOREIGN KEY f_invoices_invoice_id(`invoice_id`) REFERENCES invoices(`invoice_id`),
  FOREIGN KEY f_parcel_waybill_number(`waybill_number`) REFERENCES parcel(`waybill_number`)
);