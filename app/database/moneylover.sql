-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Aug 25, 2015 at 02:20 PM
-- Server version: 5.5.35-1ubuntu1
-- PHP Version: 5.5.9-1ubuntu4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `moneylover`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `user_id` int(3) NOT NULL,
  `name` varchar(45) CHARACTER SET utf8mb4 NOT NULL,
  `image` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `purpose` tinyint(1) DEFAULT '0',
  `note` text CHARACTER SET utf8mb4,
  PRIMARY KEY (`id`),
  KEY `fk_categories_users1_idx` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=31 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `user_id`, `name`, `image`, `purpose`, `note`) VALUES
(3, 26, 'mua sam', '', 0, 'mua sam mat tien'),
(4, 26, 'ÄÆ°á»£c cho', '', 1, ''),
(6, 26, 'mua sáº¯m', '', 1, ''),
(7, 26, 'khÃ¡c', '', 0, ''),
(8, 26, 'Äƒn uá»‘ng', '', 0, ''),
(10, 46, 'tÃ¬nh yÃªu', '', 0, ''),
(18, 26, 'Äƒn uá»‘ng', '', 1, ''),
(19, 26, 'giÃ¡o dá»¥c', '', 0, ''),
(26, 48, 'mua sam', '', 0, ''),
(27, 48, 'Äƒn uá»‘ng', '', 1, ''),
(28, 26, 'LÆ°Æ¡ng', '', 1, 'Tiá»n lÆ°Æ¡ng hÃ ng thÃ¡ng'),
(29, 26, 'KhÃ¡c', '', 1, ''),
(30, 26, 'Sinh nháº­t', '', 0, 'cÃ¡ nhÃ¢n');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `wallet_id` int(3) NOT NULL,
  `category_id` int(3) NOT NULL,
  `amount` int(11) DEFAULT NULL,
  `note` text COLLATE utf8_unicode_ci,
  `modified` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_use_money_wallet1_idx` (`wallet_id`),
  KEY `fk_use_money_category1_idx` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=93 ;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `wallet_id`, `category_id`, `amount`, `note`, `modified`, `created`) VALUES
(13, 29, 4, 100000, '', '2015-07-13 14:40:03', '2015-07-10 10:21:54'),
(14, 29, 3, -50000, 'mua mu bao hiem', '2015-07-13 08:40:25', '2015-07-13 08:40:25'),
(15, 29, 28, 70000, '', '2015-08-13 16:19:34', '2015-07-13 09:20:25'),
(25, 19, 10, -20000, '', '2015-07-14 18:05:45', '2015-07-14 18:05:45'),
(26, 29, 8, -20000, '', '2015-07-15 11:44:28', '2015-07-15 11:44:28'),
(27, 29, 4, 20000, '', '2015-07-15 18:34:49', '2015-07-15 18:34:49'),
(28, 29, 7, -10000, '', '2015-07-15 18:35:18', '2015-07-15 18:35:18'),
(52, 29, 19, -40000, '', '2015-07-17 10:24:07', '2015-07-17 10:24:07'),
(55, 29, 8, -20000, '', '2015-07-17 15:00:02', '2015-07-17 14:56:12'),
(58, 18, 23, 20000, '', '2015-07-17 17:00:44', '2015-07-17 17:00:44'),
(64, 39, 26, -50000, '', '2015-07-17 17:28:04', '2015-07-17 17:28:04'),
(67, 21, 6, 60000, '', '2015-07-21 09:37:54', '2015-07-21 09:37:54'),
(68, 21, 4, 60000, 'asd', '2015-08-07 10:15:35', '2015-07-21 09:38:11'),
(72, 24, 3, -20000, '', '2015-07-21 10:09:29', '2015-07-21 10:09:29'),
(73, 24, 6, 50000, '', '2015-07-21 10:09:39', '2015-07-21 10:09:39'),
(74, 29, 6, 500000, '', '2015-07-21 14:25:13', '2015-07-21 14:25:13'),
(76, 29, 3, -10000, '', '2015-07-24 17:17:16', '2015-07-21 17:09:33'),
(77, 29, 8, -20000, 'di an sang', '2015-08-07 09:34:47', '2015-07-24 17:17:48'),
(78, 42, 4, 70000, 'ghghg', '2015-08-05 18:07:06', '2015-08-05 17:36:00'),
(80, 42, 8, -20000, 'th', '2015-08-05 18:30:02', '2015-08-05 18:30:02'),
(81, 42, 3, -20000, 'gjgh', '2015-08-06 07:46:48', '2015-08-06 07:46:48'),
(83, 29, 6, 25000, 'duoc cho.', '2015-08-07 09:17:12', '2015-02-04 00:00:00'),
(84, 29, 18, 20000, '', '2015-08-10 08:20:07', '2015-08-10 08:20:07'),
(85, 29, 8, -25000, '', '2015-08-10 15:29:53', '2015-08-19 00:00:00'),
(86, 29, 8, -34000, '', '2015-08-10 16:03:26', '2015-08-10 16:03:26'),
(87, 29, 7, -12000, '', '2015-08-10 16:05:37', '2015-08-10 16:05:37'),
(89, 29, 3, -12000, '', '2015-08-10 16:13:04', '2015-08-10 16:13:04'),
(91, 29, 8, -15000, '', '2015-08-10 16:17:52', '2015-08-10 16:17:52'),
(92, 29, 4, 100000, '', '2015-08-10 16:18:26', '2015-08-10 16:18:26');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `current_wallet_id` int(11) DEFAULT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '0',
  `token` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=49 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `current_wallet_id`, `activated`, `token`, `modified`, `created`) VALUES
(26, 'tomcat5', 'tomcat5@gmail.com', 'de6c8eae47e5512c735778de1437074c1ccfb611', 29, 1, NULL, '2015-08-07 10:25:35', '2015-07-06 16:38:55'),
(46, 'tomcat2', 'tanhm@rikkeisoft.com', 'de6c8eae47e5512c735778de1437074c1ccfb611', 19, 1, NULL, '2015-07-14 16:24:44', '2015-07-07 18:15:48'),
(48, 'tomcat', 'hayatebutler.07@gmail.com', 'de6c8eae47e5512c735778de1437074c1ccfb611', 39, 1, '55b9f8712bb13', '2015-07-17 17:27:28', '2015-07-17 17:24:29');

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

CREATE TABLE IF NOT EXISTS `wallets` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `user_id` int(3) NOT NULL,
  `name` varchar(45) CHARACTER SET utf8mb4 NOT NULL,
  `balance` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_wallet_account_idx` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=43 ;

--
-- Dumping data for table `wallets`
--

INSERT INTO `wallets` (`id`, `user_id`, `name`, `balance`, `created`, `modified`) VALUES
(19, 46, 'vi2', 20000, '2015-07-08 09:04:27', '2015-07-08 17:46:33'),
(21, 26, 'vi1', 460000, '2015-07-08 10:34:26', '2015-07-20 16:22:13'),
(24, 26, 'vi3', 110000, '2015-07-08 11:17:29', '2015-07-21 09:38:42'),
(29, 26, 'vi4', 650000, '2015-07-09 15:57:32', '2015-08-13 16:19:19'),
(38, 48, 'vitom', 500000, '2015-07-17 17:27:13', '2015-07-17 17:27:13'),
(39, 48, 'vitom2', 6950000, '2015-07-17 17:27:25', '2015-07-17 17:27:25'),
(42, 26, 'vi66', 380000, '2015-08-05 17:34:07', '2015-08-10 16:07:09');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
