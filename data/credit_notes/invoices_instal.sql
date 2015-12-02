CREATE TABLE IF NOT EXISTS invoices (
  id INT(11) UNSIGNED PRIMARY KEY  AUTO_INCREMENT NOT NULL,
  address VARCHAR(255) NOT NULL,
  to_address VARCHAR(255) NOT NULL,
  account_number VARCHAR(15),
  stamp_duty DECIMAL(10, 2),
  currency VARCHAR(10) NOT NULL,
  reference VARCHAR(255) NOT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  KEY k_account_number(`account_number`),
  KEY k_address(`address`),
  KEY k_to_address(`to_address`),
  KEY k_currency(`currency`)
);