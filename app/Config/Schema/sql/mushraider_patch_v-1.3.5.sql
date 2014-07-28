SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

--
-- Alter table `events_templates` to add column 'time_invitation' and 'time_start'
--

ALTER TABLE  `{prefix}events_templates` ADD `time_invitation` datetime DEFAULT NULL AFTER  `dungeon_id`, ADD `time_start` datetime DEFAULT NULL AFTER  `time_invitation`;
