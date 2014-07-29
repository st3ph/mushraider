SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

--
-- Adding attenuement for character
--

ALTER TABLE  `mr_characters` ADD  `attenuement_id` INT( 11 ) NULL DEFAULT NULL AFTER  `race_id`;