CREATE TABLE `resource` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
  CREATE TABLE `resource_operations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resource_id` int(3) NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `resource_id` (`resource_id`),
  CONSTRAINT `resource_id` FOREIGN KEY (`resource_id`) REFERENCES `resource_id` (`id`),
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `resource_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resource_operation_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `resource_operation_id` (`resource_operation_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `resource_operation_id` FOREIGN KEY (`resource_operation_id`) REFERENCES `resource_operations` (`id`),
  CONSTRAINT `role_id` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
) ENGINE=InnoDB DEFAULT CHARSET=latin1;