CREATE TABLE `user_auth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(128) NOT NULL,
  `password` varchar(60) NOT NULL,
  `entity_type` tinyint(4) NOT NULL COMMENT '1 - admin, 2- corporate',
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `status` (`status`),
  CONSTRAINT `user_auth_ibfk_1` FOREIGN KEY (`status`) REFERENCES `status` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE admin ADD COLUMN user_auth_id INT(11) NOT NULL AFTER id;