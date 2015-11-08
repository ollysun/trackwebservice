CREATE TABLE `return_reason` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `statusCode` INT(11) NOT NULL,
  `meaningOfStatus` VARCHAR(45) NOT NULL,
  `useageOfStatus` VARCHAR (45) NOT NULL
);
