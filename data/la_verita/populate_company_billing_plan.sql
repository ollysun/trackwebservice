insert into company_billing_plan (company_id, billing_plan_id, is_default, `status`)
select company_id, id, 1, 1 from billing_plan where company_id is not null