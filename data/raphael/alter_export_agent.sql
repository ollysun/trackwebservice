ALTER TABLE `export_agent`  ADD `agentapi` VARCHAR(255) NOT NULL  AFTER `email`,  ADD `hasapi` CHAR(1) NOT NULL  AFTER `agentapi`;