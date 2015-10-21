CREATE TABLE `teller` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `bank_id` int(10) unsigned NOT NULL,
  `account_name` varchar(255) DEFAULT NULL,
  `account_no` varchar(20) NOT NULL DEFAULT '',
  `teller_no` varchar(60) NOT NULL,
  `amount_paid` bigint(20) NOT NULL,
  `paid_by` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `teller_ibfk2_idx` (`paid_by`),
  KEY `teller_ibfk1` (`bank_id`),
  KEY `created_by` (`created_by`),
  KEY `branch_id` (`branch_id`),
  CONSTRAINT `teller_ibfk2` FOREIGN KEY (`paid_by`) REFERENCES `admin` (`id`),
  CONSTRAINT `teller_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `admin` (`id`),
  CONSTRAINT `teller_ibfk_2` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`)
) ;

CREATE TABLE `teller_parcel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parcel_id` bigint(20) NOT NULL,
  `teller_id` bigint(20) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parcel_teller_fk2_idx` (`teller_id`),
  KEY `parcel_id` (`parcel_id`),
  CONSTRAINT `parcel_teller_fk2` FOREIGN KEY (`teller_id`) REFERENCES `teller` (`id`),
  CONSTRAINT `teller_parcel_ibfk_1` FOREIGN KEY (`parcel_id`) REFERENCES `parcel` (`id`)
);