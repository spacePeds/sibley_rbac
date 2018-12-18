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
-- Dumping data for table `auth_item`
--

INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
('chamberAdmin', 1, 'User who can administer Chamber related modules', NULL, NULL, NULL, NULL),
('cityAdmin', 1, 'User who can administer City related modules', NULL, NULL, NULL, NULL),
('create_alert', 1, 'Create a city alert', NULL, NULL, NULL, NULL),
('create_asset', 1, 'Ability to upload an image asset', NULL, NULL, NULL, NULL),
('create_business', 1, 'Create a business', NULL, NULL, NULL, NULL),
('create_category', 1, 'Create a category', NULL, NULL, NULL, NULL),
('create_event', 1, 'Create an event in the calendar', NULL, NULL, NULL, NULL),
('create_meeting', 1, 'Create a council meeting', NULL, NULL, NULL, NULL),
('create_minutes', 1, 'Create council meeting minutes', NULL, NULL, NULL, NULL),
('create_staff', 1, 'Create a city employee', NULL, NULL, NULL, NULL),
('create_user', 1, 'Create a new user', NULL, NULL, NULL, NULL),
('delete_alert', 1, 'Delete a city alert', NULL, NULL, NULL, NULL),
('delete_asset', 1, 'Ability to delete an image asset', NULL, NULL, NULL, NULL),
('delete_business', 1, 'Delete a Business', NULL, NULL, NULL, NULL),
('delete_category', 1, 'Delete a Category', NULL, NULL, NULL, NULL),
('delete_event', 1, 'Delete an event in the calendar', NULL, NULL, NULL, NULL),
('delete_meeting', 1, 'Delete a council meeting', NULL, NULL, NULL, NULL),
('delete_minutes', 1, 'Delete council meeting minutes', NULL, NULL, NULL, NULL),
('delete_staff', 1, 'Delete a city staff member', NULL, NULL, NULL, NULL),
('recAdmin', 1, 'User who can administer Rec Department related modules', NULL, NULL, NULL, NULL),
('superAdmin', 1, 'User who can administer all modules and create new users', NULL, NULL, NULL, NULL),
('update_alert', 1, 'Update a city alert', NULL, NULL, NULL, NULL),
('update_asset', 1, 'Ability to replace an image asset', NULL, NULL, NULL, NULL),
('update_business', 1, 'Update a business', NULL, NULL, NULL, NULL),
('update_category', 1, 'Update a Category', NULL, NULL, NULL, NULL),
('update_event', 1, 'Update an event in the Calendar', NULL, NULL, NULL, NULL),
('update_location', 1, 'Allow a user to update the location page', NULL, NULL, NULL, NULL),
('update_meeting', 1, 'Update a council meeting', NULL, NULL, NULL, NULL),
('update_minutes', 1, 'Update council meeting minutes', NULL, NULL, NULL, NULL),
('update_staff', 1, 'Allow a user to update city staff', NULL, NULL, NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
