SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

--
-- Alter table `characters` to add column 'status'
--

ALTER TABLE  `{prefix}characters` ADD  `status` TINYINT( 1 ) NOT NULL DEFAULT  '1' AFTER  `level`;