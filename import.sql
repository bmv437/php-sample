SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = '-04:00';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE DATABASE `clef_test` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `clef_test`;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `clef_id` varchar(32) NOT NULL,
  `name` varchar(64) NOT NULL,
  `logged_out_at` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


