SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

--
-- Alter table `events` to add column 'time_inscription'
--

ALTER TABLE `{prefix}events` ADD `time_inscription` datetime DEFAULT NULL AFTER `time_start`;