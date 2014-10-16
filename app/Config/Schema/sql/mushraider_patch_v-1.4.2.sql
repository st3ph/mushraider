SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

--
-- Alter table `events_characters` to add column 'last_notification'
--
ALTER TABLE `{prefix}events_characters` ADD `last_notification` TINYINT(3) NOT NULL DEFAULT '0' AFTER `status`;