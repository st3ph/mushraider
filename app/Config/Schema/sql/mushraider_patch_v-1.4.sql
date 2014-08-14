SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

--
-- Alter table `raids_roles` to add column 'order'
--

ALTER TABLE  `{prefix}raids_roles` ADD `order` int(2) DEFAULT 0 AFTER  `title`;

-- --------------------------------------------------------

--
-- Alter table `classes` to add column 'icon'
--

ALTER TABLE  `{prefix}classes` ADD `icon` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL AFTER  `slug`;

-- --------------------------------------------------------

--
-- Alter table `dungeons` to add column 'icon'
--

ALTER TABLE  `{prefix}dungeons` ADD `icon` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL AFTER  `slug`;

-- --------------------------------------------------------

--
-- Alter table `games` to change column 'logo' type to varchar(255)
--

ALTER TABLE  `{prefix}games` MODIFY `logo` varchar(255);