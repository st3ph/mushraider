SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

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

-- --------------------------------------------------------

--
-- Table structure for table `characters`
--

CREATE TABLE IF NOT EXISTS `{prefix}characters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL, 
  `game_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `classe_id` int(11) NOT NULL,
  `race_id` int(11) NOT NULL,
  `default_role_id` int(11) NULL,
  `level` int(3) NOT NULL,
  `status` TINYINT( 1 ) NOT NULL DEFAULT  '1',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE IF NOT EXISTS `{prefix}classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL, 
  `icon` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `color` varchar(20) COLLATE utf8_unicode_ci DEFAULT '666666', 
  `game_id` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE  `{prefix}classes` ADD INDEX (`title`, `game_id`);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `{prefix}comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NULL DEFAULT NULL,
  `model_id` int(11) NULL DEFAULT NULL,
  `model` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `comment` text COLLATE utf8_unicode_ci NULL,
  `created` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dungeons`
--

CREATE TABLE IF NOT EXISTS `{prefix}dungeons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL, 
  `icon` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `game_id` int(11) NULL DEFAULT NULL,
  `raidssize_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE  `{prefix}dungeons` ADD INDEX (`title`, `game_id`);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `{prefix}events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) COLLATE utf8_unicode_ci NULL,
  `description` text COLLATE utf8_unicode_ci NULL,
  `game_id` int(11) NOT NULL,
  `dungeon_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `time_invitation` datetime NOT NULL,
  `time_start` datetime NOT NULL,
  `character_level` int(3) NOT NULL,
  `nb_comments` int(5) DEFAULT 0,
  `open` TINYINT(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events_roles`
--

CREATE TABLE IF NOT EXISTS `{prefix}events_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `raids_role_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `count` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE  `{prefix}events_roles` ADD INDEX (`raids_role_id`, `event_id`);

-- --------------------------------------------------------

--
-- Table structure for table `events_characters`
--

CREATE TABLE IF NOT EXISTS `{prefix}events_characters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `character_id` int(11) NULL DEFAULT NULL,
  `raids_role_id` int(11) NULL DEFAULT NULL,
  `status` tinyint(3) NOT NULL,
  `last_notification` tinyint(3) NOT NULL DEFAULT '0',
  `comment` varchar(75) COLLATE utf8_unicode_ci NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE  `{prefix}events_characters` ADD INDEX (`user_id`, `event_id`);

-- --------------------------------------------------------

--
-- Table structure for table `events_templates`
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
  `open` TINYINT(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events_templates_roles`
--

CREATE TABLE IF NOT EXISTS `{prefix}events_templates_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `raids_role_id` int(11) NOT NULL,
  `event_tpl_id` int(11) NOT NULL,
  `count` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE IF NOT EXISTS `{prefix}games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL, 
  `logo` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL, 
  `import_slug` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL, 
  `import_modified` int(10) DEFAULT 0, 
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `races`
--

CREATE TABLE IF NOT EXISTS `{prefix}races` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL, 
  `game_id` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE  `{prefix}races` ADD INDEX (`title`, `game_id`);

-- --------------------------------------------------------

--
-- Table structure for table `raids_sizes`
--

CREATE TABLE IF NOT EXISTS `{prefix}raids_sizes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `size` int(3) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE IF NOT EXISTS `{prefix}raids_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `order` int(2) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `{prefix}roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_permission`
--

CREATE TABLE IF NOT EXISTS `{prefix}role_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_permission_roles`
--

CREATE TABLE IF NOT EXISTS `{prefix}role_permission_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `role_permission_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE IF NOT EXISTS `{prefix}tickets` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `hash` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `modified` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `{prefix}settings` (
  `id` int(20) NOT NULL AUTO_INCREMENT,  
  `option` varchar(60) COLLATE utf8_unicode_ci NOT NULL,  
  `value` text COLLATE utf8_unicode_ci NULL,  
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `{prefix}users` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `username` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `private_infos` text COLLATE utf8_unicode_ci NULL,
  `notifications_cancel` TINYINT( 1 ) NOT NULL DEFAULT  '1',
  `notifications_new` TINYINT( 1 ) NOT NULL DEFAULT  '1',
  `notifications_validate` TINYINT( 1 ) NOT NULL DEFAULT  '1',
  `activation_key` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `bridge` tinyint(1) NOT NULL DEFAULT  '0',
  `modified` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `widgets`
--

CREATE TABLE IF NOT EXISTS `{prefix}widgets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `controller` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `action` varchar(25) COLLATE utf8_unicode_ci NOT NULL,  
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `params` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;