-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 18, 2018 at 12:17 AM
-- Server version: 10.1.34-MariaDB
-- PHP Version: 7.1.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sibley_rbac`
--

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `first_name`, `last_name`, `position`, `elected`, `email`, `phone`, `image_asset`) VALUES
(1, 'Jerryz', 'Johnsonz', 'council', '1', 'a@b.c', '1234567890', 7),
(2, 'Larry', 'Pedley', 'Council', '1', NULL, '7127542672', 0),
(3, 'Jan', 'Henningsen', 'council', '1', '', '7127542331', 0),
(4, 'Tim', 'Nobles', 'council', '1', NULL, '7127543922', 0),
(5, 'Gail', 'Buchholtz', 'council', '1', NULL, NULL, 0),
(6, 'Mike', 'Groote', 'councilz', '1', '', '', 0),
(7, 'Susan', 'Sembach', 'City Clerk', '0', 'sibleyclerk@premieronline.net', '7127542541', 0),
(8, 'Glenn', 'Anderson', 'City Administrator', '0', 'ctysibly@hickorytech.net', '7127542528', 0),
(10, 'LUCAS', 'PEDLEY', 'staff', '0', 'WATSON1024@HOTMAIL.COM', '6053214262', NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
