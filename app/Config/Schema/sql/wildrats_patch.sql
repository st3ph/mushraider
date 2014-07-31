SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

--
-- Adding attunement for character
--

ALTER TABLE  `mr_characters` ADD  `attunement_id` INT( 11 ) NULL DEFAULT NULL AFTER  `race_id`;



ALTER TABLE  `mr_characters` ADD  `build_url` VARCHAR( 256 ) NULL DEFAULT NULL AFTER  `level`;
ALTER TABLE  `mr_characters` ADD  `stat_capture` VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER  `build_url`;