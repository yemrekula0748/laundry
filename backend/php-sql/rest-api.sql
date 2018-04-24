
-- CREATE DATABASE IF NOT EXISTS `u7970538_kulamobil` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci	;
-- USE `u7970538_kulamobil`;

-- Delete order table
-- DROP TABLE `order`;

-- Create order table
CREATE TABLE IF NOT EXISTS `order` (
	`id` int(11) NOT NULL AUTO_INCREMENT ,
	`date` tinytext NOT NULL ,
	`service` varchar(360) NOT NULL ,
	`uname` varchar(128) NOT NULL ,
	`status` tinytext NOT NULL ,
	`note` text NOT NULL ,
	PRIMARY KEY (`id`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;



-- Delete user table
-- DROP TABLE `user`;

-- Create user table
CREATE TABLE IF NOT EXISTS `user` (
	`id` int(11) NOT NULL AUTO_INCREMENT ,
	`fullname` varchar(360) NOT NULL ,
	`uname` varchar(128) NOT NULL ,
	`pwd` varchar(128) NOT NULL ,
	`address` text NOT NULL ,
	`type_ic` tinytext NOT NULL ,
	`no_ic` tinytext NOT NULL ,
	`phone` tinytext NOT NULL ,
	`email` tinytext NOT NULL ,
	UNIQUE KEY `uname` (`uname`),
	PRIMARY KEY (`id`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


