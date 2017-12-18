ALTER TABLE parcel ADD COLUMN billing_type VARCHAR (15) DEFAULT NULL;
ALTER TABLE parcel ADD INDEX k_billing_type(billing_type);
ALTER TABLE parcel ADD COLUMN onforwarding_billing_plan_id INT(11) DEFAULT NULL;
ALTER TABLE parcel  ADD INDEX k_onforwarding_billing_plan_id(onforwarding_billing_plan_id);
ALTER TABLE parcel ADD CONSTRAINT fk_billing_plan_onforwarding_billing_plan_id FOREIGN KEY (onforwarding_billing_plan_id) REFERENCES billing_plan(id);
ALTER TABLE parcel ADD COLUMN weight_billing_plan_id INT(11) DEFAULT NULL;
ALTER TABLE parcel ADD INDEX k_weight_billing_plan_id(weight_billing_plan_id);
ALTER TABLE parcel ADD CONSTRAINT fk_billing_plan_weight_billing_plan_id FOREIGN KEY (weight_billing_plan_id)  REFERENCES billing_plan(id);
