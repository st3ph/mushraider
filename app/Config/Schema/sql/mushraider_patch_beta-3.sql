SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

--
-- Alter table `dungeons` to add column 'status'
--

ALTER TABLE  `{prefix}dungeons` ADD  `status` TINYINT( 1 ) NOT NULL DEFAULT  '1';

-- --------------------------------------------------------

--
-- Alter table `characters` to add column 'modified' and 'created'
--

ALTER TABLE  `{prefix}characters` ADD  `modified` DATETIME NOT NULL , ADD  `created` DATETIME NOT NULL;

-- --------------------------------------------------------

--
-- Alter table `characters` to add column 'modified' and 'created'
--

ALTER TABLE  `{prefix}users` ADD  `notifications_cancel` TINYINT( 1 ) NOT NULL DEFAULT  '1' AFTER  `email` , ADD  `notifications_new` TINYINT( 1 ) NOT NULL DEFAULT  '1' AFTER  `notifications_cancel` , ADD  `notifications_validate` TINYINT( 1 ) NOT NULL DEFAULT  '1' AFTER  `notifications_new`;