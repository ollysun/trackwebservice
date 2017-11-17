INSERT INTO city(state_id, branch_id, name, transit_time, created_date, modified_date, status) VALUE((SELECT id FROM state WHERE name = 'lagos'), (SELECT id FROM branch WHERE name = 'lagos hub'), 'no city', 0, NOW(), NOW(), (SELECT id FROM `status` WHERE name = 'inactive'));

