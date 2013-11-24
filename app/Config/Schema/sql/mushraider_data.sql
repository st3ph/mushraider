SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

--
-- Adding data for table `roles`
--

INSERT IGNORE INTO `{prefix}roles` (`id`, `title`, `alias`, `description`, `created`, `modified`) VALUES
(1, 'Admin', 'admin', 'Like Chuck Norris, he can do anything', '2013-05-01 05:20:34', '2013-05-01 05:20:34'),
(2, 'Officer', 'officer', 'Like Robin, he can do some things but not all (like driving the batmobile or change user role)', '2013-05-01 05:20:38', '2013-05-01 05:20:38'),
(3, 'Member', 'member', 'Just a random guy', '2013-05-01 05:21:45', '2013-05-01 05:21:45');

-- --------------------------------------------------------

--
-- Adding data for table `raids_sizes`
--

INSERT IGNORE INTO `{prefix}raids_sizes` (`id`, `size`) VALUES
(1, '5'),
(2, '8'),
(3, '10'),
(4, '20'),
(5, '25'),
(6, '40');

-- --------------------------------------------------------

--
-- Adding data for table `raids_roles`
--

INSERT IGNORE INTO `{prefix}raids_roles` (`id`, `title`) VALUES
(1, 'Tank'),
(2, 'Healer'),
(3, 'DPS'),
(4, 'Support');