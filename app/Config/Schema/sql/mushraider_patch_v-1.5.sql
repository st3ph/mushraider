SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

--
-- Alter table `events` to add column 'open'
--

ALTER TABLE `{prefix}events` ADD `open` TINYINT(1) NOT NULL DEFAULT '0' AFTER `nb_comments`;

-- --------------------------------------------------------

--
-- Alter table `events_templates` to add column 'open'
--

ALTER TABLE `{prefix}events_templates` ADD `open` TINYINT(1) NOT NULL DEFAULT '0' AFTER `character_level`;

-- --------------------------------------------------------

--
-- Alter table `characters` to add column 'main'
--

ALTER TABLE `{prefix}characters` ADD `main` TINYINT(1) NOT NULL DEFAULT '0' AFTER `level`;

-- --------------------------------------------------------

--
-- Alter table `dungeons` to add column 'level_required'
--

ALTER TABLE  `{prefix}dungeons` ADD  `level_required` INT( 3 ) NOT NULL DEFAULT  '1' AFTER  `icon` ;

-- --------------------------------------------------------

--
-- Table structure for table `availabilities`
--

CREATE TABLE IF NOT EXISTS `{prefix}availabilities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `start` date DEFAULT NULL,
  `end` date DEFAULT NULL,
  `comment` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;