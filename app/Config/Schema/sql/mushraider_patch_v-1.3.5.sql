SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

--
-- Alter table `events_templates` to add column 'time_invitation' and 'time_start'
--

ALTER TABLE  `{prefix}events_templates` ADD `time_invitation` datetime DEFAULT NULL AFTER  `dungeon_id`, ADD `time_start` datetime DEFAULT NULL AFTER  `time_invitation`;

-- --------------------------------------------------------

--
-- Alter table `users` to add column 'private_infos'
--

ALTER TABLE  `{prefix}users` ADD `private_infos` text COLLATE utf8_unicode_ci NULL AFTER  `email`;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE IF NOT EXISTS `{prefix}reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `description` text COLLATE utf8_unicode_ci NULL,
  `nb_comments` int(5) DEFAULT 0,
  `screenshot_1` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL, 
  `screenshot_2` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL, 
  `screenshot_3` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL, 
  `screenshot_4` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL, 
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
