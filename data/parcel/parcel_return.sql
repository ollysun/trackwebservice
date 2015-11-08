CREATE TABLE `return_reasons` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `status_code` INT(11) NOT NULL,
  `meaning_of_status` VARCHAR(45) NOT NULL,
  `useage_of_status` VARCHAR (45) NOT NULL
);
