-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 03, 2016 at 03:37 PM
-- Server version: 5.5.49-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `DBM`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE IF NOT EXISTS `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=89 ;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `customer_id`, `product_id`, `quantity`) VALUES
(57, 2, 1, 1),
(69, 1, 1, 1),
(70, 1, 2, 1),
(71, 1, 3, 1),
(72, 22, 2, 1),
(73, 22, 3, 1),
(74, 24, 1, 1),
(84, 23, 1, 4),
(85, 23, 2, 3),
(86, 23, 3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `texts` text,
  `reply_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reply_id` (`reply_id`),
  KEY `customer_id` (`customer_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `customer_id`, `texts`, `reply_id`) VALUES
(1, 23, 'Your baby milk is awesome', NULL),
(2, 22, 'I think so, i like it very much', 1),
(3, 1, 'lol', 2),
(4, 1, 'Your delivery is quite slow, I wait my order for two weeks!', NULL),
(5, 23, 'Slow delivery!!', NULL),
(7, 24, 'My son love your milk', NULL),
(9, NULL, 'Sorry, we will improve delivery in the future!', 5),
(10, NULL, 'Thank!', 7),
(11, NULL, 'It won''t happened again ', 4);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE IF NOT EXISTS `customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `sex` tinyint(4) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `province` varchar(225) NOT NULL,
  `city` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `username`, `password`, `lastname`, `sex`, `firstname`, `birthdate`, `phone`, `email`, `address`, `province`, `city`) VALUES
(1, 'Marshall', '116b37f9cdcefe217d8f05bfe8533f49', 'Liu', 0, 'Marshall', '1996-06-18', '1820049443', 'Marshall@gmail.com', 'CDUTsong2334', 'Sichuan', 'ChengDu'),
(2, 'Johnny', '81dc9bdb52d04dc20036dbd8313ed055', '', 0, '', '2014-06-11', '1829493749', '', 'song2_333', 'Sichuan', 'ChongZhou'),
(3, 'Carry', '81dc9bdb52d04dc20036dbd8313ed055', 'Tian', 0, 'Kaiyuan', '2016-04-01', '1820043432', 'carryiss3b@gmail.com', 'song2_334', 'Sichuan', 'ChengDu'),
(22, 'Leo', '81dc9bdb52d04dc20036dbd8313ed055', 'Li', 0, 'xuan', '2016-05-01', '1829493749', '', 'song2_334', 'Guizhou', 'GuiYang'),
(23, 'David', '81dc9bdb52d04dc20036dbd8313ed055', 'Liu', 0, 'David', '2016-05-01', '1234567890', 'David@gmail.com', 'CDUT_Str.123', 'Zhejiang', 'HangZhou'),
(24, 'Kevin', '81dc9bdb52d04dc20036dbd8313ed055', '', 1, 'Kevin', '1916-05-10', '1830384923', '', 'song2_334', 'Chongqing', 'HeChuan'),
(25, 'Toby', '81dc9bdb52d04dc20036dbd8313ed055', NULL, 1, NULL, NULL, '123456790', '', 'cdut', 'Sichuan', 'ChengDu');

-- --------------------------------------------------------

--
-- Table structure for table `delivery`
--

CREATE TABLE IF NOT EXISTS `delivery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=103 ;

--
-- Dumping data for table `delivery`
--

INSERT INTO `delivery` (`id`, `status`) VALUES
(1, 'Delivery Done'),
(2, 'Preparing Your Products'),
(3, 'On the Road'),
(4, 'Arrived at Your City'),
(5, 'Distributing to Your Address'),
(101, 'Distribution Delay'),
(102, 'Distribution Cancel');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `daytime` datetime NOT NULL,
  `paid` tinyint(1) NOT NULL DEFAULT '0',
  `delivery_id` int(11) DEFAULT '2',
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  KEY `delivery_id` (`delivery_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=79 ;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `daytime`, `paid`, `delivery_id`) VALUES
(1, 1, '2016-04-23 18:47:14', 1, 2),
(5, 1, '2016-04-25 18:12:07', 1, 2),
(6, 1, '2016-04-25 18:18:28', 1, 2),
(7, 1, '2016-04-26 20:47:17', 1, 2),
(8, 1, '2016-04-28 18:12:12', 1, 2),
(9, 1, '2016-04-28 22:16:12', 1, 2),
(11, 1, '2016-04-28 22:17:16', 1, 2),
(12, 1, '2016-04-28 22:21:42', 1, 2),
(13, 1, '2016-04-28 22:32:27', 1, 2),
(14, 1, '2016-04-28 22:36:57', 1, 2),
(15, 1, '2016-04-28 23:52:36', 1, 2),
(16, 1, '2016-04-29 00:01:23', 1, 2),
(17, 1, '2016-04-29 00:26:23', 1, 2),
(20, 1, '2016-04-29 13:06:12', 1, 2),
(22, 1, '2016-04-29 13:34:34', 1, 2),
(23, 1, '2016-04-30 16:55:24', 1, 2),
(24, 1, '2016-05-01 09:14:30', 1, 2),
(26, 1, '2016-05-01 12:50:23', 1, 2),
(28, 1, '2016-05-01 14:17:36', 0, 2),
(29, 1, '2016-05-01 18:13:20', 1, 2),
(31, 1, '2016-05-01 18:14:13', 1, 2),
(32, 1, '2016-05-01 18:15:21', 1, 2),
(33, 1, '2016-05-01 18:16:05', 1, 2),
(38, 1, '2016-05-01 18:39:10', 1, 2),
(39, 1, '2016-05-01 19:17:39', 0, 2),
(40, 2, '2016-05-01 20:31:26', 0, 2),
(41, 23, '2016-05-01 20:38:08', 1, 1),
(42, 24, '2016-05-01 20:52:22', 0, 2),
(43, 3, '2016-05-01 21:01:11', 0, 2),
(44, 3, '2016-05-01 21:01:53', 1, 2),
(45, 1, '2016-05-01 21:19:49', 0, 2),
(46, 1, '2016-05-01 21:19:55', 0, 2),
(47, 1, '2016-05-01 21:19:58', 0, 2),
(48, 1, '2016-05-01 21:21:25', 0, 2),
(49, 1, '2016-05-01 21:21:30', 0, 2),
(50, 1, '2016-05-01 21:21:33', 0, 2),
(51, 1, '2016-05-01 21:21:36', 0, 2),
(52, 1, '2016-05-01 21:21:40', 0, 2),
(53, 1, '2016-05-01 21:21:45', 0, 2),
(54, 1, '2016-05-01 21:21:56', 0, 2),
(55, 23, '2016-05-01 23:48:50', 1, 1),
(56, 22, '2016-05-02 12:22:31', 0, 2),
(57, 23, '2016-05-02 13:23:47', 1, 3),
(58, 25, '2016-05-02 13:46:25', 1, 2),
(60, 23, '2016-05-03 14:08:24', 1, 2),
(62, 24, '2016-05-05 23:36:01', 1, 2),
(67, 25, '2016-05-28 21:41:31', 0, 2),
(68, 25, '2016-05-28 21:42:11', 0, 2),
(69, 23, '2016-05-29 19:01:04', 1, 1),
(70, 23, '2016-05-29 21:40:07', 0, 2),
(71, 23, '2016-06-03 15:07:22', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

CREATE TABLE IF NOT EXISTS `order_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orders_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_id` (`orders_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=114 ;

--
-- Dumping data for table `order_detail`
--

INSERT INTO `order_detail` (`id`, `orders_id`, `product_id`, `quantity`) VALUES
(1, 1, 1, 2),
(2, 1, 2, 1),
(5, 5, 1, 1),
(6, 6, 4, 2),
(7, 7, 1, 2),
(8, 7, 3, 2),
(9, 7, 4, 2),
(10, 8, 4, 1),
(11, 9, 1, 3),
(12, 9, 2, 1),
(14, 11, 2, 6),
(15, 12, 1, 1),
(17, 12, 4, 1),
(18, 13, 1, 1),
(20, 14, 1, 1),
(22, 15, 1, 1),
(23, 15, 2, 1),
(24, 16, 2, 2),
(25, 16, 1, 2),
(26, 17, 1, 1),
(27, 17, 2, 2),
(31, 20, 1, 3),
(36, 22, 4, 4),
(37, 23, 2, 2),
(38, 24, 1, 1),
(42, 26, 1, 1),
(43, 26, 2, 1),
(44, 26, 3, 1),
(45, 28, 1, 1),
(46, 28, 2, 1),
(47, 28, 3, 2),
(48, 28, 4, 1),
(49, 29, 1, 2),
(50, 29, 2, 1),
(51, 29, 3, 1),
(52, 29, 4, 1),
(54, 31, 2, 2),
(55, 32, 1, 1),
(56, 32, 2, 1),
(57, 33, 1, 1),
(64, 38, 2, 1),
(65, 39, 1, 2),
(66, 40, 1, 1),
(67, 41, 1, 2),
(68, 41, 2, 1),
(69, 42, 1, 1),
(70, 42, 3, 2),
(71, 43, 1, 1),
(72, 44, 1, 1),
(73, 44, 2, 2),
(74, 44, 3, 2),
(75, 45, 1, 1),
(76, 45, 2, 2),
(77, 45, 3, 3),
(78, 46, 1, 1),
(79, 47, 2, 1),
(80, 48, 2, 1),
(81, 49, 1, 2),
(82, 50, 3, 1),
(83, 51, 4, 2),
(84, 52, 1, 3),
(85, 53, 2, 5),
(86, 54, 3, 1),
(87, 55, 1, 1),
(88, 56, 1, 1),
(89, 57, 1, 3),
(90, 58, 1, 1),
(93, 60, 2, 1),
(95, 62, 1, 1),
(96, 67, 1, 1),
(97, 67, 3, 1),
(98, 67, 5, 2),
(99, 68, 1, 1),
(100, 69, 1, 1),
(101, 70, 1, 3),
(102, 70, 3, 1),
(103, 70, 2, 2),
(104, 71, 1, 2),
(105, 71, 4, 2),
(106, 71, 6, 2);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `price` double DEFAULT NULL,
  `description` text,
  `imgpath` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `price`, `description`, `imgpath`) VALUES
(1, 'No.1 BabyMilk', 70.2, 'Best baby milk ever', './images/product/product1.jpg'),
(2, 'No.2 BabyMilk', 66.1, 'High quality baby milk', './images/product/product2.jpg'),
(3, 'High Protein Milk', 68.5, 'High protein milk that have 3.6g pro/100ml', './images/product/product3.jpg'),
(4, 'Low Fat Milk', 61.2, 'Low fat milk is suit for people who keep fit', './images/product/product4.jpg'),
(5, 'No.3 Milk Powder', 43, 'Baby milk powder', './images/product/product1.jpg'),
(6, 'No.4 Milk', 30.2, 'The cheapest milk in DBM', './images/product/product1.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE IF NOT EXISTS `staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `username`, `password`) VALUES
(1, 'admin', '81dc9bdb52d04dc20036dbd8313ed055');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_3` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`),
  ADD CONSTRAINT `cart_ibfk_4` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`reply_id`) REFERENCES `comments` (`id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`delivery_id`) REFERENCES `delivery` (`id`);

--
-- Constraints for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD CONSTRAINT `order_detail_ibfk_1` FOREIGN KEY (`orders_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_detail_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
