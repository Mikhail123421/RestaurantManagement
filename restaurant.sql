-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 06, 2024 at 06:28 PM
-- Server version: 5.7.36
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `restaurant`
--

-- --------------------------------------------------------

--
-- Table structure for table `foodlist`
--

DROP TABLE IF EXISTS `foodlist`;
CREATE TABLE IF NOT EXISTS `foodlist` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(150) COLLATE utf8_persian_ci NOT NULL,
  `PRICE` float NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Dumping data for table `foodlist`
--

INSERT INTO `foodlist` (`ID`, `NAME`, `PRICE`) VALUES
(2, 'فسنجان', 18000),
(3, 'کباب کوبیده', 22000),
(4, 'چلوکباب', 25000),
(5, 'ته‌چین', 16000),
(6, 'آش رشته', 13000),
(10, 'ماکارونی', 400000000),
(11, 'سوشی', 200);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `USER_ID` varchar(150) COLLATE utf8_persian_ci NOT NULL,
  `FOOD_ID` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `FOOD_NAME` varchar(150) COLLATE utf8_persian_ci NOT NULL,
  `QUANTITY` int(50) NOT NULL,
  `PRICE` float NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`ID`, `USER_ID`, `FOOD_ID`, `FOOD_NAME`, `QUANTITY`, `PRICE`) VALUES
(1, '9', 'فسنجان', 'فسنجان - 18000 تومان', 5, 90000),
(2, '9', 'فسنجان', 'فسنجان - 18000 تومان', 5, 90000),
(3, '9', 'فسنجان', 'فسنجان - 18000 تومان', 10, 180000);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `F_NAME` varchar(150) COLLATE utf8_persian_ci NOT NULL,
  `L_NAME` varchar(150) COLLATE utf8_persian_ci NOT NULL,
  `EMAIL` varchar(150) COLLATE utf8_persian_ci NOT NULL,
  `ROLE` varchar(100) COLLATE utf8_persian_ci NOT NULL,
  `PASSWORD` varchar(150) COLLATE utf8_persian_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `F_NAME`, `L_NAME`, `EMAIL`, `ROLE`, `PASSWORD`) VALUES
(7, 'تس', 'دی', 'yek@gmail.com', 'admin', '$2y$10$/ACGnnzTB7rfwr4hO40sGe0/QUyw4P27pK70sh0BzdegeA.V7P7bq'),
(9, 'fdsfdf', 'dfdsfs', 'fdsfdsfds@gmail.com', 'guest', '$2y$10$x/Gzoah4kgacLIkixD1hP.06geUK0jiDFds0cQBdOV9ErKuZOYrP2');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
