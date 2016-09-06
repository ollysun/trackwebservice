CREATE TABLE `shipment_exception` ( 
	`id` INT(11) NOT NULL AUTO_INCREMENT , 
	`defaulter_branch_id` INT(11) NOT NULL , 
	`detector_branch_id` INT(11) NOT NULL , 
	`parcel_id` BIGINT(20) NOT NULL , 
	`creation_date` DATETIME NOT NULL,
	`modification_date` DATETIME NOT NULL,
	`admin_id` INT NOT NULL , 
	`held_by_id` INT NOT NULL , 
	`action_description` VARCHAR(128) NOT NULL , 
	PRIMARY KEY (`id`),
	FOREIGN KEY (defaulter_branch_id) REFERENCES branch(id),
	FOREIGN KEY (detector_branch_id) REFERENCES branch(id),
	FOREIGN KEY (parcel_id) REFERENCES parcel(id),
	FOREIGN KEY (admin_id) REFERENCES admin(id),
	FOREIGN KEY (held_by_id) REFERENCES admin(id)
) ENGINE = InnoDB;

-- phalcon model --name shipment_exception --get-set --doc --mapcolumn