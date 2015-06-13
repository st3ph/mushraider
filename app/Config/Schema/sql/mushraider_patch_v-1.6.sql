SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

--
-- Alter table `events` to add column 'open'
--

ALTER TABLE `{prefix}users` ADD `calendar_key` varchar(13) COLLATE utf8_unicode_ci NOT NULL AFTER `activation_key`;