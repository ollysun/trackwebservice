CREATE TABLE `return_reasons` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `status_code` VARCHAR(11) NOT NULL,
  `meaning_of_status` VARCHAR(100) NOT NULL,
  `usage_of_status` VARCHAR (150) NOT NULL
);

