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
-- Database: `smarapps_client_lookbook`
--

-- --------------------------------------------------------

--
-- Table structure for table `attrib_ref`
--

CREATE TABLE IF NOT EXISTS `attrib_ref` (
  `attrib_ref_id` int(10) NOT NULL AUTO_INCREMENT,
  `prod_id` int(10) NOT NULL,
  `attrib_id` int(10) NOT NULL,
  `attrib_value` text,
  PRIMARY KEY (`attrib_ref_id`),
  FULLTEXT KEY `attrib_value` (`attrib_value`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;

--
-- Dumping data for table `attrib_ref`
--

INSERT INTO `attrib_ref` (`attrib_ref_id`, `prod_id`, `attrib_id`, `attrib_value`) VALUES
(1, 1434, 1, '30'),
(2, 1434, 1, '32'),
(3, 1434, 1, '34'),
(4, 1434, 1, '36'),
(5, 1434, 2, '#90EE90'),
(6, 1434, 2, '#F08080'),
(7, 1434, 2, '#808080'),
(8, 1439, 1, '5'),
(9, 1439, 1, '6'),
(10, 1439, 1, '7'),
(11, 1439, 1, '8'),
(12, 1438, 1, '30'),
(13, 1438, 1, '32'),
(14, 1438, 1, '34'),
(15, 1438, 2, '#ab8094'),
(16, 1438, 2, '#008080'),
(17, 1438, 2, '#008080'),
(18, 1435, 1, '10'),
(19, 1435, 1, '15'),
(20, 1435, 1, '20'),
(21, 1437, 2, '#90EE90'),
(22, 1437, 2, '#FFA500'),
(23, 1437, 2, '#b49369'),
(24, 1434, 2, '#000000'),
(28, 1440, 2, '#800000'),
(26, 1433, 2, '#000000'),
(27, 1432, 2, '#000000');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `attrib_ref_old`
--

INSERT INTO `attrib_ref_old` (`attrib_ref_id`, `prod_id`, `attrib_id`, `attrib_value`) VALUES
(1, 1434, 1, '["30","32","34","36"]'),
(2, 1434, 2, '["#000000","#C1C1C1","#FFFFFF"]'),
(3, 1439, 1, '["5","6","7","8"]'),
(4, 1438, 1, '["30","32","34","36"]'),
(5, 1438, 2, '["#000000","#C1C1C1","#FFFFFF"]'),
(6, 1435, 1, '["10","15","20"]'),
(7, 1437, 2, '["#F3F3F3","#C1C1C1","#FFFFFF"]');

-- --------------------------------------------------------

--
-- Table structure for table `cat_ref`
--

CREATE TABLE IF NOT EXISTS `cat_ref` (
  `cat_ref_id` int(10) NOT NULL AUTO_INCREMENT,
  `cat_parent_id` int(10) NOT NULL,
  `cat_id` int(10) NOT NULL,
  PRIMARY KEY (`cat_ref_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `cat_ref`
--

INSERT INTO `cat_ref` (`cat_ref_id`, `cat_parent_id`, `cat_id`) VALUES
(1, 0, 1),
(2, 0, 2),
(3, 1, 3),
(4, 1, 4),
(5, 2, 8),
(6, 2, 5),
(7, 2, 6),
(8, 0, 7);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_total`, `order_created_date`, `order_updated_date`, `order_status`, `order_sales_tax`, `order_cart_total`, `order_shipping_cost`, `order_shipping_addr1`, `order_shipping_addr2`, `order_shipping_city`, `order_shipping_state`, `order_shipping_zip`, `order_shipping_country`, `order_shipping_method`, `order_session_id`, `user_pay_method_id`) VALUES
(1, 9, '15.00', '2014-12-05 05:38:45', '2014-12-05 05:38:46', 1, '0.00', '15.00', '0.00', 'dd', 'sss', 'aza', 'df', '2345', 'US', '', 'famLbOFcIfw9WCknweSUKJUiCBa2diTkDskomAox', 1),
(2, 9, '15.00', '2014-12-05 05:39:45', '2014-12-05 05:39:46', 1, '0.00', '15.00', '0.00', 'dd', 'sss', 'aza', 'df', '2345', 'US', '', 'famLbOFcIfw9WCknweSUKJUiCBa2diTkDskomAox', 1),
(3, 9, '15.00', '2014-12-05 05:41:20', '2014-12-05 05:42:13', 0, '0.00', '15.00', '0.00', 'dd', 'sss', 'aza', 'df', '2345', 'US', '', 'famLbOFcIfw9WCknweSUKJUiCBa2diTkDskomAox', 0),
(4, 9, '15.00', '2014-12-05 05:44:30', '2014-12-05 05:45:44', 1, '0.00', '15.00', '0.00', 'dd', 'sss', 'aza', 'df', '2345', 'US', '', '6Ja5vSrurCs4Z8kY5a7y4LUMWTlVneDh598ngrFE', 1),
(5, 9, '74.00', '2014-12-05 06:17:08', '2014-12-05 06:20:35', 1, '0.00', '74.00', '0.00', 'dd', 'sss', 'aza', 'df', '2345', 'US', '', 'W8Rzi8DXOkJ0ZkNJik4O7qGwYqjZz6vlmkvg34bm', 1),
(6, 9, '19.00', '2014-12-05 06:20:07', '2014-12-05 06:20:08', 1, '0.00', '19.00', '0.00', 'dd', 'sss', 'aza', 'df', '2345', 'US', '', 'W8Rzi8DXOkJ0ZkNJik4O7qGwYqjZz6vlmkvg34bm', 1),
(7, 9, '15.00', '2014-12-05 06:26:19', '2014-12-05 06:27:49', 1, '0.00', '15.00', '0.00', 'dd', 'sss', 'aza', 'df', '2345', 'US', '', 'U8y3Ho9ik5XfGJdMXuFyHE4uMLn6x6e9smkZFGKR', 1),
(8, 9, '93.00', '2014-12-05 06:30:24', '2014-12-05 06:31:09', 1, '0.00', '93.00', '0.00', 'dd', 'sss', 'aza', 'df', '2345', 'US', '', 'U8y3Ho9ik5XfGJdMXuFyHE4uMLn6x6e9smkZFGKR', 1),
(9, 9, '19.00', '2014-12-05 06:34:41', '2014-12-05 06:36:02', 1, '0.00', '19.00', '0.00', 'dd', 'sss', 'aza', 'df', '2345', 'US', '', 'GDzVEwEzRaw78xqpzTcxubjuCUSOhqLD1LCRlzbE', 1),
(10, 9, '38.00', '2014-12-11 02:11:45', '2014-12-11 02:11:45', 0, '0.00', '38.00', '0.00', 'Add1', '', 'Newyork', 'OH', '4577', 'US', '', 'hVtELgwIPGRDy0Qoj1YwKwWADtb4y0zT9En9mYnk', 0),
(11, 9, '19.00', '2014-12-22 07:23:08', '2014-12-22 07:23:10', 1, '0.00', '19.00', '0.00', 'Add1', '', 'Newyork', 'OM', '4577', 'US', '', 'aHjF7jxpLPUvrGnCZBROIOuqqlgreNJtrKIt6dNY', 1),
(12, 9, '30.00', '2014-12-22 07:54:37', '2014-12-22 07:54:39', 1, '0.00', '30.00', '0.00', 'Add1', '', 'Newyork', 'OM', '4577', 'US', '', 'NStm9caxPh36Zh3f1i08egrN3avBKLyraYrOKHuC', 1),
(13, 9, '89.00', '2014-12-23 00:12:46', '2014-12-23 00:12:50', 1, '0.00', '89.00', '0.00', 'Add2', '', 'Col', 'OH', '98765', 'US', '', 'bthKVnA8flDRSuMKbDqduzirUe2DPl2dhXxHdz8B', 1),
(14, 9, '80.45', '2014-12-30 01:07:03', '2014-12-30 01:07:03', 0, '6.45', '74.00', '0.00', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '', '0fQqCMeup7inIfquPdHkb6To7C5CDqs985RKlNFP', 0),
(15, 9, '144.60', '2014-12-30 03:31:13', '2014-12-30 03:31:15', 1, '11.60', '133.00', '0.00', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '', 'UvrbwOgMZNHFBrdlnZfzqyVbA6OEjosh63D6Iztq', 1),
(16, 9, '61.05', '2014-12-30 04:50:52', '2014-12-30 04:50:54', 1, '4.05', '57.00', '0.00', 'Add2', '', 'Col', 'OH', '98765', 'US', '', '7bCMoXpCJa3gbRCwepSwNozr7pdfrL5scbETkbzX', 1),
(17, 9, '32.13', '2014-12-30 05:40:05', '2014-12-30 05:40:06', 1, '2.13', '30.00', '0.00', 'Add2', '', 'Col', 'OH', '98765', 'US', '', 'd3O82Oyp3kduArP2fpOhkMYPcqUbU8Q2wQIHhvKM', 1),
(18, 9, '80.45', '2014-12-30 06:37:37', '2014-12-30 06:37:39', 1, '6.45', '74.00', '0.00', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '', 'IeGGkp0ljJcCAwVoDtA8Kzw5yx5Ygv6DwRwX4V59', 1),
(19, 9, '80.45', '2014-12-30 06:55:02', '2014-12-30 06:55:03', 1, '6.45', '74.00', '0.00', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '', 'o73cb5zGqTvLEabTN3rR1BWT0Y9UHpubu9yOjznB', 1),
(20, 13, '117.82', '2014-12-30 18:07:58', '2014-12-30 18:08:00', 1, '7.82', '110.00', '0.00', '1245 polaris pkwy', '', 'Columbus', 'Oh', '43240', 'US', '', 'M8pGwVwWXTCMMGhSh20bx1rOtDiKZ16GjisKf0w1', 1),
(21, 13, '99.61', '2014-12-30 18:10:30', '2014-12-30 18:10:32', 1, '6.61', '93.00', '0.00', '1245 polaris pkwy', '', 'Columbus', 'Oh', '43240', 'US', '', 'M8pGwVwWXTCMMGhSh20bx1rOtDiKZ16GjisKf0w1', 1),
(22, 9, '20.66', '2015-01-02 05:37:55', '2015-01-02 05:37:55', 0, '1.66', '19.00', '0.00', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '', 'DFxxmVfkQyGqLBDBZWwZrUSvRNkKuPrtCfrZ05Vw', 0),
(23, 6, '20.55', '2015-01-02 07:40:15', '2015-01-02 07:40:15', 0, '1.55', '19.00', '0.00', 'hi', '', 'adf', 'TX', 'hjgjh', 'US', '', 'cSrKuzXGWpsnmNUEHh5uJ6WO3BAXVmxjk9iUcWSq', 0),
(24, 9, '80.45', '2015-01-02 07:50:01', '2015-01-02 07:51:05', 0, '6.45', '74.00', '0.00', 'Add1', '', 'Newyork', 'OK', '4577', 'US', '', 'dtTIIYTxtEa6MMm3aYdhWsQOjI4DaYFHV3cNbxoY', 1),
(25, 16, '19.00', '2015-01-02 10:38:19', '2015-01-02 15:26:11', 0, '0.00', '19.00', '0.00', '296 s. Merkle rd', '', 'Bexley', 'Ohio', '43209', 'US', '', 'PqXP1NOK9D1cK1yGY4VW1N98LxuIRZP86jW2FA2R', 0),
(26, 16, '19.00', '2015-01-04 08:41:21', '2015-01-04 08:41:21', 0, '0.00', '19.00', '0.00', '296 s. Merkle rd', '', 'Bexley', 'Ohio', '43209', 'US', '', 'PqXP1NOK9D1cK1yGY4VW1N98LxuIRZP86jW2FA2R', 0);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`order_details_id`, `order_id`, `prod_id`, `prod_name`, `prod_price`, `prod_quantity`, `prod_attribs`) VALUES
(1, 1, 1438, 'Vintage Circle Shades', '15.00', '1.00', '{"color":"#008080","size":"32"}'),
(2, 2, 1438, 'Vintage Circle Shades', '15.00', '1.00', '{"color":"#008080","size":"32"}'),
(3, 3, 1438, 'Vintage Circle Shades', '15.00', '1.00', '{"color":"#008080","size":"32"}'),
(4, 4, 1438, 'Vintage Circle Shades', '15.00', '1.00', '{"color":"#008080","size":"32"}'),
(5, 5, 1434, 'Double-Breasted Coat', '74.00', '1.00', '{"color":"#808080","size":"30"}'),
(6, 6, 1439, 'Pyramid PU Bracelet', '19.00', '1.00', '{"size":"5"}'),
(7, 7, 1439, 'Pyramid PU Bracelet', '19.00', '1.00', '{"size":"6"}'),
(8, 7, 1438, 'Vintage Circle Shades', '15.00', '1.00', '{"color":"#008080","size":"30"}'),
(9, 8, 1439, 'Pyramid PU Bracelet', '19.00', '1.00', '{"size":"5"}'),
(10, 8, 1434, 'Double-Breasted Coat', '74.00', '1.00', '{"color":"#808080","size":"30"}'),
(11, 9, 1439, 'Pyramid PU Bracelet', '19.00', '1.00', '{"size":"5"}'),
(12, 10, 1439, 'Pyramid PU Bracelet', '19.00', '2.00', '{"size":"6"}'),
(13, 11, 1439, 'Pyramid PU Bracelet', '19.00', '1.00', '{"size":"5"}'),
(14, 12, 1438, 'Vintage Circle Shades', '15.00', '2.00', '{"color":"#ab8094","size":"30"}'),
(15, 13, 1438, 'Vintage Circle Shades', '15.00', '1.00', '{"color":"#ab8094","size":"30"}'),
(16, 13, 1434, 'Double-Breasted Coat', '74.00', '1.00', '{"color":"#90EE90","size":"30"}'),
(17, 14, 1434, 'Double-Breasted Coat', '74.00', '1.00', '{"color":"#90EE90","size":"30"}'),
(18, 15, 1439, 'Pyramid PU Bracelet', '19.00', '7.00', '{"size":"7"}'),
(19, 16, 1439, 'Pyramid PU Bracelet', '19.00', '3.00', '{"size":"7"}'),
(20, 17, 1438, 'Vintage Circle Shades', '15.00', '2.00', '{"color":"#ab8094","size":"30"}'),
(21, 18, 1434, 'Double-Breasted Coat', '74.00', '1.00', '{"color":"#90EE90","size":"30"}'),
(22, 19, 1434, 'Double-Breasted Coat', '74.00', '1.00', '{"color":"#90EE90","size":"30"}'),
(23, 20, 1435, 'Studs Rhinestones Bracelet', '17.00', '1.00', '{"size":"10"}'),
(24, 20, 1434, 'Double-Breasted Coat', '74.00', '1.00', '{"color":"#90EE90","size":"30"}'),
(25, 20, 1439, 'Pyramid PU Bracelet', '19.00', '1.00', '{"size":"5"}'),
(26, 21, 1439, 'Pyramid PU Bracelet', '19.00', '1.00', '{"size":"5"}'),
(27, 21, 1434, 'Double-Breasted Coat', '74.00', '1.00', '{"color":"#90EE90","size":"30"}'),
(28, 22, 1439, 'Pyramid PU Bracelet', '19.00', '1.00', '{"size":"5"}'),
(29, 23, 1439, 'Pyramid PU Bracelet', '19.00', '1.00', '{"size":"5"}'),
(30, 24, 1434, 'Double-Breasted Coat', '74.00', '1.00', '{"color":"#90EE90","size":"30"}'),
(31, 25, 1439, 'Pyramid PU Bracelet', '19.00', '1.00', '{"size":"5"}'),
(32, 26, 1439, 'Pyramid PU Bracelet', '19.00', '1.00', '{"size":"6"}');

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
-- Table structure for table `prod_cat`
--

CREATE TABLE IF NOT EXISTS `prod_cat` (
  `cat_id` int(10) NOT NULL AUTO_INCREMENT,
  `cat_name` text,
  `cat_status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`cat_id`),
  FULLTEXT KEY `cat_name` (`cat_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `prod_cat`
--

INSERT INTO `prod_cat` (`cat_id`, `cat_name`, `cat_status`) VALUES
(1, 'Men', 1),
(2, 'Women', 1),
(3, 'T-Shirt', 1),
(4, 'Jeans', 1),
(5, 'Tops', 1),
(6, 'Shoes', 1),
(7, 'Accessories', 1),
(8, 'Men Jeans', 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `prod_cat_ref`
--

INSERT INTO `prod_cat_ref` (`prod_cat_ref_id`, `prod_id`, `cat_id`, `prod_status`) VALUES
(1, 1434, 4, 1),
(2, 1439, 6, 1),
(3, 1438, 4, 1),
(4, 1435, 7, 1),
(5, 1437, 2, 1);

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `prod_tags`
--

INSERT INTO `prod_tags` (`prod_tag_id`, `prod_id`, `prod_tag_name`) VALUES
(1, 1434, 'Women'),
(2, 1434, 'Jeans'),
(3, 1439, 'Shoes'),
(4, 1439, 'Women'),
(5, 1438, 'Accessories'),
(6, 1438, 'Men'),
(7, 1435, 'Accessories'),
(8, 1437, 'Women'),
(9, 1433, 'Women'),
(11, 1432, 'Accessories'),
(12, 1440, 'Women');

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
