SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

--
-- Alter table `characters` to add column 'default_role_id'
--

ALTER TABLE  `{prefix}characters` ADD `default_role_id` INT( 11 ) NULL DEFAULT NULL AFTER  `race_id`