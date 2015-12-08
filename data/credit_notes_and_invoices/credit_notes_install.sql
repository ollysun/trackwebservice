CREATE TABLE IF NOT EXISTS credit_notes (
  id                 INT(11) UNSIGNED PRIMARY KEY AUTO_INCREMENT      NOT NULL,
  credit_note_number VARCHAR(25)                                      NOT NULL,
  invoice_number     VARCHAR(25)                                      NOT NULL,
  created_at         DATETIME                                         NOT NULL,
  updated_at         DATETIME                                         NOT NULL,
  created_by         INT(11)                                          NOT NULL,
  `status`           TINYINT(4) DEFAULT 1                             NOT NULL,
  UNIQUE u_invoice_number(`invoice_number`),
  UNIQUE u_credit_note_number(`credit_note_number`),
  KEY k_created_at(`created_at`),
  KEY k_updated_at(`updated_at`),
  KEY k_created_by(`created_by`),
  KEY k_status(`status`),
  CONSTRAINT f_cn_invoices_status_status FOREIGN KEY (`status`) REFERENCES `status` (`id`),
  CONSTRAINT f_cn_invoices_invoice_number FOREIGN KEY (`invoice_number`) REFERENCES invoices (`invoice_number`),
  CONSTRAINT f_cn_admin_created_by FOREIGN KEY (`created_by`) REFERENCES admin (`id`)
);

CREATE TABLE IF NOT EXISTS credit_note_parcels (
  id                 INT(11) UNSIGNED PRIMARY KEY AUTO_INCREMENT      NOT NULL,
  credit_note_number VARCHAR(25)                                      NOT NULL,
  invoice_parcel_id  INT(11) UNSIGNED                                 NOT NULL,
  deducted_amount    DECIMAL(10, 2)                                   NOT NULL,
  new_net_amount     DECIMAL(10, 2)                                   NOT NULL,
  created_at         DATETIME                                         NOT NULL,
  updated_at         DATETIME                                         NOT NULL,
  `status`           TINYINT(4) DEFAULT 1                             NOT NULL,
  KEY k_credit_note_number(`credit_note_number`),
  KEY k_invoice_parcel_id(`invoice_parcel_id`),
  KEY k_created_at(`created_at`),
  KEY k_updated_at(`updated_at`),
  UNIQUE u_invoice_parcel_id(`invoice_parcel_id`),
  KEY k_status(`status`),
  CONSTRAINT f_cnp_invoices_status_status FOREIGN KEY (`status`) REFERENCES `status` (`id`),
  CONSTRAINT f_cnp_cn_credit_note_number FOREIGN KEY (`credit_note_number`) REFERENCES `credit_notes` (`credit_note_number`),
  CONSTRAINT f_cnp_inp_invoice_parcel_id FOREIGN KEY (`invoice_parcel_id`) REFERENCES `invoice_parcels` (`id`)
);