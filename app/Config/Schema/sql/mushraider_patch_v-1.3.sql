SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

--
-- Alter table `users` to add column 'bridge'
--

ALTER TABLE  `{prefix}users` ADD  `bridge` TINYINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `status`;
