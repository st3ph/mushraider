SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

--
-- Alter table `mr_characters` to allow higher levels above 999
--

ALTER TABLE `{prefix}characters` CHANGE `level` `level` INT(5) NOT NULL;

-- --------------------------------------------------------

--
-- Alter table `mr_dungeons` to allow higher levels above 999
--

ALTER TABLE `{prefix}dungeons` CHANGE `level_required` `level_required` INT(5) NOT NULL DEFAULT '1';

-- --------------------------------------------------------

--
-- Alter table `mr_dungeons` to allow higher levels above 999
--

ALTER TABLE `{prefix}events` CHANGE `character_level` `character_level` INT(5) NOT NULL;

-- --------------------------------------------------------

--
-- Alter table `mr_dungeons` to allow higher levels above 999
--

ALTER TABLE `{prefix}events_templates` CHANGE `character_level` `character_level` INT(5) NOT NULL;

-- --------------------------------------------------------