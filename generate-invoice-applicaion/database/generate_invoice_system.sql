-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 22, 2021 at 06:10 AM
-- Server version: 8.0.23
-- PHP Version: 8.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `generate_invoice_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `invoice_list`
--

DROP TABLE IF EXISTS `invoice_list`;
CREATE TABLE IF NOT EXISTS `invoice_list` (
  `invoice_id` int NOT NULL AUTO_INCREMENT,
  `invoice_rep` varchar(250) NOT NULL,
  `invoice_created_by` int NOT NULL,
  `invoice_created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `invoice_receiver_name` varchar(250) NOT NULL,
  `invoice_receiver_address` text NOT NULL,
  `invoice_sub_total` decimal(10,2) NOT NULL,
  `invoice_sub_total_with_tax` double(10,2) NOT NULL,
  `invoice_discount_type` decimal(10,2) NOT NULL,
  `invoice_discount_rate` decimal(10,2) NOT NULL,
  `invoice_total_amount` decimal(10,2) NOT NULL,
  `invoice_deleted` int NOT NULL,
  PRIMARY KEY (`invoice_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `invoice_list`
--

INSERT INTO `invoice_list` (`invoice_id`, `invoice_rep`, `invoice_created_by`, `invoice_created_date`, `invoice_receiver_name`, `invoice_receiver_address`, `invoice_sub_total`, `invoice_sub_total_with_tax`, `invoice_discount_type`, `invoice_discount_rate`, `invoice_total_amount`, `invoice_deleted`) VALUES
(1, 'INV52779', 1, '2021-12-19 04:23:54', 'Anju', '474\r\nHouse one,\r\nPune - 669357', '900.00', 960.00, '2.00', '10.00', '864.00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `invoice_product_details`
--

DROP TABLE IF EXISTS `invoice_product_details`;
CREATE TABLE IF NOT EXISTS `invoice_product_details` (
  `invpr_id` int NOT NULL AUTO_INCREMENT,
  `invpr_invoice_id` int NOT NULL,
  `invpr_product_id` varchar(250) NOT NULL,
  `invpr_quantity` int NOT NULL,
  `invpr_tax_rate` int NOT NULL,
  PRIMARY KEY (`invpr_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `invoice_product_details`
--

INSERT INTO `invoice_product_details` (`invpr_id`, `invpr_invoice_id`, `invpr_product_id`, `invpr_quantity`, `invpr_tax_rate`) VALUES
(1, 1, '6', 1, 10),
(2, 1, '2', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `invoice_product_list`
--

DROP TABLE IF EXISTS `invoice_product_list`;
CREATE TABLE IF NOT EXISTS `invoice_product_list` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `product_name` varchar(250) NOT NULL,
  `product_quantity` varchar(250) NOT NULL,
  `product_unit_price` decimal(10,2) NOT NULL,
  `product_deleted` int NOT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `invoice_product_list`
--

INSERT INTO `invoice_product_list` (`product_id`, `product_name`, `product_quantity`, `product_unit_price`, `product_deleted`) VALUES
(1, 'Galaxy Note', '1', '700.00', 1),
(2, 'Smart Watch', '1', '300.00', 1),
(3, 'Mobile Cover', '1', '10.00', 1),
(4, 'Smart TV', '1', '500.00', 1),
(5, 'Soundbar', '1', '250.00', 1),
(6, 'Refrigerator', '1', '600.00', 1),
(7, 'Smart Keyboard', '1', '100.00', 1),
(8, 'Portable SSD', '1', '300.00', 1),
(9, 'Flash Drive', '1', '40.00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `invoice_user`
--

DROP TABLE IF EXISTS `invoice_user`;
CREATE TABLE IF NOT EXISTS `invoice_user` (
  `userid` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `deleted` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `invoice_user`
--

INSERT INTO `invoice_user` (`userid`, `first_name`, `last_name`, `username`, `password`, `deleted`) VALUES
(1, 'Admin', '', 'admin', 'admin123', 1),
(2, 'System', 'Admin', 'sysadmin', 'sys123', 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
