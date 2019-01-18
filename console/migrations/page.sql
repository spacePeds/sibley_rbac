-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 01, 2019 at 09:45 PM
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
-- Dumping data for table `page`
--

INSERT INTO `page` (`id`, `route`, `title`, `body`, `last_edit_dt`, `user_id`) VALUES
(1, 'health', 'Sibley: Health Services', '<p>Providing the newest and most advanced health care to the entire area is the aim of the Family Medicine Clinic and Osceola Community Hospital. The 32-bed hospital provides the special services of radiology, MRI, CT scanner, Ultrasound, cardiology scans, cardiac rehabilitation, nuclear medicine, stress tests, occupational health screening, and home health care. test In addition to the four board-certified family practice physicians, the following specialists are available: Cardiologist Urologist Orthopedist Surgeons EMT Oncologist Speech Pathologist Occupational and Physical Therapists Also available in Sibley are two pharmacies, two chiropractic offices, one optometrist, two licensed massage therapists and two dentists. Serving residents of the older generation are the Sibley Nursing and Rehab Center, Country View Manor, Viola House, and Heartwood Heights, a 22 unit, independent and assisted senior living facility, connected to the Osceola Community Hospital and Clinic. Sibley also offers complete pet health care with a veterinary clinic and pet store. The Sibley Senior Center houses both the Dinner Date Programs and a wide range of activities for senior adults within the community.</p>\r\n', '2018-11-02 20:13:55', 1),
(2, 'sibley/location/', 'Sibley Visitors Guide: Location', '<h2>Visitors Guide</h2>\r\n\r\n<p>Surrounding Sibley are many family farms, which provide products and the work ethic typical of our community. Northwest Iowa&#39;s fertile soil is a unique resource, which provides the economic base for the area.</p>\r\n\r\n<p>Transportation by rail, air and highways provide easy access to major Midwest markets for both agricultural and manufactured products. HI</p>\r\n\r\n<p>here i am a adding things<img alt=\"cool\" src=\"http://localhost/sibley_rbac/frontend/web/assets/d5ab9c05/plugins/smiley/images/shades_smile.png\" style=\"height:23px; width:23px\" title=\"cool\" /></p>\r\n\r\n<table border=\"1\" cellpadding=\"1\" cellspacing=\"1\" style=\"width:500px\">\r\n	<tbody>\r\n		<tr>\r\n			<td>a</td>\r\n			<td>b</td>\r\n		</tr>\r\n		<tr>\r\n			<td>c</td>\r\n			<td>d</td>\r\n		</tr>\r\n		<tr>\r\n			<td>e</td>\r\n			<td>f</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n\r\n<p>test</p>\r\n', '2018-11-04 21:23:27', 1),
(3, 'test', 'test title', '<p>this is a test</p>\r\n\r\n<p><span style=\"background-color:#2ecc71\">This is a test</span></p>\r\n\r\n<p><span style=\"background-color:#2ecc71\"><strong>this is a test</strong></span></p>\r\n\r\n<table border=\"1\" cellpadding=\"1\" cellspacing=\"1\" style=\"width:500px\">\r\n	<tbody>\r\n		<tr>\r\n			<td>\r\n			<ul>\r\n				<li>orange</li>\r\n			</ul>\r\n			</td>\r\n			<td>&nbsp;</td>\r\n		</tr>\r\n		<tr>\r\n			<td>\r\n			<h2><span style=\"font-family:Comic Sans MS,cursive\">bananna</span></h2>\r\n			</td>\r\n			<td>\r\n			<h2>&nbsp;</h2>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td>\r\n			<h2>&nbsp;</h2>\r\n			</td>\r\n			<td>&nbsp;</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n\r\n<p>&nbsp;</p>\r\n', '2018-11-02 20:26:25', 1),
(4, 'sibley/staff', 'Sibley Iowa', '<div class=\"container\">\r\n        <div class=\"row\">\r\n            <div class=\"col-md-6\">\r\n                <h3>City of Sibley: Mission</h3>\r\n                <p>Increase opportunities that will enhance the quality of life for all ages.</p>\r\n\r\n                <h3>Vision Statement</h3>\r\n                <p>Sibley, located in beautiful NW Iowa offers an energetic community, large retail base, affordable housing, and modern telecommunications. We Strive to provide an excellent quality of life for all through a great medical community, a diverse and thriving industrial base, great educational and recreational opportunities. Sibley, Iowa, unlimited opportunities with strong family values.</p>\r\n                \r\n            </div>\r\n\r\n            <div class=\"col-md-6\">\r\n                <img src=\"/sibley_rbac/frontend/web/img/cityOffice.jpg\" alt=\"\" class=\"rec-img img-fluid rounded-circle d-none d-md-block\">\r\n            </div>\r\n        </div>\r\n        <div class=\"row\">\r\n            <div class=\"col-md-12\">\r\n                <h3>About Sibley</h3>\r\n\r\n                <p>Sibley, located in beautiful NW Iowa offers an energetic community, large retail base, affordable housing, and modern telecommunications. \r\n                    We Strive to provide an excellent quality of life for all through a great medical community, a diverse and thriving industrial base, \r\n                    great educational and recreational opportunities. Sibley, Iowa, unlimited opportunities with strong family values.</p>\r\n            </div>\r\n        </div>\r\n\r\n    </div>\r\n', '2018-12-11 00:00:00', 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
