CREATE TABLE `company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `reg_no` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `city_id` int(11) NOT NULL,
  `credit_limit` decimal(10,0) NOT NULL,
  `discount` float NOT NULL,
  `primary_contact_id` int(11) DEFAULT NULL,
  `sec_contact_id` int(11) DEFAULT NULL,
  `relations_officer_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `company_fk0` (`status`),
  KEY `reg_no` (`reg_no`),
  KEY `name` (`name`),
  KEY `city_id` (`city_id`),
  KEY `primary_contact_id` (`primary_contact_id`),
  KEY `sec_contact_id` (`sec_contact_id`),
  KEY `relations_officer_id` (`relations_officer_id`),
  CONSTRAINT `company_fk0` FOREIGN KEY (`status`) REFERENCES `status` (`id`),
  CONSTRAINT `company_ibfk_1` FOREIGN KEY (`city_id`) REFERENCES `city` (`id`),
  CONSTRAINT `company_ibfk_4` FOREIGN KEY (`relations_officer_id`) REFERENCES `admin` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



/*Table structure for table `company_user` */

CREATE TABLE `company_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `user_auth_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `firstname` varchar(128) NOT NULL,
  `lastname` varchar(128) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `company_user_fk0` (`company_id`),
  KEY `user_auth_id` (`user_auth_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `company_user_fk0` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`),
  CONSTRAINT `company_user_ibfk_1` FOREIGN KEY (`user_auth_id`) REFERENCES `user_auth` (`id`),
  CONSTRAINT `company_user_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


/*New Roles*/
INSERT INTO `role` (`id`, `name`) VALUES (6, 'company_admin');
INSERT INTO `role` (`id`, `name`) VALUES (7, 'company_officer');


ALTER TABLE company ADD CONSTRAINT `company_ibfk_2` FOREIGN KEY (`primary_contact_id`) REFERENCES `company_user` (`id`);
ALTER TABLE company ADD  CONSTRAINT `company_ibfk_3` FOREIGN KEY (`sec_contact_id`) REFERENCES `company_user` (`id`);


ALTER TABLE `company` CHANGE `credit_limit` `credit_limit` DECIMAL(10,0)  NULL;
ALTER TABLE `company` CHANGE `discount` `discount` FLOAT  NULL;