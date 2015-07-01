SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

--
-- Alter table `events` to add column 'open'
--

ALTER TABLE `{prefix}users` ADD `calendar_key` varchar(13) COLLATE utf8_unicode_ci NOT NULL AFTER `activation_key`;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `{prefix}pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL, 
  `slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL, 
  `content` text COLLATE utf8_unicode_ci NULL,
  `public` tinyint(1) DEFAULT 0, 
  `published` tinyint(1) DEFAULT 0, 
  `onMenu` tinyint(1) DEFAULT 0, 
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;