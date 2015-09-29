ALTER TABLE `admin` ADD UNIQUE KEY  (`user_auth_id`);
ALTER TABLE admin ADD  CONSTRAINT `admin_user_auth_user_auth_id` FOREIGN KEY (`user_auth_id`) REFERENCES `user_auth` (`id`);