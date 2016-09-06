CREATE TABLE `audit` (
 `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
 `username` VARCHAR(128) NOT NULL , 
 `service` VARCHAR(128) NOT NULL , 
 `action_name` VARCHAR(128) NOT NULL , 
 `start_time` DATETIME NOT NULL ,
 `end_time` DATETIME NOT NULL,
 `ip_address` VARCHAR(64) NOT NULL , 
 `client` VARCHAR(265) NOT NULL , 
 `browser` VARCHAR(265) NOT NULL 
) ENGINE = MyISAM;