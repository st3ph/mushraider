SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

--
-- Alter table `users` to add column 'bridge'
--

ALTER TABLE  `{prefix}users` ADD  `bridge` TINYINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `status`;

-- --------------------------------------------------------

--
-- Table structure for table `events_tpl`
--

CREATE TABLE IF NOT EXISTS `{prefix}events_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) COLLATE utf8_unicode_ci NULL,
  `event_title` varchar(50) COLLATE utf8_unicode_ci NULL,
  `event_description` text COLLATE utf8_unicode_ci NULL,
  `game_id` int(11) NOT NULL,
  `dungeon_id` int(11) NOT NULL,  
  `time_invitation` datetime DEFAULT NULL,
  `time_start` datetime DEFAULT NULL,
  `character_level` int(3) NOT NULL,  
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events_tpl_roles`
--

CREATE TABLE IF NOT EXISTS `{prefix}events_templates_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `raids_role_id` int(11) NOT NULL,
  `event_tpl_id` int(11) NOT NULL,
  `count` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;