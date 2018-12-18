-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 18, 2018 at 12:19 AM
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
-- Dumping data for table `auth_item_child`
--

INSERT INTO `auth_item_child` (`parent`, `child`) VALUES
('cityAdmin', 'update_staff'),
('superAdmin', 'create_alert'),
('superAdmin', 'create_asset'),
('superAdmin', 'create_business'),
('superAdmin', 'create_category'),
('superAdmin', 'create_event'),
('superAdmin', 'create_meeting'),
('superAdmin', 'create_minutes'),
('superAdmin', 'create_staff'),
('superAdmin', 'create_user'),
('superAdmin', 'delete_alert'),
('superAdmin', 'delete_asset'),
('superAdmin', 'delete_business'),
('superAdmin', 'delete_category'),
('superAdmin', 'delete_event'),
('superAdmin', 'delete_meeting'),
('superAdmin', 'delete_minutes'),
('superAdmin', 'delete_staff'),
('superAdmin', 'update_alert'),
('superAdmin', 'update_asset'),
('superAdmin', 'update_business'),
('superAdmin', 'update_category'),
('superAdmin', 'update_event'),
('superAdmin', 'update_location'),
('superAdmin', 'update_meeting'),
('superAdmin', 'update_minutes'),
('superAdmin', 'update_staff');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
