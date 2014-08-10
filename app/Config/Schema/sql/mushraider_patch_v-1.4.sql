SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

--
-- Alter table `raids_roles` to add column 'order'
--

ALTER TABLE  `{prefix}raids_roles` ADD `order` int(2) DEFAULT 0 AFTER  `title`;