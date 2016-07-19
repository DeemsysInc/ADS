-- phpMyAdmin SQL Dump
-- version 4.1.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 05, 2015 at 02:41 AM
-- Server version: 5.5.40-cll
-- PHP Version: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `smarapps_client_sm`
--

-- --------------------------------------------------------

--
-- Table structure for table `attrib_ref`
--

CREATE TABLE IF NOT EXISTS `attrib_ref` (
  `attrib_ref_id` int(10) NOT NULL AUTO_INCREMENT,
  `prod_id` int(10) NOT NULL,
  `attrib_id` int(10) NOT NULL,
  `attrib_value` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`attrib_ref_id`),
  FULLTEXT KEY `attrib_value` (`attrib_value`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `attrib_ref`
--

INSERT INTO `attrib_ref` (`attrib_ref_id`, `prod_id`, `attrib_id`, `attrib_value`) VALUES
(1, 5892, 1, '4'' X 6'''),
(2, 5892, 1, '6'' X 8'''),
(3, 5892, 2, '#808000'),
(4, 5892, 2, '#90EE90'),
(5, 5891, 1, 'Single'),
(6, 5891, 1, 'Double'),
(7, 5891, 1, 'Queen'),
(8, 5891, 1, 'King'),
(9, 5891, 2, '#BA55D3'),
(10, 5891, 2, '#C0C0C0'),
(11, 5891, 2, '#A52A2A');

-- --------------------------------------------------------

--
-- Table structure for table `attrib_ref_old`
--

CREATE TABLE IF NOT EXISTS `attrib_ref_old` (
  `attrib_ref_id` int(10) NOT NULL AUTO_INCREMENT,
  `prod_id` int(10) NOT NULL,
  `attrib_id` int(10) NOT NULL,
  `attrib_value` text NOT NULL,
  PRIMARY KEY (`attrib_ref_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `attrib_ref_old`
--

INSERT INTO `attrib_ref_old` (`attrib_ref_id`, `prod_id`, `attrib_id`, `attrib_value`) VALUES
(1, 5892, 1, '["4'' X 6''","6'' X 8 ''"]'),
(2, 5892, 2, '["#F6F6F6","#C1C1C1","#FFFFFF"]'),
(3, 5891, 1, '["Single","Double","Queen","King"]'),
(4, 5891, 2, '["#000000","#C1C1C1","#E5E5E5"]');

-- --------------------------------------------------------

--
-- Table structure for table `cat_ref`
--

CREATE TABLE IF NOT EXISTS `cat_ref` (
  `cat_ref_id` int(10) NOT NULL AUTO_INCREMENT,
  `cat_parent_id` int(10) NOT NULL,
  `cat_id` int(10) NOT NULL,
  PRIMARY KEY (`cat_ref_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `cat_ref`
--

INSERT INTO `cat_ref` (`cat_ref_id`, `cat_parent_id`, `cat_id`) VALUES
(1, 0, 1),
(2, 0, 2),
(3, 0, 3),
(4, 2, 4),
(5, 2, 5),
(6, 3, 6);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `order_total` decimal(10,2) NOT NULL,
  `order_created_date` datetime NOT NULL,
  `order_updated_date` datetime NOT NULL,
  `order_status` tinyint(1) NOT NULL,
  `order_sales_tax` decimal(10,2) NOT NULL,
  `order_cart_total` decimal(10,2) NOT NULL,
  `order_shipping_cost` decimal(10,2) NOT NULL,
  `order_shipping_addr1` varchar(256) NOT NULL,
  `order_shipping_addr2` varchar(256) NOT NULL,
  `order_shipping_city` varchar(100) NOT NULL,
  `order_shipping_state` varchar(100) NOT NULL,
  `order_shipping_zip` varchar(100) NOT NULL,
  `order_shipping_country` varchar(100) NOT NULL,
  `order_shipping_method` varchar(256) NOT NULL,
  `order_session_id` varchar(256) NOT NULL,
  `user_pay_method_id` int(10) NOT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=119 ;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_total`, `order_created_date`, `order_updated_date`, `order_status`, `order_sales_tax`, `order_cart_total`, `order_shipping_cost`, `order_shipping_addr1`, `order_shipping_addr2`, `order_shipping_city`, `order_shipping_state`, `order_shipping_zip`, `order_shipping_country`, `order_shipping_method`, `order_session_id`, `user_pay_method_id`) VALUES
(1, 9, '4119.99', '2014-12-05 05:34:58', '2014-12-05 05:34:59', 1, '0.00', '3999.99', '120.00', 'dd', 'sss', 'aza', 'df', '2345', 'US', '5-6 Business Days', '5ebTBKTeEcJz1vDcQnAeqAa5hNtIi7uwNmdAz5wu', 2),
(2, 9, '4269.98', '2014-12-05 05:36:50', '2014-12-05 05:36:52', 1, '0.00', '4149.98', '120.00', 'dd', 'sss', 'aza', 'df', '2345', 'US', '5-6 Business Days', 'famLbOFcIfw9WCknweSUKJUiCBa2diTkDskomAox', 1),
(3, 9, '524.47', '2014-12-05 05:37:21', '2014-12-05 05:37:23', 1, '0.00', '399.97', '124.50', 'dd', 'sss', 'aza', 'df', '2345', 'US', '5-6 Business Days', 'famLbOFcIfw9WCknweSUKJUiCBa2diTkDskomAox', 1),
(4, 9, '224.49', '2014-12-05 05:37:55', '2014-12-05 05:37:56', 1, '0.00', '99.99', '124.50', 'dd', 'sss', 'aza', 'df', '2345', 'US', '5-6 Business Days', 'famLbOFcIfw9WCknweSUKJUiCBa2diTkDskomAox', 1),
(5, 9, '224.49', '2014-12-05 05:38:45', '2014-12-05 05:38:46', 1, '0.00', '99.99', '124.50', 'dd', 'sss', 'aza', 'df', '2345', 'US', '5-6 Business Days', 'famLbOFcIfw9WCknweSUKJUiCBa2diTkDskomAox', 1),
(6, 9, '864.49', '2014-12-05 05:39:45', '2014-12-05 05:39:46', 1, '0.00', '739.99', '124.50', 'dd', 'sss', 'aza', 'df', '2345', 'US', '5-6 Business Days', 'famLbOFcIfw9WCknweSUKJUiCBa2diTkDskomAox', 1),
(7, 9, '1402.19', '2014-12-05 05:41:20', '2014-12-05 05:42:13', 0, '0.00', '1379.99', '22.20', 'dd', 'sss', 'aza', 'df', '2345', 'US', '5-6 Business Days', 'famLbOFcIfw9WCknweSUKJUiCBa2diTkDskomAox', 0),
(8, 9, '1502.18', '2014-12-05 05:44:30', '2014-12-05 05:45:44', 1, '0.00', '1479.98', '22.20', 'dd', 'sss', 'aza', 'df', '2345', 'US', '5-6 Business Days', '6Ja5vSrurCs4Z8kY5a7y4LUMWTlVneDh598ngrFE', 1),
(9, 9, '304.48', '2014-12-05 05:50:42', '2014-12-05 05:50:51', 1, '0.00', '299.98', '4.50', 'dd', 'sss', 'aza', 'df', '2345', 'US', '5-6 Business Days', 'Fjps2ndUDXaCibNZaX0IBGN8LN9pXEGCKMlOyjbS', 1),
(10, 9, '154.49', '2014-12-05 06:05:33', '2014-12-05 06:05:34', 1, '0.00', '149.99', '4.50', 'dd', 'sss', 'aza', 'df', '2345', 'US', '5-6 Business Days', '0ITuggePRyA6flnWFxA9MlDliuolJbM2Av18lvRh', 1),
(11, 9, '104.49', '2014-12-05 06:07:20', '2014-12-05 06:07:31', 1, '0.00', '99.99', '4.50', 'dd', 'sss', 'aza', 'df', '2345', 'US', '5-6 Business Days', '0ITuggePRyA6flnWFxA9MlDliuolJbM2Av18lvRh', 1),
(12, 9, '643.00', '2014-12-05 06:12:26', '2014-12-05 06:12:38', 1, '0.00', '640.00', '3.00', 'dd', 'sss', 'aza', 'df', '2345', 'US', '5-6 Business Days', 'Vtur7fE3R0qEI2ig5BjOWXVOXDCtykPj2p9vihPf', 1),
(13, 9, '4102.98', '2014-12-05 06:17:08', '2014-12-05 06:20:35', 1, '0.00', '4099.98', '3.00', 'dd', 'sss', 'aza', 'df', '2345', 'US', '5-6 Business Days', 'W8Rzi8DXOkJ0ZkNJik4O7qGwYqjZz6vlmkvg34bm', 1),
(14, 9, '203.00', '2014-12-05 06:20:07', '2014-12-05 06:20:08', 1, '0.00', '200.00', '3.00', 'dd', 'sss', 'aza', 'df', '2345', 'US', '5-6 Business Days', 'W8Rzi8DXOkJ0ZkNJik4O7qGwYqjZz6vlmkvg34bm', 1),
(15, 9, '759.19', '2014-12-05 06:26:19', '2014-12-05 06:27:49', 1, '0.00', '739.99', '19.20', 'dd', 'sss', 'aza', 'df', '2345', 'US', '5-6 Business Days', 'U8y3Ho9ik5XfGJdMXuFyHE4uMLn6x6e9smkZFGKR', 1),
(16, 9, '4119.18', '2014-12-05 06:26:51', '2014-12-05 06:26:52', 1, '0.00', '4099.98', '19.20', 'dd', 'sss', 'aza', 'df', '2345', 'US', '5-6 Business Days', 'U8y3Ho9ik5XfGJdMXuFyHE4uMLn6x6e9smkZFGKR', 1),
(17, 9, '169.19', '2014-12-05 06:28:46', '2014-12-05 06:29:14', 1, '0.00', '149.99', '19.20', 'dd', 'sss', 'aza', 'df', '2345', 'US', '5-6 Business Days', 'U8y3Ho9ik5XfGJdMXuFyHE4uMLn6x6e9smkZFGKR', 1),
(18, 9, '402.97', '2014-12-05 06:35:07', '2014-12-05 06:35:08', 1, '0.00', '399.97', '3.00', 'dd', 'sss', 'aza', 'df', '2345', 'US', '5-6 Business Days', 'GDzVEwEzRaw78xqpzTcxubjuCUSOhqLD1LCRlzbE', 1),
(19, 9, '154.49', '2014-12-05 06:36:01', '2014-12-05 06:36:02', 1, '0.00', '149.99', '4.50', 'dd', 'sss', 'aza', 'df', '2345', 'US', '5-6 Business Days', 'GDzVEwEzRaw78xqpzTcxubjuCUSOhqLD1LCRlzbE', 1),
(20, 9, '154.49', '2014-12-08 04:25:40', '2014-12-08 04:25:41', 1, '0.00', '149.99', '4.50', 'dd', 'sss', 'aza', 'df', '2345', 'US', '5-6 Business Days', '7Et4cerLEfxCQficxsd9ywO7nKe19iG5lJPWX47Y', 1),
(21, 9, '102.99', '2014-12-08 04:26:36', '2014-12-08 04:26:37', 1, '0.00', '99.99', '3.00', 'dd', 'sss', 'aza', 'df', '2345', 'US', '5-6 Business Days', 'NWS4HWyAXuana5u6s92unlHLYEEBr2gqWn2tb7Ox', 1),
(22, 9, '104.49', '2014-12-08 04:29:22', '2014-12-08 04:29:23', 1, '0.00', '99.99', '4.50', 'dd', 'sss', 'aza', 'df', '2345', 'US', '5-6 Business Days', 'POOYV0wKXLJ4VHwltGI19WRxulCGAdKrNALzz9RH', 1),
(23, 9, '154.49', '2014-12-08 06:51:37', '2014-12-08 06:52:14', 1, '0.00', '149.99', '4.50', 'dd', 'sss', 'aza', 'df', '2345', 'US', '5-6 Business Days', 'b9jo1xURm9lzSCXGMOekHDEVfJrgK6wyDG5GKls0', 1),
(24, 9, '4809.99', '2014-12-11 02:05:18', '2014-12-11 02:31:32', 1, '0.00', '3999.99', '810.00', 'Add1', '', 'Newyork', 'OM', '4577', 'US', '1-2 Business Days', 'XAQreampWb2tJWMTBgTmxAzVM1NOmMUPUlVIbkqU', 1),
(25, 9, '8909.97', '2014-12-11 02:11:45', '2014-12-11 02:11:45', 0, '0.00', '8099.97', '810.00', 'Add1', '', 'Newyork', 'OH', '4577', 'US', '1-2 Business Days', 'hVtELgwIPGRDy0Qoj1YwKwWADtb4y0zT9En9mYnk', 0),
(26, 9, '20239.95', '2014-12-11 02:38:11', '2014-12-11 02:38:12', 1, '0.00', '19999.95', '240.00', 'Add1', '', 'Newyork', 'OM', '4577', 'US', '5-6 Business Days', 'y0cCqtgM02zoUF23RLXvpDkh4Z745McUNob6Tm4y', 1),
(27, 9, '4119.99', '2014-12-22 05:05:13', '2014-12-22 05:05:16', 1, '0.00', '3999.99', '120.00', 'Add1', '', 'Newyork', 'OM', '4577', 'US', '5-6 Business Days', 'Q5rl119W4LszTcDu9LYAkM4O3X6XDl4VxAhhSP1a', 1),
(28, 9, '4219.98', '2014-12-22 05:22:17', '2014-12-22 05:22:58', 1, '0.00', '4099.98', '120.00', 'Add1', '', 'Newyork', 'OM', '4577', 'US', '5-6 Business Days', 'Q5rl119W4LszTcDu9LYAkM4O3X6XDl4VxAhhSP1a', 1),
(29, 9, '8239.98', '2014-12-22 07:23:53', '2014-12-22 07:23:55', 1, '0.00', '7999.98', '240.00', 'Add1', '', 'Newyork', 'OM', '4577', 'US', '5-6 Business Days', 'aHjF7jxpLPUvrGnCZBROIOuqqlgreNJtrKIt6dNY', 1),
(30, 9, '8239.98', '2014-12-22 07:54:05', '2014-12-22 07:54:07', 1, '0.00', '7999.98', '240.00', 'Add1', '', 'Newyork', 'OM', '4577', 'US', '5-6 Business Days', 'NStm9caxPh36Zh3f1i08egrN3avBKLyraYrOKHuC', 1),
(31, 13, '4102.98', '2014-12-22 11:33:06', '2014-12-22 11:33:08', 1, '0.00', '4099.98', '3.00', '1245 polaris pkwy', '', 'Columbus', 'Oh', '43240', 'US', '5-6 Business Days', 'JaQo4F7OhiuZngrFMfQN8d3YRqLHG9P9XWTJ9TuF', 1),
(32, 13, '154.49', '2014-12-22 23:32:35', '2014-12-22 23:32:37', 1, '0.00', '149.99', '4.50', '1245 polaris pkwy', '', 'Columbus', 'Oh', '43240', 'US', '5-6 Business Days', '0Dn4R42ysINOOIOM7SgRlyg6Q5SsxehKAAX0bQAd', 1),
(33, 9, '4119.99', '2014-12-23 00:08:28', '2014-12-23 00:08:30', 1, '0.00', '3999.99', '120.00', 'Add2', '', 'Col', 'OH', '98765', 'US', '5-6 Business Days', 'NYtyq3fc0H8uEvg2N4WF8RqcgVCR0byE049NSFsu', 1),
(34, 9, '1977.60', '2014-12-23 02:52:42', '2014-12-23 02:52:42', 0, '0.00', '1920.00', '57.60', 'Add2', '', 'Col', 'OH', '98765', 'US', '5-6 Business Days', '1v9c7zvYD48pDQzXdgXfSaIuWGpiSjJjFAaRWljE', 0),
(35, 9, '4882.18', '2014-12-23 03:55:08', '2014-12-23 03:55:12', 1, '0.00', '4739.98', '142.20', 'Add1', '', 'Newyork', 'OM', '4577', 'US', '5-6 Business Days', 'J4fVAWfbQLqE15PhoZrsOJXy4yLEBeNTWxqZpKHG', 2),
(36, 9, '4882.18', '2014-12-23 04:01:20', '2014-12-23 04:01:40', 1, '0.00', '4739.98', '142.20', 'Add1', '', 'Newyork', 'OM', '4577', 'US', '5-6 Business Days', 'J4fVAWfbQLqE15PhoZrsOJXy4yLEBeNTWxqZpKHG', 2),
(37, 9, '119.99', '2014-12-24 02:12:30', '2014-12-24 02:12:32', 1, '0.00', '99.99', '20.00', 'Add2', '', 'Col', 'OH', '98765', 'US', '3-5 Business Days', 'etGzX8SCpyBfV70PlZWd99ZoQOoN6EbPdISA2iFp', 1),
(38, 9, '119.99', '2014-12-24 02:20:24', '2014-12-24 02:20:24', 0, '0.00', '99.99', '20.00', 'Add1', '', 'Newyork', 'OM', '4577', 'US', '3-5 Business Days', 'LJP3lSEkKUvI35B461KWHHgyUbrVqog2Wo4hhlMD', 0),
(39, 9, '119.99', '2014-12-24 02:21:24', '2014-12-24 02:21:26', 1, '0.00', '99.99', '20.00', 'Add2', '', 'Col', 'OH', '98765', 'US', '3-5 Business Days', 'LJP3lSEkKUvI35B461KWHHgyUbrVqog2Wo4hhlMD', 1),
(40, 9, '4119.99', '2014-12-24 02:41:26', '2014-12-24 02:41:27', 1, '0.00', '3999.99', '120.00', 'Add2', '', 'Col', 'OH', '98765', 'US', '5-6 Business Days', 'xdw9OBUqoXB5TmZayagEIVQUeT041sCqoNVRzTi8', 1),
(41, 9, '111.97', '2014-12-29 06:54:10', '2014-12-29 06:54:10', 0, '8.98', '99.99', '3.00', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '5-6 Business Days', 'bHTIse8MCdHNPw90tpx7qd1oYizqNBeXzrq7xM19', 0),
(42, 9, '111.97', '2014-12-29 08:19:39', '2014-12-29 08:19:39', 0, '8.98', '99.99', '3.00', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '5-6 Business Days', 'mbOEtskX6JFfonLnt8Kgj1ZXgqrZ2VrhUF1Ej43M', 0),
(43, 9, '110.31', '2014-12-29 08:19:51', '2014-12-29 08:19:51', 0, '7.32', '99.99', '3.00', 'Add2', '', 'Col', 'OH', '98765', 'US', '5-6 Business Days', '6hfmlS6AqxhCEHrLJP0dHmcl2IFYOsdWHGLWCOcF', 0),
(44, 9, '8936.16', '2014-12-29 08:20:39', '2014-12-29 08:20:39', 0, '593.19', '8099.97', '243.00', 'Add2', '', 'Col', 'OH', '98765', 'US', '5-6 Business Days', '6hfmlS6AqxhCEHrLJP0dHmcl2IFYOsdWHGLWCOcF', 0),
(45, 9, '9182.45', '2014-12-29 23:23:43', '2014-12-29 23:23:43', 0, '736.49', '8199.96', '246.00', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '5-6 Business Days', 'DqZwCnMoSZffpj9tj1ECoEptqqR7Dg452TEwoHF0', 0),
(46, 9, '9182.45', '2014-12-29 23:25:19', '2014-12-29 23:25:19', 0, '736.49', '8199.96', '246.00', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '5-6 Business Days', '1Zo8fB5sM9gj5tjNz8O38GVK012vd3YEHf878Zyr', 0),
(47, 9, '128.52', '2014-12-30 00:22:53', '2014-12-30 00:22:53', 0, '8.53', '99.99', '20.00', 'Add2', '', 'Col', 'OH', '98765', 'US', '3-5 Business Days', '4A5NIeUohSrdtB8Pi2ISVQA8pho7Jqm78UPE6U9B', 0),
(48, 9, '111.97', '2014-12-30 02:39:32', '2014-12-30 02:39:33', 1, '8.98', '99.99', '3.00', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '5-6 Business Days', 'Cqxr0cgzlO2Ylyrz9q3CUalnYjSf2geNQzyIOiY7', 1),
(49, 9, '111.97', '2014-12-30 03:30:23', '2014-12-30 03:30:24', 1, '8.98', '99.99', '3.00', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '5-6 Business Days', 'LZ6iEVAIfQvD2S66rmtqpZshl35yOADoeFWNW1ki', 1),
(50, 9, '4479.25', '2014-12-30 03:30:46', '2014-12-30 03:30:47', 1, '359.26', '3999.99', '120.00', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '5-6 Business Days', 'LZ6iEVAIfQvD2S66rmtqpZshl35yOADoeFWNW1ki', 1),
(51, 9, '167.96', '2014-12-30 03:31:13', '2014-12-30 03:31:15', 1, '13.47', '149.99', '4.50', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '5-6 Business Days', 'UvrbwOgMZNHFBrdlnZfzqyVbA6OEjosh63D6Iztq', 1),
(52, 9, '111.97', '2014-12-30 03:47:40', '2014-12-30 03:47:41', 1, '8.98', '99.99', '3.00', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '5-6 Business Days', 'Z8W5wSwCaehRKeuz8SxmXWWAt8gQjUw9BNHStd5N', 1),
(53, 9, '167.96', '2014-12-30 03:58:03', '2014-12-30 03:58:03', 0, '13.47', '149.99', '4.50', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '5-6 Business Days', 'pvQ4j1HcFqPudYYhEwpvTaxIl23XNgabybNPJCbO', 0),
(54, 9, '2255.39', '2014-12-30 03:58:31', '2014-12-30 03:58:32', 1, '180.90', '2069.99', '4.50', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '5-6 Business Days', 'pvQ4j1HcFqPudYYhEwpvTaxIl23XNgabybNPJCbO', 1),
(55, 9, '111.97', '2014-12-30 04:07:48', '2014-12-30 04:07:49', 1, '8.98', '99.99', '3.00', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '5-6 Business Days', 'BdRKLmTyxpD8dA3fN4ioMTnvFQhrSkmlLbQMYpie', 1),
(56, 9, '329.39', '2014-12-30 04:15:33', '2014-12-30 04:15:39', 1, '26.42', '299.97', '3.00', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '5-6 Business Days', 'BAuZvPv2tokX4rglNlGOHiu8yhtFsKfXHuL5LKAd', 1),
(57, 9, '940.65', '2014-12-30 04:30:43', '2014-12-30 04:30:44', 1, '75.45', '840.00', '25.20', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '5-6 Business Days', 'v6t8cxeuHKLF0an8PdsEPFVIcnvSR9vttBl5SX1A', 1),
(58, 9, '130.45', '2014-12-30 06:36:13', '2014-12-30 06:36:14', 1, '10.46', '99.99', '20.00', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '3-5 Business Days', 'IeGGkp0ljJcCAwVoDtA8Kzw5yx5Ygv6DwRwX4V59', 1),
(59, 9, '130.45', '2014-12-30 06:54:00', '2014-12-30 06:54:03', 1, '10.46', '99.99', '20.00', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '3-5 Business Days', 'o73cb5zGqTvLEabTN3rR1BWT0Y9UHpubu9yOjznB', 1),
(60, 13, '816.38', '2014-12-30 16:22:51', '2014-12-30 16:22:53', 1, '54.19', '739.99', '22.20', '1245 polaris pkwy', '', 'Columbus', 'Oh', '43240', 'US', '5-6 Business Days', '5x5pvb63Ku8Rzw03yUQyVliPXV6XnYgA3Jc4c9bQ', 1),
(61, 13, '220.65', '2014-12-30 17:51:49', '2014-12-30 17:51:51', 1, '14.65', '200.00', '6.00', '1245 polaris pkwy', '', 'Columbus', 'Oh', '43240', 'US', '5-6 Business Days', 'M8pGwVwWXTCMMGhSh20bx1rOtDiKZ16GjisKf0w1', 1),
(62, 13, '110.31', '2014-12-30 18:06:42', '2014-12-30 18:06:44', 1, '7.32', '99.99', '3.00', '1245 polaris pkwy', '', 'Columbus', 'Oh', '43240', 'US', '5-6 Business Days', 'M8pGwVwWXTCMMGhSh20bx1rOtDiKZ16GjisKf0w1', 1),
(63, 13, '110.31', '2014-12-30 18:10:30', '2014-12-30 18:10:32', 1, '7.32', '99.99', '3.00', '1245 polaris pkwy', '', 'Columbus', 'Oh', '43240', 'US', '5-6 Business Days', 'M8pGwVwWXTCMMGhSh20bx1rOtDiKZ16GjisKf0w1', 1),
(64, 13, '706.07', '2014-12-30 18:11:33', '2014-12-30 18:11:36', 1, '46.87', '640.00', '19.20', '1245 polaris pkwy', '', 'Columbus', 'Oh', '43240', 'US', '5-6 Business Days', 'M8pGwVwWXTCMMGhSh20bx1rOtDiKZ16GjisKf0w1', 1),
(65, 13, '110.31', '2014-12-31 10:49:52', '2014-12-31 10:49:52', 0, '7.32', '99.99', '3.00', '1245 polaris pkwy', '', 'Columbus', 'Oh', '43240', 'US', '5-6 Business Days', 'l5xXRD6LjFADbC5NcowKMIdWKp6Z5Fg1uVONX6NN', 0),
(66, 13, '110.31', '2014-12-31 10:54:18', '2014-12-31 10:54:18', 0, '7.32', '99.99', '3.00', '1245 polaris pkwy', '', 'Columbus', 'Oh', '43240', 'US', '5-6 Business Days', 'l5xXRD6LjFADbC5NcowKMIdWKp6Z5Fg1uVONX6NN', 0),
(67, 13, '110.31', '2014-12-31 10:58:01', '2014-12-31 10:58:01', 0, '7.32', '99.99', '3.00', '1245 polaris pkwy', '', 'Columbus', 'Oh', '43240', 'US', '5-6 Business Days', 'l5xXRD6LjFADbC5NcowKMIdWKp6Z5Fg1uVONX6NN', 0),
(68, 13, '110.31', '2014-12-31 11:00:01', '2014-12-31 11:00:01', 0, '7.32', '99.99', '3.00', '1245 polaris pkwy', '', 'Columbus', 'Oh', '43240', 'US', '5-6 Business Days', 'l5xXRD6LjFADbC5NcowKMIdWKp6Z5Fg1uVONX6NN', 0),
(69, 13, '4523.23', '2014-12-31 11:15:36', '2014-12-31 11:15:36', 0, '300.25', '4099.98', '123.00', '1245 polaris pkwy', '', 'Columbus', 'Oh', '43240', 'US', '5-6 Business Days', 'l5xXRD6LjFADbC5NcowKMIdWKp6Z5Fg1uVONX6NN', 0),
(70, 13, '4523.23', '2014-12-31 11:31:33', '2014-12-31 11:31:33', 0, '300.25', '4099.98', '123.00', '1245 polaris pkwy', '', 'Columbus', 'Oh', '43240', 'US', '5-6 Business Days', 'l5xXRD6LjFADbC5NcowKMIdWKp6Z5Fg1uVONX6NN', 0),
(71, 13, '4523.23', '2014-12-31 11:32:38', '2014-12-31 11:32:46', 0, '300.25', '4099.98', '123.00', '1245 polaris pkwy', '', 'Columbus', 'Oh', '43240', 'US', '5-6 Business Days', 'l5xXRD6LjFADbC5NcowKMIdWKp6Z5Fg1uVONX6NN', 0),
(72, 13, '4523.23', '2014-12-31 11:45:46', '2014-12-31 11:45:46', 0, '300.25', '4099.98', '123.00', '1245 polaris pkwy', '', 'Columbus', 'Oh', '43240', 'US', '5-6 Business Days', 'l5xXRD6LjFADbC5NcowKMIdWKp6Z5Fg1uVONX6NN', 0),
(73, 9, '1545.34', '2015-01-02 01:15:24', '2015-01-02 01:15:24', 0, '123.95', '1379.99', '41.40', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '5-6 Business Days', 'IcI3PkrbSblnBY18vB9VMUWrvNrG6kPGOthH9Mgf', 0),
(74, 9, '1545.34', '2015-01-02 01:20:52', '2015-01-02 01:20:52', 0, '123.95', '1379.99', '41.40', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '5-6 Business Days', 'tFii1dVD8T0DVDn6kaHusvA90VyBhAT1g57qhFfU', 0),
(75, 9, '2217.16', '2015-01-02 01:21:55', '2015-01-02 01:21:55', 0, '177.83', '1979.93', '59.40', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '5-6 Business Days', '5YPUDQvfaaJAbR0XTRwfzaCHdjaTPRypPqqso5Rp', 0),
(76, 9, '2217.16', '2015-01-02 01:23:38', '2015-01-02 01:23:38', 0, '177.83', '1979.93', '59.40', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '5-6 Business Days', 'E5BgGBihGt9PZRxwBGOjiAGL8mJLcVvkkZwLqOer', 0),
(77, 9, '2385.12', '2015-01-02 01:25:56', '2015-01-02 01:25:56', 0, '191.30', '2129.92', '63.90', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '5-6 Business Days', '9hGA4qmf5NzRdprObfJtl0exeUdrBqP3qfHhMifr', 0),
(78, 9, '2217.16', '2015-01-02 01:27:32', '2015-01-02 01:29:23', 0, '177.83', '1979.93', '59.40', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '5-6 Business Days', 'RFwiJT8xtA4PJtHCsqYXJMEnspjsD6ApmzdMAKhd', 0),
(79, 9, '2385.12', '2015-01-02 01:30:44', '2015-01-02 01:30:44', 0, '191.30', '2129.92', '63.90', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '5-6 Business Days', '6RE6ReUrUMW7IMUEidSIIgI6T3fDh0hg662D4Lbk', 0),
(80, 9, '2553.08', '2015-01-02 01:32:36', '2015-01-02 01:32:36', 0, '204.77', '2279.91', '68.40', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '5-6 Business Days', 'bfoZ0F3fCpXdhgWMY9h8EEZw5Oqcf49Pbqdm202t', 0),
(81, 9, '2665.05', '2015-01-02 01:34:58', '2015-01-02 01:34:58', 0, '213.75', '2379.90', '71.40', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '5-6 Business Days', 'AZQTJkCcnLBetKlfm7Z8LG9BB31Yx8IUNkABFkTP', 0),
(82, 9, '2665.05', '2015-01-02 01:36:35', '2015-01-02 01:36:35', 0, '213.75', '2379.90', '71.40', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '5-6 Business Days', 'Yw6cwPthnBtq43g3YRMs1b8Epr0yhhOFk3iankqa', 0),
(83, 9, '2665.05', '2015-01-02 01:38:04', '2015-01-02 01:38:04', 0, '213.75', '2379.90', '71.40', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '5-6 Business Days', 'YxUryRRhEQC5IS39wSKQnQQwxP9WgtAoGTrUCAeP', 0),
(84, 9, '2665.05', '2015-01-02 01:39:28', '2015-01-02 01:39:47', 0, '213.75', '2379.90', '71.40', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '5-6 Business Days', 'btxjC8PSoTdg0XH8emkw9W6VXDiwZlWgaGQJdDLZ', 1),
(85, 9, '2735.90', '2015-01-02 01:57:03', '2015-01-02 01:57:03', 0, '181.61', '2479.89', '74.40', 'Add2', '', 'Col', 'OH', '98765', 'US', '5-6 Business Days', '4FrluyWleOvFVAi6K9itrBSetR2KvJB6OMTa0BA2', 0),
(86, 9, '716.68', '2015-01-02 01:58:40', '2015-01-02 02:02:00', 0, '57.48', '640.00', '19.20', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '5-6 Business Days', 'S0TrOEfkjiZ2jffWHz7gdGe9dOumvlq5nB5aZv4E', 0),
(87, 9, '111.97', '2015-01-02 02:01:30', '2015-01-02 02:01:30', 0, '8.98', '99.99', '3.00', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '5-6 Business Days', 'S0TrOEfkjiZ2jffWHz7gdGe9dOumvlq5nB5aZv4E', 0),
(88, 9, '828.65', '2015-01-02 02:08:45', '2015-01-02 02:08:49', 1, '66.46', '739.99', '22.20', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '5-6 Business Days', 'tBlY4dJJ99ay78yuq9b9dRwKmU1Kv1PMUPbR4hXa', 1),
(89, 9, '816.38', '2015-01-02 02:09:14', '2015-01-02 02:09:14', 0, '54.19', '739.99', '22.20', 'Add1', '', 'Newyork', 'OM', '4577', 'US', '5-6 Business Days', 'tBlY4dJJ99ay78yuq9b9dRwKmU1Kv1PMUPbR4hXa', 0),
(90, 9, '111.97', '2015-01-02 05:28:24', '2015-01-02 05:28:31', 0, '8.98', '99.99', '3.00', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '5-6 Business Days', 'mckQOumgRwpxmk3RBkfKLVB56CbLw6UKw8KgIhfE', 0),
(91, 9, '111.97', '2015-01-02 05:29:11', '2015-01-02 05:29:11', 0, '8.98', '99.99', '3.00', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '5-6 Business Days', 'mckQOumgRwpxmk3RBkfKLVB56CbLw6UKw8KgIhfE', 0),
(92, 9, '5782.15', '2015-01-02 05:34:34', '2015-01-02 05:34:34', 0, '463.76', '5279.99', '38.40', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '5-6 Business Days', '43kSfjrVv3xdRUGn9YThJCwkuqq4uebwMiZyFR0X', 0),
(93, 9, '607.69', '2015-01-02 05:37:01', '2015-01-02 05:37:55', 0, '48.74', '549.95', '9.00', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '5-6 Business Days', 'DFxxmVfkQyGqLBDBZWwZrUSvRNkKuPrtCfrZ05Vw', 0),
(94, 6, '5513.92', '2015-01-02 07:40:15', '2015-01-02 07:40:15', 0, '415.52', '4949.90', '148.50', 'hi', '', 'adf', 'TX', 'hjgjh', 'US', '5-6 Business Days', 'cSrKuzXGWpsnmNUEHh5uJ6WO3BAXVmxjk9iUcWSq', 0),
(95, 9, '1279.51', '2015-01-02 07:49:35', '2015-01-02 07:49:35', 0, '102.62', '1149.89', '27.00', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '5-6 Business Days', 'dtTIIYTxtEa6MMm3aYdhWsQOjI4DaYFHV3cNbxoY', 0),
(96, 13, '110.31', '2015-01-02 10:03:09', '2015-01-05 00:25:28', 0, '7.32', '99.99', '3.00', '1245 polaris pkwy', '', 'Columbus', 'Oh', '43240', 'US', '5-6 Business Days', 'aW8w9MGwyVzAGdWfKjaA57kjAcWoSriwJhmWThOX', 0),
(97, 16, '4102.98', '2015-01-02 10:38:19', '2015-01-02 15:26:11', 0, '0.00', '4099.98', '3.00', '296 s. Merkle rd', '', 'Bexley', 'Ohio', '43209', 'US', '5-6 Business Days', 'PqXP1NOK9D1cK1yGY4VW1N98LxuIRZP86jW2FA2R', 0),
(98, 16, '254.48', '2015-01-02 11:51:16', '2015-01-02 16:24:07', 0, '0.00', '249.98', '4.50', '296 s. Merkle rd', '', 'Bexley', 'Ohio', '43209', 'US', '5-6 Business Days', 'PqXP1NOK9D1cK1yGY4VW1N98LxuIRZP86jW2FA2R', 0),
(99, 13, '110.31', '2015-01-02 11:53:01', '2015-01-02 11:53:01', 0, '7.32', '99.99', '3.00', '1245 polaris pkwy', '', 'Columbus', 'Oh', '43240', 'US', '5-6 Business Days', 'PmI3bA9PWkSZ44IB8XzjTZQ1ulWfWAsbhXIGTmr3', 0),
(100, 16, '154.49', '2015-01-02 11:53:35', '2015-01-02 11:53:35', 0, '0.00', '149.99', '4.50', '296 s. Merkle rd', '', 'Bexley', 'Ohio', '43209', 'US', '5-6 Business Days', 'xmnWIt4a93VwjQO0bCoHh1keTUqMdaNhODsje2V0', 0),
(101, 16, '6406.57', '2015-01-02 15:27:46', '2015-01-02 15:27:49', 1, '0.00', '6219.97', '186.60', '296 s. Merkle rd', '', 'Bexley', 'Ohio', '43209', 'US', '5-6 Business Days', 'PqXP1NOK9D1cK1yGY4VW1N98LxuIRZP86jW2FA2R', 1),
(102, 13, '110.31', '2015-01-02 15:42:29', '2015-01-02 15:42:30', 1, '7.32', '99.99', '3.00', '1245 polaris pkwy', '', 'Columbus', 'Oh', '43240', 'US', '5-6 Business Days', 'PmI3bA9PWkSZ44IB8XzjTZQ1ulWfWAsbhXIGTmr3', 1),
(103, 13, '110.31', '2015-01-02 15:44:48', '2015-01-02 15:44:49', 1, '7.32', '99.99', '3.00', '1245 polaris pkwy', '', 'Columbus', 'Oh', '43240', 'US', '5-6 Business Days', 'PmI3bA9PWkSZ44IB8XzjTZQ1ulWfWAsbhXIGTmr3', 1),
(104, 13, '110.31', '2015-01-02 15:45:30', '2015-01-02 15:45:31', 1, '7.32', '99.99', '3.00', '1245 polaris pkwy', '', 'Columbus', 'Oh', '43240', 'US', '5-6 Business Days', 'PmI3bA9PWkSZ44IB8XzjTZQ1ulWfWAsbhXIGTmr3', 1),
(105, 13, '110.31', '2015-01-02 15:46:10', '2015-01-02 15:46:11', 1, '7.32', '99.99', '3.00', '1245 polaris pkwy', '', 'Columbus', 'Oh', '43240', 'US', '5-6 Business Days', 'PmI3bA9PWkSZ44IB8XzjTZQ1ulWfWAsbhXIGTmr3', 1),
(106, 13, '110.31', '2015-01-02 16:05:18', '2015-01-02 16:32:36', 1, '7.32', '99.99', '3.00', '1245 polaris pkwy', '', 'Columbus', 'Oh', '43240', 'US', '5-6 Business Days', 'PmI3bA9PWkSZ44IB8XzjTZQ1ulWfWAsbhXIGTmr3', 1),
(107, 16, '6712.56', '2015-01-02 16:22:26', '2015-01-02 16:23:49', 0, '0.00', '6519.96', '192.60', '296 s. Merkle rd', '', 'Bexley', 'Ohio', '43209', 'US', '5-6 Business Days', 'PqXP1NOK9D1cK1yGY4VW1N98LxuIRZP86jW2FA2R', 0),
(108, 16, '494.99', '2015-01-04 08:40:07', '2015-01-04 08:40:07', 0, '0.00', '299.99', '195.00', '296 s. Merkle rd', '', 'Bexley', 'Ohio', '43209', 'US', '5-6 Business Days', 'PqXP1NOK9D1cK1yGY4VW1N98LxuIRZP86jW2FA2R', 0),
(109, 16, '7262.90', '2015-01-04 08:42:14', '2015-01-04 08:43:55', 0, '0.00', '7259.90', '3.00', '296 s. Merkle rd', '', 'Bexley', 'Ohio', '43209', 'US', '5-6 Business Days', 'PqXP1NOK9D1cK1yGY4VW1N98LxuIRZP86jW2FA2R', 1),
(110, 13, '110.31', '2015-01-04 23:28:04', '2015-01-04 23:28:04', 0, '7.32', '99.99', '3.00', '1245 polaris pkwy', '', 'Columbus', 'Oh', '43240', 'US', '5-6 Business Days', 'aW8w9MGwyVzAGdWfKjaA57kjAcWoSriwJhmWThOX', 0),
(111, 13, '795.82', '2015-01-04 23:31:37', '2015-01-04 23:31:37', 0, '52.83', '739.99', '3.00', '1245 polaris pkwy', '', 'Columbus', 'Oh', '43240', 'US', '5-6 Business Days', 'aW8w9MGwyVzAGdWfKjaA57kjAcWoSriwJhmWThOX', 0),
(112, 13, '706.07', '2015-01-04 23:32:32', '2015-01-04 23:32:32', 0, '46.87', '640.00', '19.20', '1245 polaris pkwy', '', 'Columbus', 'Oh', '43240', 'US', '5-6 Business Days', 'aW8w9MGwyVzAGdWfKjaA57kjAcWoSriwJhmWThOX', 0),
(113, 9, '111.97', '2015-01-05 00:28:41', '2015-01-05 00:28:45', 0, '8.98', '99.99', '3.00', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '5-6 Business Days', 'C42y6K9E3PTHlzgXhd1r5Pepa3EELABjUiP8pcMc', 0),
(114, 13, '110.31', '2015-01-05 00:38:05', '2015-01-05 00:38:05', 0, '7.32', '99.99', '3.00', '1245 polaris pkwy', '', 'Columbus', 'Oh', '43240', 'US', '5-6 Business Days', 'JrMrK8tkT4MQll2VaDRkImTEFWZ7vyd5MYdV3vhk', 0),
(115, 13, '706.07', '2015-01-05 00:53:07', '2015-01-05 00:53:15', 0, '46.87', '640.00', '19.20', '1245 polaris pkwy', '', 'Columbus', 'Oh', '43240', 'US', '5-6 Business Days', 'SSERZGVrxXCYUDviu2q9SCXTZtyG4m27HtRW9g4O', 0),
(116, 9, '111.97', '2015-01-05 00:57:44', '2015-01-05 01:00:18', 0, '8.98', '99.99', '3.00', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '5-6 Business Days', '3CaozOYUK4t4ECOG02Ui9wVL74KUhEvjjyVJYBnS', 0),
(117, 6, '1047.09', '2015-01-05 01:18:39', '2015-01-05 01:18:39', 0, '78.91', '939.98', '28.20', 'hi', '', 'adf', 'TX', 'hjgjh', 'US', '5-6 Business Days', 'hRfIrdF27JFKDUrDBpnFYSUR2jQq1n1MHqRHI4LJ', 0),
(118, 6, '128.90', '2015-01-05 01:19:33', '2015-01-05 01:19:48', 0, '9.71', '99.99', '19.20', 'hi', '', 'adf', 'TX', 'hjgjh', 'US', '5-6 Business Days', 'hRfIrdF27JFKDUrDBpnFYSUR2jQq1n1MHqRHI4LJ', 0);

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE IF NOT EXISTS `order_details` (
  `order_details_id` int(10) NOT NULL AUTO_INCREMENT,
  `order_id` int(10) NOT NULL,
  `prod_id` int(10) NOT NULL,
  `prod_name` varchar(256) NOT NULL,
  `prod_price` decimal(10,2) NOT NULL,
  `prod_quantity` decimal(10,2) NOT NULL,
  `prod_attribs` text NOT NULL,
  PRIMARY KEY (`order_details_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=244 ;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`order_details_id`, `order_id`, `prod_id`, `prod_name`, `prod_price`, `prod_quantity`, `prod_attribs`) VALUES
(1, 1, 6095, 'Sofa 2', '3999.99', '1.00', ''),
(2, 2, 6095, 'Sofa 2', '3999.99', '1.00', ''),
(3, 2, 5892, 'Curtains', '149.99', '1.00', '{"color":"#90EE90","size":"4'' X 6''"}'),
(4, 3, 5892, 'Curtains', '149.99', '1.00', '{"color":"#90EE90","size":"4'' X 6''"}'),
(5, 3, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#C0C0C0","size":"Double"}'),
(6, 3, 5892, 'Curtains', '149.99', '1.00', '{"color":"#808000","size":"4'' X 6''"}'),
(7, 4, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#A52A2A","size":"Single"}'),
(8, 5, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#A52A2A","size":"Single"}'),
(9, 6, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#A52A2A","size":"Single"}'),
(10, 6, 6087, 'Curtain 2', '640.00', '1.00', ''),
(11, 7, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#A52A2A","size":"Single"}'),
(12, 7, 6087, 'Curtain 2', '640.00', '1.00', ''),
(13, 7, 6087, 'Curtain 2', '640.00', '1.00', ''),
(14, 8, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#A52A2A","size":"Single"}'),
(15, 8, 6087, 'Curtain 2', '640.00', '1.00', ''),
(16, 8, 6087, 'Curtain 2', '640.00', '1.00', ''),
(17, 8, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#A52A2A","size":"Single"}'),
(18, 9, 5892, 'Curtains', '149.99', '1.00', '{"color":"#808000","size":"4'' X 6''"}'),
(19, 9, 5892, 'Curtains', '149.99', '1.00', '{"color":"#808000","size":"6'' X 8''"}'),
(20, 10, 5892, 'Curtains', '149.99', '1.00', '{"color":"#90EE90","size":"4'' X 6''"}'),
(21, 11, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#C0C0C0","size":"Single"}'),
(22, 12, 6087, 'Curtain 2', '640.00', '1.00', ''),
(23, 13, 6095, 'Sofa 2', '3999.99', '1.00', ''),
(24, 14, 6108, 'Glass 1', '200.00', '1.00', ''),
(25, 13, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#A52A2A","size":"Single"}'),
(26, 15, 6087, 'Curtain 2', '640.00', '1.00', ''),
(27, 16, 6095, 'Sofa 2', '3999.99', '1.00', ''),
(28, 16, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Queen"}'),
(29, 15, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Queen"}'),
(30, 17, 5892, 'Curtains', '149.99', '1.00', '{"color":"#90EE90","size":"6'' X 8''"}'),
(31, 17, 6094, 'Chair 2', '640.00', '1.00', ''),
(32, 18, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#C0C0C0","size":"Single"}'),
(33, 18, 5892, 'Curtains', '149.99', '1.00', '{"color":"#90EE90","size":"4'' X 6''"}'),
(34, 18, 5892, 'Curtains', '149.99', '1.00', '{"color":"#808000","size":"6'' X 8''"}'),
(35, 19, 5892, 'Curtains', '149.99', '1.00', '{"color":"#90EE90","size":"4'' X 6''"}'),
(36, 20, 5892, 'Curtains', '149.99', '1.00', '{"color":"#808000","size":"6'' X 8''"}'),
(37, 21, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#C0C0C0","size":"Single"}'),
(38, 22, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#C0C0C0","size":"Double"}'),
(39, 23, 5892, 'Curtains', '149.99', '1.00', '{"color":"#90EE90","size":"6'' X 8''"}'),
(40, 24, 6095, 'Sofa 2', '3999.99', '1.00', ''),
(41, 24, 5891, 'Lime Bedding', '99.99', '2.00', '{"color":"#BA55D3","size":"Double"}'),
(42, 25, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Double"}'),
(43, 25, 6095, 'Sofa 2', '3999.99', '1.00', ''),
(44, 25, 6095, 'Sofa 2', '3999.99', '1.00', ''),
(45, 26, 6095, 'Sofa 2', '3999.99', '5.00', ''),
(46, 27, 6095, 'Sofa 2', '3999.99', '1.00', ''),
(47, 28, 6095, 'Sofa 2', '3999.99', '1.00', ''),
(48, 28, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(49, 29, 6095, 'Sofa 2', '3999.99', '2.00', ''),
(50, 30, 6095, 'Sofa 2', '3999.99', '2.00', ''),
(51, 31, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(52, 31, 6095, 'Sofa 2', '3999.99', '1.00', ''),
(53, 32, 5892, 'Curtains', '149.99', '1.00', '{"color":"#808000","size":"4'' X 6''"}'),
(54, 33, 6095, 'Sofa 2', '3999.99', '1.00', ''),
(55, 34, 6087, 'Curtain 2', '640.00', '3.00', ''),
(56, 35, 6094, 'Chair 2', '640.00', '1.00', ''),
(57, 35, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(58, 35, 6095, 'Sofa 2', '3999.99', '1.00', ''),
(59, 36, 6094, 'Chair 2', '640.00', '1.00', ''),
(60, 36, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(61, 36, 6095, 'Sofa 2', '3999.99', '1.00', ''),
(62, 37, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(63, 38, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(64, 39, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(65, 40, 6095, 'Sofa 2', '3999.99', '1.00', ''),
(66, 41, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(67, 42, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#C0C0C0","size":"Single"}'),
(68, 43, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(69, 44, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(70, 44, 6095, 'Sofa 2', '3999.99', '2.00', ''),
(71, 45, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(72, 45, 6095, 'Sofa 2', '3999.99', '2.00', ''),
(73, 45, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#A52A2A","size":"Single"}'),
(74, 46, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(75, 46, 6095, 'Sofa 2', '3999.99', '2.00', ''),
(76, 46, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#C0C0C0","size":"Queen"}'),
(77, 47, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#A52A2A","size":"Single"}'),
(78, 48, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(79, 49, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(80, 50, 6095, 'Sofa 2', '3999.99', '1.00', ''),
(81, 51, 5892, 'Curtains', '149.99', '1.00', '{"color":"#808000","size":"4'' X 6''"}'),
(82, 52, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(83, 53, 5892, 'Curtains', '149.99', '1.00', '{"color":"#90EE90","size":"4'' X 6''"}'),
(84, 54, 5892, 'Curtains', '149.99', '1.00', '{"color":"#90EE90","size":"4'' X 6''"}'),
(85, 54, 6094, 'Chair 2', '640.00', '3.00', ''),
(86, 55, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(87, 56, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#A52A2A","size":"Single"}'),
(88, 56, 5891, 'Lime Bedding', '99.99', '2.00', '{"color":"#A52A2A","size":"Queen"}'),
(89, 57, 6108, 'Glass 1', '200.00', '1.00', ''),
(90, 57, 6087, 'Curtain 2', '640.00', '1.00', ''),
(91, 58, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(92, 59, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(93, 60, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#C0C0C0","size":"Double"}'),
(94, 60, 6094, 'Chair 2', '640.00', '1.00', ''),
(95, 61, 6108, 'Glass 1', '200.00', '1.00', ''),
(96, 62, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(97, 63, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(98, 64, 6094, 'Chair 2', '640.00', '1.00', ''),
(99, 65, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(100, 66, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(101, 67, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(102, 68, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(103, 69, 6095, 'Sofa 2', '3999.99', '1.00', ''),
(104, 69, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(105, 70, 6095, 'Sofa 2', '3999.99', '1.00', ''),
(106, 70, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(107, 71, 6095, 'Sofa 2', '3999.99', '1.00', ''),
(108, 71, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(109, 72, 6095, 'Sofa 2', '3999.99', '1.00', ''),
(110, 72, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(111, 73, 6087, 'Curtain 2', '640.00', '1.00', ''),
(112, 73, 6094, 'Chair 2', '640.00', '1.00', ''),
(113, 73, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#A52A2A","size":"Single"}'),
(114, 74, 6087, 'Curtain 2', '640.00', '1.00', ''),
(115, 74, 6094, 'Chair 2', '640.00', '1.00', ''),
(116, 74, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#A52A2A","size":"Single"}'),
(117, 75, 6087, 'Curtain 2', '640.00', '1.00', ''),
(118, 75, 6094, 'Chair 2', '640.00', '1.00', ''),
(119, 75, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#A52A2A","size":"Single"}'),
(120, 75, 5891, 'Lime Bedding', '99.99', '6.00', '{"color":"#C0C0C0","size":"Single"}'),
(121, 76, 6087, 'Curtain 2', '640.00', '1.00', ''),
(122, 76, 6094, 'Chair 2', '640.00', '1.00', ''),
(123, 76, 5891, 'Lime Bedding', '99.99', '6.00', '{"color":"#C0C0C0","size":"Single"}'),
(124, 76, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#A52A2A","size":"Single"}'),
(125, 77, 6087, 'Curtain 2', '640.00', '1.00', ''),
(126, 77, 6094, 'Chair 2', '640.00', '1.00', ''),
(127, 77, 5891, 'Lime Bedding', '99.99', '6.00', '{"color":"#C0C0C0","size":"Single"}'),
(128, 77, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#A52A2A","size":"Single"}'),
(129, 77, 5892, 'Curtains', '149.99', '1.00', '{"color":"#808000","size":"4'' X 6''"}'),
(130, 78, 6094, 'Chair 2', '640.00', '1.00', ''),
(131, 78, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#C0C0C0","size":"Single"}'),
(132, 78, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#A52A2A","size":"Single"}'),
(133, 78, 6087, 'Curtain 2', '640.00', '1.00', ''),
(134, 79, 6094, 'Chair 2', '640.00', '1.00', ''),
(135, 79, 5891, 'Lime Bedding', '99.99', '6.00', '{"color":"#C0C0C0","size":"Single"}'),
(136, 79, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#A52A2A","size":"Single"}'),
(137, 79, 6087, 'Curtain 2', '640.00', '1.00', ''),
(138, 79, 5892, 'Curtains', '149.99', '1.00', '{"color":"#808000","size":"4'' X 6''"}'),
(139, 80, 6094, 'Chair 2', '640.00', '1.00', ''),
(140, 80, 5891, 'Lime Bedding', '99.99', '6.00', '{"color":"#C0C0C0","size":"Single"}'),
(141, 80, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#A52A2A","size":"Single"}'),
(142, 80, 6087, 'Curtain 2', '640.00', '1.00', ''),
(143, 80, 5892, 'Curtains', '149.99', '1.00', '{"color":"#808000","size":"4'' X 6''"}'),
(144, 80, 5892, 'Curtains', '149.99', '1.00', '{"color":"#90EE90","size":"4'' X 6''"}'),
(145, 81, 6094, 'Chair 2', '640.00', '1.00', ''),
(146, 81, 5891, 'Lime Bedding', '99.99', '6.00', '{"color":"#C0C0C0","size":"Single"}'),
(147, 81, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#A52A2A","size":"Single"}'),
(148, 81, 6087, 'Curtain 2', '640.00', '1.00', ''),
(149, 81, 5892, 'Curtains', '149.99', '1.00', '{"color":"#808000","size":"4'' X 6''"}'),
(150, 81, 5892, 'Curtains', '149.99', '1.00', '{"color":"#90EE90","size":"4'' X 6''"}'),
(151, 81, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#C0C0C0","size":"Single"}'),
(152, 82, 6094, 'Chair 2', '640.00', '1.00', ''),
(153, 82, 5891, 'Lime Bedding', '99.99', '6.00', '{"color":"#C0C0C0","size":"Single"}'),
(154, 82, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#A52A2A","size":"Single"}'),
(155, 82, 5892, 'Curtains', '149.99', '1.00', '{"color":"#808000","size":"4'' X 6''"}'),
(156, 82, 5892, 'Curtains', '149.99', '1.00', '{"color":"#90EE90","size":"4'' X 6''"}'),
(157, 82, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#C0C0C0","size":"Single"}'),
(158, 82, 6087, 'Curtain 2', '640.00', '1.00', ''),
(159, 83, 6094, 'Chair 2', '640.00', '1.00', ''),
(160, 83, 5891, 'Lime Bedding', '99.99', '6.00', '{"color":"#C0C0C0","size":"Single"}'),
(161, 83, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#A52A2A","size":"Single"}'),
(162, 83, 5892, 'Curtains', '149.99', '1.00', '{"color":"#90EE90","size":"4'' X 6''"}'),
(163, 83, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#C0C0C0","size":"Single"}'),
(164, 83, 6087, 'Curtain 2', '640.00', '1.00', ''),
(165, 83, 5892, 'Curtains', '149.99', '1.00', '{"color":"#808000","size":"4'' X 6''"}'),
(166, 84, 6094, 'Chair 2', '640.00', '1.00', ''),
(167, 84, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#C0C0C0","size":"Single"}'),
(168, 84, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#A52A2A","size":"Single"}'),
(169, 84, 5892, 'Curtains', '149.99', '1.00', '{"color":"#90EE90","size":"4'' X 6''"}'),
(170, 84, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#C0C0C0","size":"Single"}'),
(171, 84, 5892, 'Curtains', '149.99', '1.00', '{"color":"#808000","size":"4'' X 6''"}'),
(172, 84, 6087, 'Curtain 2', '640.00', '1.00', ''),
(173, 85, 6094, 'Chair 2', '640.00', '1.00', ''),
(174, 85, 5891, 'Lime Bedding', '99.99', '6.00', '{"color":"#C0C0C0","size":"Single"}'),
(175, 85, 5892, 'Curtains', '149.99', '1.00', '{"color":"#90EE90","size":"4'' X 6''"}'),
(176, 85, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#C0C0C0","size":"Single"}'),
(177, 85, 5892, 'Curtains', '149.99', '1.00', '{"color":"#808000","size":"4'' X 6''"}'),
(178, 85, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(179, 85, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#A52A2A","size":"Single"}'),
(180, 85, 6087, 'Curtain 2', '640.00', '1.00', ''),
(181, 86, 6087, 'Curtain 2', '640.00', '1.00', ''),
(182, 87, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#A52A2A","size":"Single"}'),
(183, 88, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(184, 88, 6094, 'Chair 2', '640.00', '1.00', ''),
(185, 89, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(186, 89, 6094, 'Chair 2', '640.00', '1.00', ''),
(187, 90, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(188, 91, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(189, 92, 6094, 'Chair 2', '640.00', '2.00', ''),
(190, 92, 6095, 'Sofa 2', '3999.99', '1.00', ''),
(191, 93, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#A52A2A","size":"Single"}'),
(192, 93, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#A52A2A","size":"Double"}'),
(193, 93, 5892, 'Curtains', '149.99', '1.00', '{"color":"#808000","size":"4'' X 6''"}'),
(194, 94, 5891, 'Lime Bedding', '99.99', '7.00', '{"color":"#A52A2A","size":"Single"}'),
(195, 94, 6095, 'Sofa 2', '3999.99', '1.00', ''),
(196, 94, 5892, 'Curtains', '149.99', '1.00', '{"color":"#808000","size":"4'' X 6''"}'),
(197, 94, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(198, 95, 5891, 'Lime Bedding', '99.99', '9.00', '{"color":"#A52A2A","size":"Single"}'),
(199, 95, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#A52A2A","size":"Double"}'),
(200, 95, 5892, 'Curtains', '149.99', '1.00', '{"color":"#808000","size":"6'' X 8''"}'),
(201, 96, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(202, 97, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#A52A2A","size":"Single"}'),
(203, 98, 5892, 'Curtains', '149.99', '1.00', '{"color":"#808000","size":"4'' X 6''"}'),
(204, 99, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(205, 100, 5892, 'Curtains', '149.99', '1.00', '{"color":"#808000","size":"4'' X 6''"}'),
(206, 97, 6095, 'Sofa 2', '3999.99', '1.00', ''),
(207, 101, 5892, 'Curtains', '149.99', '1.00', '{"color":"#808000","size":"4'' X 6''"}'),
(208, 101, 5892, 'Curtains', '149.99', '1.00', '{"color":"#90EE90","size":"6'' X 8''"}'),
(209, 101, 6095, 'Sofa 2', '3999.99', '1.00', ''),
(210, 101, 6094, 'Chair 2', '640.00', '1.00', ''),
(211, 101, 6087, 'Curtain 2', '640.00', '2.00', ''),
(212, 102, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(213, 103, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(214, 104, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(215, 105, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(216, 106, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(217, 107, 5892, 'Curtains', '149.99', '1.00', '{"color":"#808000","size":"4'' X 6''"}'),
(218, 107, 5892, 'Curtains', '149.99', '1.00', '{"color":"#90EE90","size":"6'' X 8''"}'),
(219, 107, 6095, 'Sofa 2', '3999.99', '1.00', ''),
(220, 107, 6094, 'Chair 2', '640.00', '1.00', ''),
(221, 107, 6087, 'Curtain 2', '640.00', '2.00', ''),
(222, 107, 6108, 'Glass 1', '200.00', '1.00', ''),
(223, 107, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#C0C0C0","size":"Single"}'),
(224, 98, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#A52A2A","size":"Single"}'),
(225, 108, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#A52A2A","size":"King"}'),
(226, 108, 6108, 'Glass 1', '200.00', '1.00', ''),
(227, 109, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#A52A2A","size":"Single"}'),
(228, 109, 5892, 'Curtains', '149.99', '10.00', '{"color":"#90EE90","size":"4'' X 6''"}'),
(229, 109, 6095, 'Sofa 2', '3999.99', '1.00', ''),
(230, 109, 6087, 'Curtain 2', '640.00', '9.00', ''),
(231, 110, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(232, 111, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(233, 111, 6087, 'Curtain 2', '640.00', '1.00', ''),
(234, 112, 6087, 'Curtain 2', '640.00', '1.00', ''),
(235, 113, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(236, 114, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(237, 115, 6094, 'Chair 2', '640.00', '1.00', ''),
(238, 116, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Single"}'),
(239, 117, 6087, 'Curtain 2', '640.00', '1.00', ''),
(240, 117, 5892, 'Curtains', '149.99', '1.00', '{"color":"#90EE90","size":"4'' X 6''"}'),
(241, 117, 5892, 'Curtains', '149.99', '1.00', '{"color":"#808000","size":"4'' X 6''"}'),
(242, 118, 6094, 'Chair 2', '640.00', '1.00', ''),
(243, 118, 5891, 'Lime Bedding', '99.99', '1.00', '{"color":"#BA55D3","size":"Queen"}');

-- --------------------------------------------------------

--
-- Table structure for table `payment_invoice`
--

CREATE TABLE IF NOT EXISTS `payment_invoice` (
  `pay_invoice_id` int(10) NOT NULL AUTO_INCREMENT,
  `pay_inv_number` varchar(100) NOT NULL,
  `pay_inv_date` datetime NOT NULL,
  `order_id` int(10) NOT NULL,
  `pay_inv_status` tinyint(1) NOT NULL,
  `user_id` int(10) NOT NULL,
  PRIMARY KEY (`pay_invoice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE IF NOT EXISTS `payment_methods` (
  `pay_method_id` int(10) NOT NULL AUTO_INCREMENT,
  `pay_method_name` varchar(256) NOT NULL,
  PRIMARY KEY (`pay_method_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`pay_method_id`, `pay_method_name`) VALUES
(1, 'Save Order'),
(2, 'Paypal');

-- --------------------------------------------------------

--
-- Table structure for table `prod_attrib`
--

CREATE TABLE IF NOT EXISTS `prod_attrib` (
  `attrib_id` int(10) NOT NULL AUTO_INCREMENT,
  `attrib_name` varchar(200) NOT NULL,
  PRIMARY KEY (`attrib_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `prod_attrib`
--

INSERT INTO `prod_attrib` (`attrib_id`, `attrib_name`) VALUES
(1, 'Size'),
(2, 'Color');

-- --------------------------------------------------------

--
-- Table structure for table `prod_attrib_ref`
--

CREATE TABLE IF NOT EXISTS `prod_attrib_ref` (
  `prod_attrib_ref_id` int(10) NOT NULL AUTO_INCREMENT,
  `prod_id` int(10) NOT NULL,
  `attrib_ref_set` varchar(100) NOT NULL,
  PRIMARY KEY (`prod_attrib_ref_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `prod_attrib_ref`
--

INSERT INTO `prod_attrib_ref` (`prod_attrib_ref_id`, `prod_id`, `attrib_ref_set`) VALUES
(1, 5892, '[1,3]'),
(2, 5892, '[1,4]'),
(3, 5892, '[2,3]'),
(4, 5892, '[2,4]');

-- --------------------------------------------------------

--
-- Table structure for table `prod_cat`
--

CREATE TABLE IF NOT EXISTS `prod_cat` (
  `cat_id` int(10) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(200) NOT NULL,
  `cat_status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `prod_cat`
--

INSERT INTO `prod_cat` (`cat_id`, `cat_name`, `cat_status`) VALUES
(1, 'Furnishings', 1),
(2, 'Furniture', 1),
(3, 'Accessories', 1),
(4, 'Beddings', 1),
(5, 'Sofa', 1),
(6, 'Eye Glasses', 1);

-- --------------------------------------------------------

--
-- Table structure for table `prod_cat_ref`
--

CREATE TABLE IF NOT EXISTS `prod_cat_ref` (
  `prod_cat_ref_id` int(10) NOT NULL AUTO_INCREMENT,
  `prod_id` int(10) NOT NULL,
  `cat_id` int(10) NOT NULL,
  `prod_status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`prod_cat_ref_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `prod_cat_ref`
--

INSERT INTO `prod_cat_ref` (`prod_cat_ref_id`, `prod_id`, `cat_id`, `prod_status`) VALUES
(1, 5891, 4, 1),
(2, 5892, 1, 1),
(3, 6087, 1, 1),
(4, 6094, 5, 1),
(5, 6095, 5, 1),
(6, 6108, 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `prod_tags`
--

CREATE TABLE IF NOT EXISTS `prod_tags` (
  `prod_tag_id` int(10) NOT NULL AUTO_INCREMENT,
  `prod_id` int(10) NOT NULL,
  `prod_tag_name` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`prod_tag_id`),
  FULLTEXT KEY `prod_tag_name` (`prod_tag_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `prod_tags`
--

INSERT INTO `prod_tags` (`prod_tag_id`, `prod_id`, `prod_tag_name`) VALUES
(1, 5891, 'Furniture'),
(2, 5891, 'Beddings'),
(3, 5892, 'Furnishings'),
(4, 6087, 'Furnishings');

-- --------------------------------------------------------

--
-- Table structure for table `shipping_methods`
--

CREATE TABLE IF NOT EXISTS `shipping_methods` (
  `ship_method_id` int(10) NOT NULL AUTO_INCREMENT,
  `ship_method_name` varchar(256) NOT NULL,
  `ship_method_details` varchar(256) NOT NULL,
  `ship_method_rate` varchar(100) NOT NULL,
  PRIMARY KEY (`ship_method_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `shipping_methods`
--

INSERT INTO `shipping_methods` (`ship_method_id`, `ship_method_name`, `ship_method_details`, `ship_method_rate`) VALUES
(1, 'General Shipping', '5-6 Business Days', '{"P":3}'),
(2, 'Express Shipping', '3-5 Business Days', '{"A":20.00}'),
(3, 'Overnight Shipping', '1-2 Business Days', '{"P":2}');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
