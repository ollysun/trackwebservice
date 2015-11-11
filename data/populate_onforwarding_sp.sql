CREATE PROCEDURE PopulateOnforwarding(IN new_billing_plan_id INT)
  BEGIN
    DECLARE v_plan_type, v_default_weight_plan_id, v_default_onforwarding_plan_id INT;
    DECLARE v_outter_finish, v_inner_finish INT DEFAULT 0;
		DECLARE type_weight, type_onforwarding INT;
		SET type_weight = 1;
		SET type_onforwarding = 2;

		SELECT `id`, `type` INTO v_default_onforwarding_plan_id, v_plan_type FROM `billing_plan` WHERE company_id IS NULL AND
				 `type` = type_onforwarding
		LIMIT 1;
	    IF v_plan_type = 2 THEN
	      BEGIN
		DECLARE v_name VARCHAR(100);
		DECLARE v_description TEXT;
		DECLARE v_code VARCHAR(100);
		DECLARE v_amount DECIMAL(10, 2);
		DECLARE v_percentage DOUBLE;
		DECLARE v_charge_id, v_old_charge_id INT;

		-- declare cursor for onforwarding_charge
		DECLARE o_charge_cursor CURSOR FOR
		  SELECT `id`, `name`,`description`,`code`,`amount`,`percentage` FROM `onforwarding_charge` WHERE `billing_plan_id` = v_default_onforwarding_plan_id;

		-- declare NOT FOUND handler
		DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_outter_finish = 1;

		OPEN o_charge_cursor;
		charge_loop: LOOP
		  FETCH o_charge_cursor INTO v_old_charge_id, v_name, v_description, v_code, v_amount, v_percentage;

		  IF v_outter_finish = 1 THEN
		  LEAVE charge_loop;
		  END IF;

		  INSERT INTO `onforwarding_charge` (`billing_plan_id`,`name`,`description`,`code`,`amount`,`percentage`,`created_date`,`modified_date`,`status`)
		    VALUES (new_billing_plan_id, v_name, v_description, v_code, v_amount, v_percentage, NOW(), NOW(), 1);

		  SET v_charge_id = LAST_INSERT_ID();

		  -- insert all associated onforwarding city
		  BEGIN
		    DECLARE v_city_id INT;
		    DECLARE o_city_cursor CURSOR FOR
		      SELECT `city_id` FROM `onforwarding_city` WHERE `onforwarding_charge_id` = v_old_charge_id;
		    -- declare NOT FOUND handler
		    DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_inner_finish = 1;

		    SET v_inner_finish = 0;
		    OPEN o_city_cursor;
		    city_loop: LOOP
		      FETCH o_city_cursor INTO v_city_id;
		      IF v_inner_finish = 1 THEN
		      LEAVE city_loop;
		      END IF;

		      INSERT INTO `onforwarding_city` (`city_id`,`onforwarding_charge_id`,`status`)
			VALUES (v_city_id, v_charge_id, 1);
		    END LOOP city_loop;
		    CLOSE o_city_cursor;
		  END;
		END LOOP charge_loop;
		CLOSE o_charge_cursor;
	      END;
	    END IF;
  END