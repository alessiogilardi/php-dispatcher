-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versione server:              10.1.31-MariaDB - mariadb.org binary distribution
-- S.O. server:                  Win32
-- HeidiSQL Versione:            9.5.0.5196
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dump della struttura del database mysite
DROP DATABASE IF EXISTS `mysite`;
CREATE DATABASE IF NOT EXISTS `mysite` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `mysite`;

-- Dump della struttura di tabella mysite.emails
DROP TABLE IF EXISTS `emails`;
CREATE TABLE IF NOT EXISTS `emails` (
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`,`email`),
  CONSTRAINT `FK_emails_user_data` FOREIGN KEY (`user_id`) REFERENCES `user_data` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dump dei dati della tabella mysite.emails: ~0 rows (circa)
DELETE FROM `emails`;
/*!40000 ALTER TABLE `emails` DISABLE KEYS */;
/*!40000 ALTER TABLE `emails` ENABLE KEYS */;

-- Dump della struttura di tabella mysite.groups
DROP TABLE IF EXISTS `groups`;
CREATE TABLE IF NOT EXISTS `groups` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(255) NOT NULL,
  PRIMARY KEY (`group_id`),
  UNIQUE KEY `group_name` (`group_name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Dump dei dati della tabella mysite.groups: ~5 rows (circa)
DELETE FROM `groups`;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` (`group_id`, `group_name`) VALUES
	(2, 'admin'),
	(5, 'guest'),
	(3, 'moderator'),
	(1, 'uber'),
	(4, 'user');
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;

-- Dump della struttura di tabella mysite.groups_events_log
DROP TABLE IF EXISTS `groups_events_log`;
CREATE TABLE IF NOT EXISTS `groups_events_log` (
  `groups_event_log_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_date` datetime NOT NULL,
  `status` varchar(8) NOT NULL,
  `executor_user_id` int(11) NOT NULL,
  PRIMARY KEY (`groups_event_log_id`),
  KEY `FK_groups_events_log_groups` (`group_id`),
  KEY `FK_groups_events_log_user_data_1` (`user_id`),
  KEY `FK_groups_events_log_user_data_2` (`executor_user_id`),
  CONSTRAINT `FK_groups_events_log_groups` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_groups_events_log_user_data_1` FOREIGN KEY (`user_id`) REFERENCES `user_data` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_groups_events_log_user_data_2` FOREIGN KEY (`executor_user_id`) REFERENCES `user_data` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dump dei dati della tabella mysite.groups_events_log: ~0 rows (circa)
DELETE FROM `groups_events_log`;
/*!40000 ALTER TABLE `groups_events_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `groups_events_log` ENABLE KEYS */;

-- Dump della struttura di tabella mysite.ip_addresses
DROP TABLE IF EXISTS `ip_addresses`;
CREATE TABLE IF NOT EXISTS `ip_addresses` (
  `user_id` int(11) NOT NULL,
  `ip_address` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`ip_address`),
  CONSTRAINT `FK_ip_addresses_user_data` FOREIGN KEY (`user_id`) REFERENCES `user_data` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dump dei dati della tabella mysite.ip_addresses: ~0 rows (circa)
DELETE FROM `ip_addresses`;
/*!40000 ALTER TABLE `ip_addresses` DISABLE KEYS */;
/*!40000 ALTER TABLE `ip_addresses` ENABLE KEYS */;

-- Dump della struttura di tabella mysite.login
DROP TABLE IF EXISTS `login`;
CREATE TABLE IF NOT EXISTS `login` (
  `user_id` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `activation_token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  CONSTRAINT `FK_login_user_data` FOREIGN KEY (`user_id`) REFERENCES `user_data` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dump dei dati della tabella mysite.login: ~0 rows (circa)
DELETE FROM `login`;
/*!40000 ALTER TABLE `login` DISABLE KEYS */;
/*!40000 ALTER TABLE `login` ENABLE KEYS */;

-- Dump della struttura di tabella mysite.login_attempts
DROP TABLE IF EXISTS `login_attempts`;
CREATE TABLE IF NOT EXISTS `login_attempts` (
  `login_attempt_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `ip_address` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `event_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`login_attempt_id`),
  FULLTEXT KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dump dei dati della tabella mysite.login_attempts: ~0 rows (circa)
DELETE FROM `login_attempts`;
/*!40000 ALTER TABLE `login_attempts` DISABLE KEYS */;
/*!40000 ALTER TABLE `login_attempts` ENABLE KEYS */;

-- Dump della struttura di tabella mysite.password_reset
DROP TABLE IF EXISTS `password_reset`;
CREATE TABLE IF NOT EXISTS `password_reset` (
  `user_id` int(11) NOT NULL,
  `reset_token` varchar(255) NOT NULL,
  `request_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  CONSTRAINT `FK_reset_password_user_data` FOREIGN KEY (`user_id`) REFERENCES `user_data` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dump dei dati della tabella mysite.password_reset: ~0 rows (circa)
DELETE FROM `password_reset`;
/*!40000 ALTER TABLE `password_reset` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset` ENABLE KEYS */;

-- Dump della struttura di tabella mysite.rel_group_right
DROP TABLE IF EXISTS `rel_group_right`;
CREATE TABLE IF NOT EXISTS `rel_group_right` (
  `group_id` int(11) NOT NULL,
  `right_id` int(11) NOT NULL,
  PRIMARY KEY (`group_id`,`right_id`),
  KEY `FK_rel_group_right_rights` (`right_id`),
  CONSTRAINT `FK_rel_group_right_groups` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_rel_group_right_rights` FOREIGN KEY (`right_id`) REFERENCES `rights` (`right_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dump dei dati della tabella mysite.rel_group_right: ~6 rows (circa)
DELETE FROM `rel_group_right`;
/*!40000 ALTER TABLE `rel_group_right` DISABLE KEYS */;
INSERT INTO `rel_group_right` (`group_id`, `right_id`) VALUES
	(1, 1),
	(1, 2),
	(1, 3),
	(1, 4),
	(1, 5),
	(1, 6);
/*!40000 ALTER TABLE `rel_group_right` ENABLE KEYS */;

-- Dump della struttura di tabella mysite.rel_group_user
DROP TABLE IF EXISTS `rel_group_user`;
CREATE TABLE IF NOT EXISTS `rel_group_user` (
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`group_id`),
  KEY `FK_rel_group_user_groups` (`group_id`),
  CONSTRAINT `FK_rel_group_user_groups` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`),
  CONSTRAINT `FK_rel_group_user_user_data` FOREIGN KEY (`user_id`) REFERENCES `user_data` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dump dei dati della tabella mysite.rel_group_user: ~0 rows (circa)
DELETE FROM `rel_group_user`;
/*!40000 ALTER TABLE `rel_group_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `rel_group_user` ENABLE KEYS */;

-- Dump della struttura di tabella mysite.rights
DROP TABLE IF EXISTS `rights`;
CREATE TABLE IF NOT EXISTS `rights` (
  `right_id` int(11) NOT NULL AUTO_INCREMENT,
  `right_name` varchar(255) NOT NULL,
  `right_description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`right_id`),
  UNIQUE KEY `right_name` (`right_name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- Dump dei dati della tabella mysite.rights: ~6 rows (circa)
DELETE FROM `rights`;
/*!40000 ALTER TABLE `rights` DISABLE KEYS */;
INSERT INTO `rights` (`right_id`, `right_name`, `right_description`) VALUES
	(1, 'create_user', 'Allow the creation of new users'),
	(2, 'delete_user', 'Allow the deletion of users'),
	(3, 'edit_user', 'Allow to modify users data'),
	(4, 'view_user', 'Allow to view users data'),
	(5, 'grant_user', 'Allow to edit the group of a user'),
	(6, 'grant_group', 'Grant a right to a group');
/*!40000 ALTER TABLE `rights` ENABLE KEYS */;

-- Dump della struttura di tabella mysite.rights_events_log
DROP TABLE IF EXISTS `rights_events_log`;
CREATE TABLE IF NOT EXISTS `rights_events_log` (
  `rights_event_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `right_id` int(11) NOT NULL,
  `status` varchar(8) NOT NULL,
  `event_date` datetime NOT NULL,
  `executor_user_id` int(11) NOT NULL,
  PRIMARY KEY (`rights_event_log_id`),
  KEY `FK_rights_events_log_user_data` (`executor_user_id`),
  KEY `FK_rights_events_log_groups` (`group_id`),
  KEY `FK_rights_events_log_rights` (`right_id`),
  CONSTRAINT `FK_rights_events_log_groups` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_rights_events_log_rights` FOREIGN KEY (`right_id`) REFERENCES `rights` (`right_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_rights_events_log_user_data` FOREIGN KEY (`executor_user_id`) REFERENCES `user_data` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dump dei dati della tabella mysite.rights_events_log: ~0 rows (circa)
DELETE FROM `rights_events_log`;
/*!40000 ALTER TABLE `rights_events_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `rights_events_log` ENABLE KEYS */;

-- Dump della struttura di tabella mysite.user_data
DROP TABLE IF EXISTS `user_data`;
CREATE TABLE IF NOT EXISTS `user_data` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `surname` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dump dei dati della tabella mysite.user_data: ~0 rows (circa)
DELETE FROM `user_data`;
/*!40000 ALTER TABLE `user_data` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_data` ENABLE KEYS */;

-- Dump della struttura di tabella mysite.user_events_log
DROP TABLE IF EXISTS `user_events_log`;
CREATE TABLE IF NOT EXISTS `user_events_log` (
  `user_event_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `event_type_id` int(11) NOT NULL,
  `event_date` datetime NOT NULL,
  `status` varchar(8) NOT NULL,
  `executor_user_id` int(11) NOT NULL,
  PRIMARY KEY (`user_event_log_id`),
  KEY `FK_user_events_log_user_events_type` (`event_type_id`),
  KEY `FK_user_events_log_user_data_1` (`user_id`),
  KEY `FK_user_events_log_user_data_2` (`executor_user_id`),
  CONSTRAINT `FK_user_events_log_user_data_1` FOREIGN KEY (`user_id`) REFERENCES `user_data` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_user_events_log_user_data_2` FOREIGN KEY (`executor_user_id`) REFERENCES `user_data` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_user_events_log_user_events_type` FOREIGN KEY (`event_type_id`) REFERENCES `user_events_type` (`event_type_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dump dei dati della tabella mysite.user_events_log: ~0 rows (circa)
DELETE FROM `user_events_log`;
/*!40000 ALTER TABLE `user_events_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_events_log` ENABLE KEYS */;

-- Dump della struttura di tabella mysite.user_events_type
DROP TABLE IF EXISTS `user_events_type`;
CREATE TABLE IF NOT EXISTS `user_events_type` (
  `event_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `event_name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`event_type_id`),
  UNIQUE KEY `event_name` (`event_name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- Dump dei dati della tabella mysite.user_events_type: ~5 rows (circa)
DELETE FROM `user_events_type`;
/*!40000 ALTER TABLE `user_events_type` DISABLE KEYS */;
INSERT INTO `user_events_type` (`event_type_id`, `event_name`, `description`) VALUES
	(1, 'user_creation', ''),
	(2, 'user_deletion', ''),
	(3, 'user_modify', ''),
	(4, 'password_reset', ''),
	(5, 'password_modify', '');
/*!40000 ALTER TABLE `user_events_type` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
