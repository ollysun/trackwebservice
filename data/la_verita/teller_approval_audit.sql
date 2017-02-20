ALTER TABLE `teller`
	ADD COLUMN `approval_date` DATETIME NULL DEFAULT NULL AFTER `branch_id`,
	ADD COLUMN `approved_by` INT NULL DEFAULT NULL AFTER `approval_date`,
	ADD CONSTRAINT `FK_cod_teller_admin` FOREIGN KEY (`approved_by`) REFERENCES `admin` (`id`);

ALTER TABLE `cod_teller`
	ADD COLUMN `approval_date` DATETIME NULL DEFAULT NULL AFTER `branch_id`,
	ADD COLUMN `approved_by` INT NULL DEFAULT NULL AFTER `approval_date`,
	ADD CONSTRAINT `FK_cod_teller_admin` FOREIGN KEY (`approved_by`) REFERENCES `admin` (`id`);

ALTER TABLE `returned_shipment_teller`
	ADD COLUMN `approval_date` DATETIME NULL DEFAULT NULL AFTER `branch_id`,
	ADD COLUMN `approved_by` INT NULL DEFAULT NULL AFTER `approval_date`,
	ADD CONSTRAINT `FK_cod_teller_admin` FOREIGN KEY (`approved_by`) REFERENCES `admin` (`id`);
