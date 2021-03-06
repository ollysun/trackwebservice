CREATE PROCEDURE PopulatePlan(IN new_billing_plan_id INT)
	BEGIN
		DECLARE v_plan_type, v_default_plan_id INT;
		DECLARE v_outter_finish, v_inner_finish INT DEFAULT 0;

		SELECT `id`, `type` INTO v_default_plan_id, v_plan_type FROM `billing_plan` WHERE company_id IS NULL AND
																																											`type` = (SELECT `type` FROM `billing_plan` WHERE id = new_billing_plan_id)
		LIMIT 1;

		IF v_plan_type IN (1,3) THEN
			BEGIN
				DECLARE v_min_weight, v_max_weight, v_increment_weight FLOAT;
				DECLARE v_weight_range_id, v_old_weight_range_id INT;
				-- declare cursor for weight range
				DECLARE w_range_cursor CURSOR FOR
					SELECT `id`, `min_weight`, `max_weight`, `increment_weight` FROM `weight_range` WHERE billing_plan_id = v_default_plan_id;

				-- declare NOT FOUND handler
				DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_outter_finish = 1;

				OPEN w_range_cursor;
				range_loop: LOOP
					FETCH w_range_cursor INTO v_old_weight_range_id, v_min_weight, v_max_weight, v_increment_weight;

					IF v_outter_finish = 1 THEN
						LEAVE range_loop;
					END IF;

					INSERT INTO `weight_range` (`billing_plan_id`,`min_weight`,`max_weight`,`increment_weight`,`created_date`,`modified_date`,`status`)
					VALUES (new_billing_plan_id, v_min_weight, v_max_weight, v_increment_weight, NOW(), NOW(), 1);

					SET v_weight_range_id = LAST_INSERT_ID();

					-- insert all associated weight billings
					BEGIN
						DECLARE v_zone_id INT;
						DECLARE v_base_cost, v_increment_cost DECIMAL(10,2);
						DECLARE v_base_percentage, v_increment_percentage FLOAT;

						-- declare cursor for weight billing associated with outter weight_range
						DECLARE w_billing_cursor CURSOR FOR
							SELECT `zone_id`,`base_cost`,`base_percentage`,`increment_cost`,`increment_percentage` FROM `weight_billing` WHERE `weight_range_id` = v_old_weight_range_id;
						-- declare NOT FOUND handler
						DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_inner_finish = 1;

						SET v_inner_finish = 0;

						OPEN w_billing_cursor;
						billing_loop: LOOP
							FETCH w_billing_cursor INTO v_zone_id, v_base_cost, v_base_percentage,v_increment_cost, v_increment_percentage;

							IF v_inner_finish = 1 THEN
								LEAVE billing_loop;
							END IF;

							INSERT INTO `weight_billing` (`zone_id`,`weight_range_id`,`base_cost`,`base_percentage`,`increment_cost`,`increment_percentage`,`created_date`,`modified_date`,`status`)
							VALUES (v_zone_id, v_weight_range_id, v_base_cost, v_base_percentage, v_increment_cost, v_increment_percentage, NOW(), NOW(), 1);
						END LOOP billing_loop;
						CLOSE w_billing_cursor;
					END;
				END LOOP range_loop;
				CLOSE w_range_cursor;
			END;
		ELSE
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
						SELECT `id`, `name`,`description`,`code`,`amount`,`percentage` FROM `onforwarding_charge` WHERE `billing_plan_id` = v_default_plan_id;

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
		END IF;
	END