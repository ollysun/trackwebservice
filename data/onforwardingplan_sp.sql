DELIMITER //
CREATE PROCEDURE OnforwardingChargePlanBuild()
  BEGIN
    DECLARE v_city_id, v_onforwarding_charge, v_finished INTEGER DEFAULT 0;

-- declare cursor for cities
    DECLARE city_cursor CURSOR FOR
      SELECT id, onforwarding_charge_id FROM city;

-- declare NOT FOUND handler
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_finished = 1;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
-- ERROR
      ROLLBACK;
    END;

    DECLARE EXIT HANDLER FOR SQLWARNING
    BEGIN
-- ERROR
      ROLLBACK;
    END;

    CREATE TABLE `billing_plan`(
      `id` INT NOT NULL AUTO_INCREMENT,
      `company_id` INT,
      `type` TINYINT NOT NULL COMMENT '1 - weight, 2 - onforwarding charge, 3- numbers',
      `name` VARCHAR(255) NOT NULL,
      `created_date` DATETIME NOT NULL,
      `modified_date` DATETIME NOT NULL,
      `status` TINYINT NOT NULL,
      PRIMARY KEY (`id`),
      FOREIGN KEY (`company_id`) REFERENCES `company`(`id`),
      FOREIGN KEY (`status`) REFERENCES `status`(`id`)
    );

    INSERT INTO billing_plan (company_id, `type`, `name`, created_date, modified_date, `status`)
    VALUES (NULL, 1, 'tnt weight default', NOW(), NOW(), 1);
    INSERT INTO billing_plan (company_id, `type`, `name`, created_date, modified_date, `status`)
    VALUES (NULL, 2, 'tnt onforwarding default', NOW(), NOW(), 1);

    ALTER TABLE `weight_range`
    ADD COLUMN `billing_plan_id` INT DEFAULT 1  NOT NULL AFTER `id`,
    ADD FOREIGN KEY (`billing_plan_id`) REFERENCES `billing_plan`(`id`);
    ALTER TABLE `weight_range`
    CHANGE `billing_plan_id` `billing_plan_id` INT(11) NOT NULL;

-- onforwarding city bridge table
    CREATE TABLE `onforwarding_city`(
      `id` INT NOT NULL AUTO_INCREMENT,
      `city_id` INT NOT NULL,
      `onforwarding_charge_id` INT NOT NULL,
      `status` TINYINT NOT NULL,
      PRIMARY KEY (`id`),
      FOREIGN KEY (`city_id`) REFERENCES `city`(`id`),
      FOREIGN KEY (`onforwarding_charge_id`) REFERENCES `onforwarding_charge`(`id`),
      FOREIGN KEY (`status`) REFERENCES `status`(`id`)
    );

-- adding billing plan id to onforwarding_charge table
    ALTER TABLE `onforwarding_charge`
    ADD COLUMN `billing_plan_id` INT DEFAULT 2  NOT NULL AFTER `id`;
    ALTER TABLE `onforwarding_charge`
    CHANGE `billing_plan_id` `billing_plan_id` INT NOT NULL AFTER `id`;
    ALTER TABLE `onforwarding_charge`
    ADD FOREIGN KEY (`billing_plan_id`) REFERENCES `billing_plan`(`id`);


    START TRANSACTION;

-- open cursor to insert city info into onforwarding_city table
    OPEN city_cursor;
    set_city: LOOP

      FETCH city_cursor INTO v_city_id, v_onforwarding_charge;

-- exit loop once done
      IF v_finished = 1 THEN
        LEAVE set_city;
      END IF;

-- insert into onforwarding_city table
      INSERT INTO onforwarding_city (city_id, onforwarding_charge_id, `status`)
      VALUES (v_city_id, v_onforwarding_charge, 1);

    END LOOP set_city;
    CLOSE city_cursor;

    ALTER TABLE `city`
    DROP COLUMN `onforwarding_charge_id`,
    DROP INDEX `onforwarding_charge_id`,
    DROP FOREIGN KEY `city_ibfk_4`;

    COMMIT;
  END //
