ALTER TABLE email_message ADD COLUMN email_message_code VARCHAR(100) DEFAULT NULL AFTER id;
ALTER TABLE `email_message` ADD UNIQUE INDEX `k_email_message_email_message_code` (`email_message_code`);
