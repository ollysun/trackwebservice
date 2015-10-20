ALTER TABLE user_auth ADD COLUMN last_login_time INT DEFAULT 0 after modified_date;
UPDATE  user_auth SET last_login_time = UNIX_TIMESTAMP();