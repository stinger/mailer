SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `test_development`
--

--
-- Table structure for table `resources`
--

DROP TABLE IF EXISTS `resources`;
CREATE TABLE IF NOT EXISTS `resources` (
  `id` int(11) NOT NULL auto_increment,
  `module_name` varchar(255) NOT NULL,
  `controller_name` varchar(255) NOT NULL,
  `action_name` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `resources`
--

INSERT INTO `resources` (`id`, `module_name`, `controller_name`, `action_name`) VALUES(1, 'default', 'index', 'index');
INSERT INTO `resources` (`id`, `module_name`, `controller_name`, `action_name`) VALUES(2, 'default', 'index', 'upload');
INSERT INTO `resources` (`id`, `module_name`, `controller_name`, `action_name`) VALUES(3, 'default', 'index', 'destroy');
INSERT INTO `resources` (`id`, `module_name`, `controller_name`, `action_name`) VALUES(4, 'default', 'index', 'list');
INSERT INTO `resources` (`id`, `module_name`, `controller_name`, `action_name`) VALUES(5, 'default', 'index', 'send');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL auto_increment,
  `role_name` varchar(255) NOT NULL,
  `parents` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`, `parents`) VALUES(1, 'guest', '');
INSERT INTO `roles` (`id`, `role_name`, `parents`) VALUES(2, 'member', 'guest');
INSERT INTO `roles` (`id`, `role_name`, `parents`) VALUES(3, 'admin', 'guest, member');

-- --------------------------------------------------------

--
-- Table structure for table `roles_resources_permissions`
--

DROP TABLE IF EXISTS `roles_resources_permissions`;
CREATE TABLE IF NOT EXISTS `roles_resources_permissions` (
  `id` int(11) NOT NULL auto_increment,
  `role_id` int(11) NOT NULL,
  `resource_id` int(11) NOT NULL,
  `allowed` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `roles_resources_permissions`
--

INSERT INTO `roles_resources_permissions` (`id`, `role_id`, `resource_id`, `allowed`) VALUES(1, 1, 1, 0);
INSERT INTO `roles_resources_permissions` (`id`, `role_id`, `resource_id`, `allowed`) VALUES(3, 2, 1, 1);
INSERT INTO `roles_resources_permissions` (`id`, `role_id`, `resource_id`, `allowed`) VALUES(6, 1, 2, 0);
INSERT INTO `roles_resources_permissions` (`id`, `role_id`, `resource_id`, `allowed`) VALUES(7, 2, 2, 1);
INSERT INTO `roles_resources_permissions` (`id`, `role_id`, `resource_id`, `allowed`) VALUES(8, 1, 3, 0);
INSERT INTO `roles_resources_permissions` (`id`, `role_id`, `resource_id`, `allowed`) VALUES(9, 2, 3, 1);
INSERT INTO `roles_resources_permissions` (`id`, `role_id`, `resource_id`, `allowed`) VALUES(10, 1, 4, 0);
INSERT INTO `roles_resources_permissions` (`id`, `role_id`, `resource_id`, `allowed`) VALUES(11, 2, 4, 1);
INSERT INTO `roles_resources_permissions` (`id`, `role_id`, `resource_id`, `allowed`) VALUES(12, 1, 5, 0);
INSERT INTO `roles_resources_permissions` (`id`, `role_id`, `resource_id`, `allowed`) VALUES(13, 2, 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) NOT NULL auto_increment,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--