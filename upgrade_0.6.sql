-- phpMyAdmin SQL Dump
-- version 3.3.2deb1ubuntu1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 15, 2020 at 10:40 PM
-- Server version: 5.1.70
-- PHP Version: 5.3.2-1ubuntu4.21

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `faucet`
--

-- --------------------------------------------------------

--
-- Table structure for table `balances`
--

ALTER TABLE `balances` MODIFY COLUMN `balance`  bigint(11) UNSIGNED NOT NULL AFTER `email`;
ALTER TABLE `balances` ADD COLUMN `referralbalance`  bigint(11) UNSIGNED NOT NULL AFTER `balance`;
ALTER TABLE `balances` MODIFY COLUMN `totalbalance`  bigint(11) UNSIGNED NOT NULL AFTER `referralbalance`;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
