-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Värd: 127.0.0.1
-- Tid vid skapande: 08 sep 2014 kl 16:14
-- Serverversion: 5.6.15-log
-- PHP-version: 5.5.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databas: `lab2logindb`
--

-- --------------------------------------------------------

--
-- Tabellstruktur `users`
--
-- use databaseName
CREATE TABLE IF NOT EXISTS `users` (
  `UserName` varchar(45) NOT NULL,
  `UserID` int(11) NOT NULL AUTO_INCREMENT,
  `Hash` varchar(200) NOT NULL,
  `CookieValue` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `PK` (`UserID`),
  KEY `UserName` (`UserName`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumpning av Data i tabell `users`
--

INSERT INTO `users` (`UserName`, `UserID`, `Hash`, `CookieValue`) VALUES
('Admin', 3, '$2a$10$DixWNrAgbkC7Z7T6JY7ex.g7hiqrXMF3qh9mBvI9CNQeWbeM.y5Tq', NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
