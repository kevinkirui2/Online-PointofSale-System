-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 04, 2026 at 07:09 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pointofsale`
--

-- --------------------------------------------------------

--
-- Table structure for table `cashflow`
--

CREATE TABLE `cashflow` (
  `transaction_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `amount` decimal(18,2) DEFAULT NULL,
  `username` varchar(30) DEFAULT NULL,
  `transaction_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `cashflow`
--

INSERT INTO `cashflow` (`transaction_id`, `description`, `amount`, `username`, `transaction_date`) VALUES
(1, 'Cash-in', 10000.00, 'admin', '2019-04-08 15:51:58');

-- --------------------------------------------------------

--
-- Table structure for table `closeofdaysummary`
--

CREATE TABLE `closeofdaysummary` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `cash_start` decimal(10,2) DEFAULT NULL,
  `cash_end` decimal(10,2) DEFAULT NULL,
  `mpesa_start` decimal(10,2) DEFAULT NULL,
  `mpesa_end` decimal(10,2) DEFAULT NULL,
  `deduction_method` varchar(20) DEFAULT NULL,
  `deduction_amount` decimal(10,2) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `date` date NOT NULL,
  `total_made` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `closeofdaysummary`
--

INSERT INTO `closeofdaysummary` (`id`, `username`, `cash_start`, `cash_end`, `mpesa_start`, `mpesa_end`, `deduction_method`, `deduction_amount`, `notes`, `date`, `total_made`) VALUES
(1, 'Kevin', 1000.00, 4000.00, 500.00, 2500.00, 'Cash', 500.00, 'Normal business day', '2026-02-20', 0.00),
(2, 'Kevin', 4000.00, 5000.00, 2500.00, 3200.00, 'MPESA', 300.00, 'Stock purchase deduction', '2026-02-21', 0.00),
(3, 'smoothdime', 4000.00, 3800.00, 2500.00, 2500.00, 'Cash', 200.00, '', '2026-02-21', 0.00),
(4, 'smoothdime', 5000.00, 5500.00, 3200.00, 3200.00, 'Cash', 200.00, 'VCUT', '2026-02-22', 0.00),
(5, 'mike123', 0.00, 350.00, 0.00, 0.00, 'Cash', 100.00, 'Lunch Needed', '2026-02-24', 350.00);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `firstname` varchar(30) DEFAULT NULL,
  `lastname` varchar(30) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `contact_number` varchar(30) DEFAULT NULL,
  `image` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `firstname`, `lastname`, `address`, `contact_number`, `image`) VALUES
(16, 'jersel', 'Bill', 'Philippines', '+63(09)1234-1234', 'user.png');

-- --------------------------------------------------------

--
-- Table structure for table `delivery`
--

CREATE TABLE `delivery` (
  `transaction_no` varchar(20) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `username` varchar(20) DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `delivery`
--

INSERT INTO `delivery` (`transaction_no`, `supplier_id`, `username`, `date`) VALUES
('5CAAFDA8CD697', 21, 'admin', '2019-04-08 15:52:40'),
('5CAAFDEEDB333', 22, 'admin', '2019-04-08 15:54:19'),
('5CAAFE37D21E8', 21, 'admin', '2019-04-08 15:55:28'),
('5E7F00084C934', 22, 'admin', '2020-03-28 15:43:22'),
('5E81DF2B7B8F7', 22, 'admin', '2020-03-30 20:00:48'),
('66F04F8564B8C', 23, 'admin', '2024-09-22 20:13:19'),
('673B48EE9028F', 21, 'admin', '2024-11-19 10:59:57'),
('673C4FC4A8782', 23, 'admin', '2024-11-19 11:44:04'),
('673C5070BD2D5', 23, 'admin', '2024-11-19 11:47:01'),
('673C54D833A87', 23, 'admin', '2024-11-19 12:05:43'),
('673C5607B3577', 23, 'admin', '2024-11-19 12:10:49'),
('673C5A934A4AE', 23, 'admin', '2024-11-19 12:30:10'),
('673C5BED556AD', 21, 'admin', '2024-11-19 12:35:50'),
('673C5CA9A67BC', 23, 'admin', '2024-11-19 12:39:04'),
('673C5D11CE505', 23, 'admin', '2024-11-19 12:40:50'),
('673C5D8F64B2E', 23, 'admin', '2024-11-19 12:43:12'),
('673C5DB72B142', 23, 'admin', '2024-11-19 13:18:25'),
('673C604CC9DCF', 23, 'admin', '2024-11-19 12:54:40'),
('673C62352785B', 23, 'admin', '2024-11-19 13:02:46'),
('673C67D4DAF21', 23, 'admin', '2024-11-19 13:27:43'),
('677D4A0A11E65', 23, 'admin', '2025-01-07 18:37:16'),
('677D4A7FE6927', 23, 'admin', '2025-01-07 18:38:51'),
('677F948F16F11', 21, 'admin', '2025-01-09 12:20:00'),
('677F96708B7DF', 21, 'admin', '2025-01-09 12:27:53'),
('677F96FC61293', 21, 'admin', '2025-01-09 12:30:33'),
('677F9B1374FA4', 21, 'admin', '2025-01-09 12:47:38'),
('677F9C5DBF44B', 21, 'admin', '2025-01-09 12:53:01'),
('6780D1B7BAE6A', 23, 'admin', '2025-01-10 10:53:13'),
('6780D9D99094C', 21, 'admin', '2025-01-10 11:27:56'),
('6780DAE754EDF', 23, 'admin', '2025-01-10 11:32:01'),
('6780DB826979F', 23, 'admin', '2025-01-10 11:34:37'),
('6780E3C062251', 23, 'admin', '2025-01-10 12:09:48'),
('6780E52897F18', 23, 'admin', '2025-01-10 12:15:55'),
('6780E961C1A68', 21, 'admin', '2025-01-10 12:34:02'),
('6780E9F290444', 23, 'admin', '2025-01-10 12:36:34'),
('67812E1B71B37', 21, 'admin', '2025-01-10 17:27:02'),
('67812ED0A5EBB', 21, 'admin', '2025-01-10 17:30:57'),
('67813267DF3F0', 23, 'admin', '2025-01-10 17:45:11'),
('678132EFDA7EC', 21, 'admin', '2025-01-10 17:48:12'),
('67813501BF839', 23, 'admin', '2025-01-10 17:56:55');

-- --------------------------------------------------------

--
-- Table structure for table `initial_products`
--

CREATE TABLE `initial_products` (
  `id` varchar(50) NOT NULL,
  `initial_quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `initial_products`
--

INSERT INTO `initial_products` (`id`, `initial_quantity`) VALUES
('1001', 100),
('10011', 200),
('10012', 100),
('1', 100),
('2', 200),
('3', 150),
('4', 125),
('5', 100),
('23213', 23),
('10000', 21),
('101', 2000),
('1001', 100),
('10011', 200),
('10012', 100),
('1', 100),
('2', 200),
('3', 150),
('4', 125),
('5', 100),
('23213', 23),
('10000', 21),
('101', 2000),
('S9SJS9S', 5),
('60', 30),
('10', 40),
('40', 392),
('70', 100),
('12', 20),
('13', 10),
('1', 50),
('2', 60),
('3', 50),
('4', 60),
('5', 40),
('6', 50),
('7', 80),
('8', 80),
('9', 80),
('10', 200),
('11', 300),
('12', 100),
('13', 40),
('14', 40),
('15', 40),
('16', 30),
('17', 30),
('18', 20),
('19', 50),
('20', 25),
('21', 15),
('22', 20),
('23', 15),
('24', 30),
('25', 20),
('26', 30),
('27', 30),
('28', 100),
('29', 20),
('30', 40),
('31', 20),
('32', 30),
('33', 30),
('34', 24),
('35', 24),
('36', 10),
('37', 10);

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `purpose` varchar(30) NOT NULL,
  `logs_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `username`, `purpose`, `logs_time`) VALUES
(851, 'admin', 'User admin login', '2019-04-08 15:48:04'),
(854, 'admin', 'User admin logout', '2019-04-08 15:49:48'),
(855, 'admin', 'User admin login', '2019-04-08 15:50:04'),
(856, 'admin', 'Supplier OrangeCompany added', '2019-04-08 15:50:54'),
(857, 'admin', 'Customer jersel Added', '2019-04-08 15:51:25'),
(858, 'admin', 'Cash-in', '2019-04-08 15:51:58'),
(859, 'admin', 'Delivery Added', '2019-04-08 15:52:40'),
(860, 'admin', 'Customer Bill Added', '2019-04-08 15:53:18'),
(861, 'admin', 'Delivery Added', '2019-04-08 15:54:19'),
(862, 'admin', 'Delivery Added', '2019-04-08 15:55:29'),
(863, 'admin', 'Product sold', '2019-04-08 15:56:39'),
(864, 'admin', 'User admin logout', '2019-04-08 15:57:38'),
(865, 'admin', 'User admin login', '2019-04-08 16:06:54'),
(866, 'admin', 'User admin login', '2019-04-08 20:28:36'),
(867, 'admin', 'Product sold', '2019-04-08 20:29:27'),
(868, 'admin', 'User admin login', '2020-03-23 16:04:06'),
(869, 'admin', 'User admin logout', '2020-03-23 16:04:24'),
(870, 'admin', 'User admin login', '2020-03-28 12:58:34'),
(871, 'admin', 'User admin logout', '2020-03-28 13:02:20'),
(872, 'admin', 'User admin login', '2020-03-28 13:02:26'),
(873, 'admin', 'User admin logout', '2020-03-28 13:02:59'),
(874, 'admin', 'User admin login', '2020-03-28 13:05:48'),
(875, 'admin', 'Product sold', '2020-03-28 14:06:26'),
(876, 'admin', 'Product sold', '2020-03-28 14:07:27'),
(877, 'admin', 'Product sold', '2020-03-28 14:08:09'),
(878, 'admin', 'Product sold', '2020-03-28 14:14:46'),
(879, 'admin', 'Product sold', '2020-03-28 14:22:55'),
(880, 'admin', 'Product sold', '2020-03-28 14:27:51'),
(881, 'admin', 'Delivery Added', '2020-03-28 15:43:22'),
(882, 'admin', 'Product sold', '2020-03-28 16:14:30'),
(883, 'admin', 'User admin login', '2020-03-29 09:26:29'),
(884, 'admin', 'User admin login', '2020-03-29 09:40:46'),
(885, 'admin', 'Product Coffee updated', '2020-03-29 09:53:36'),
(886, 'admin', 'Product Coffee updated', '2020-03-29 09:53:51'),
(887, 'admin', 'User admin login', '2020-03-30 09:05:52'),
(888, 'admin', 'Product sold', '2020-03-30 09:07:10'),
(889, 'admin', 'User admin login', '2020-03-30 19:59:24'),
(890, 'admin', 'Delivery Added', '2020-03-30 20:00:48'),
(891, 'admin', 'User admin login', '2020-03-30 22:26:03'),
(892, 'admin', 'User admin login', '2024-09-08 15:18:09'),
(893, 'admin', 'User admin login', '2024-09-10 10:27:12'),
(894, 'admin', 'User admin login', '2024-09-10 22:50:16'),
(895, 'admin', 'User admin login', '2024-09-11 23:36:36'),
(896, 'admin', 'Product sold', '2024-09-12 00:12:16'),
(897, 'admin', 'User admin login', '2024-09-12 10:30:41'),
(898, 'admin', 'User admin login', '2024-09-12 12:22:11'),
(899, 'admin', 'User admin login', '2024-09-12 12:34:06'),
(900, 'admin', 'Product sold', '2024-09-12 12:38:54'),
(901, 'admin', 'Product sold', '2024-09-12 12:58:12'),
(902, 'admin', 'Product sold', '2024-09-12 13:13:16'),
(903, 'admin', 'Product sold', '2024-09-12 19:55:44'),
(904, 'admin', 'User admin login', '2024-09-12 21:06:30'),
(905, 'admin', 'Product sold', '2024-09-12 21:55:36'),
(906, 'admin', 'User admin login', '2024-09-14 12:11:57'),
(907, 'admin', 'Product sold', '2024-09-14 12:12:13'),
(908, 'admin', 'Product sold', '2024-09-14 12:15:21'),
(909, 'admin', 'Product sold', '2024-09-15 17:13:14'),
(910, 'admin', 'Product sold', '2024-09-15 17:13:57'),
(911, 'admin', 'Product sold', '2024-09-16 20:56:56'),
(912, 'admin', 'User admin login', '2024-09-18 11:29:25'),
(913, 'admin', 'Product sold', '2024-09-18 11:50:56'),
(914, 'admin', 'Product sold', '2024-09-18 12:37:34'),
(915, 'admin', 'User admin login', '2024-09-22 19:30:48'),
(916, 'admin', 'User admin login', '2024-09-22 19:31:12'),
(917, 'admin', 'Product sold', '2024-09-22 19:32:45'),
(918, 'admin', 'Product sold', '2024-09-22 19:33:26'),
(919, 'admin', 'Supplier Culligan Water added', '2024-09-22 19:48:38'),
(920, 'admin', 'Delivery Added', '2024-09-22 20:13:19'),
(921, 'admin', 'Product sold', '2024-09-22 20:15:51'),
(922, 'admin', 'User admin login', '2024-10-10 15:43:19'),
(923, 'admin', 'User admin login', '2024-10-17 11:49:19'),
(924, 'admin', 'User admin login', '2024-10-17 11:50:19'),
(925, 'admin', 'User admin login', '2024-10-17 12:29:52'),
(926, 'admin', 'Product sold', '2024-10-17 12:39:20'),
(927, 'admin', 'User admin login', '2024-11-18 17:01:57'),
(928, 'admin', 'User admin login', '2024-11-19 10:32:55'),
(929, 'admin', 'Delivery Added', '2024-11-19 10:59:57'),
(930, 'admin', 'Delivery Added', '2024-11-19 11:44:04'),
(931, 'admin', 'Product sold', '2024-11-19 11:46:09'),
(932, 'admin', 'Delivery Added', '2024-11-19 11:47:01'),
(933, 'admin', 'Delivery Added', '2024-11-19 12:05:43'),
(934, 'admin', 'Delivery Added', '2024-11-19 12:10:49'),
(935, 'admin', 'Delivery Added', '2024-11-19 12:30:10'),
(936, 'admin', 'Delivery Added', '2024-11-19 12:35:50'),
(937, 'admin', 'Delivery Added', '2024-11-19 12:39:04'),
(938, 'admin', 'Delivery Added', '2024-11-19 12:40:50'),
(939, 'admin', 'Delivery Added', '2024-11-19 12:43:12'),
(940, 'admin', 'User admin login', '2024-11-19 12:46:55'),
(941, 'admin', 'Delivery Added', '2024-11-19 12:54:40'),
(942, 'admin', 'Delivery Added', '2024-11-19 13:02:46'),
(943, 'admin', 'Delivery Added', '2024-11-19 13:18:25'),
(944, 'admin', 'Delivery Added', '2024-11-19 13:27:43'),
(945, 'admin', 'User admin login', '2025-01-06 21:09:46'),
(946, 'admin', 'User admin login', '2025-01-06 21:09:47'),
(947, 'admin', 'Product Chair updated', '2025-01-06 21:10:39'),
(948, 'admin', 'Delivery Added', '2025-01-07 18:37:16'),
(949, 'admin', 'Delivery Added', '2025-01-07 18:38:51'),
(950, 'admin', 'Product sold', '2025-01-08 10:44:00'),
(951, 'admin', 'Delivery Added', '2025-01-09 12:20:00'),
(952, 'admin', 'Delivery Added', '2025-01-09 12:27:53'),
(953, 'admin', 'Delivery Added', '2025-01-09 12:30:33'),
(954, 'admin', 'Delivery Added', '2025-01-09 12:47:38'),
(955, 'admin', 'Delivery Added', '2025-01-09 12:53:01'),
(956, 'admin', 'User admin login', '2025-01-10 09:02:35'),
(957, 'admin', 'User admin login', '2025-01-10 10:07:51'),
(958, 'admin', 'Delivery Added', '2025-01-10 10:53:14'),
(959, 'admin', 'Delivery Added', '2025-01-10 11:27:56'),
(960, 'admin', 'Delivery Added', '2025-01-10 11:32:01'),
(961, 'admin', 'Delivery Added', '2025-01-10 11:34:37'),
(962, 'admin', 'Delivery Added', '2025-01-10 12:09:49'),
(963, 'admin', 'Delivery Added', '2025-01-10 12:15:55'),
(964, 'admin', 'Delivery Added', '2025-01-10 12:34:02'),
(965, 'admin', 'Delivery Added', '2025-01-10 12:36:34'),
(966, 'admin', 'Product Coffee updated', '2025-01-10 13:12:31'),
(967, 'admin', 'Product Coffee updated', '2025-01-10 13:12:44'),
(968, 'admin', 'Product Coffee updated', '2025-01-10 13:16:26'),
(969, 'admin', 'Product Coffee updated', '2025-01-10 13:24:34'),
(970, 'admin', 'Delivery Added', '2025-01-10 17:27:02'),
(971, 'admin', 'Delivery Added', '2025-01-10 17:30:57'),
(972, 'admin', 'Delivery Added', '2025-01-10 17:45:11'),
(973, 'admin', 'Delivery Added', '2025-01-10 17:48:12'),
(974, 'admin', 'Delivery Added', '2025-01-10 17:56:55'),
(975, 'admin', 'Product asdas updated', '2025-01-10 18:13:28'),
(976, 'admin', 'Product Chair updated', '2025-01-10 18:14:28'),
(977, 'admin', 'Product sold', '2025-01-11 12:21:46'),
(978, 'admin', 'Product sold', '2025-01-11 12:23:20'),
(979, 'admin', 'Product Milk updated', '2025-01-14 11:53:17'),
(980, 'admin', 'Product Honey updated', '2025-01-14 11:55:02'),
(981, 'admin', 'Product sold', '2025-01-14 13:34:34'),
(982, 'admin', 'Product sold', '2025-01-14 13:38:39'),
(983, 'admin', 'Product sold', '2025-01-14 13:41:26'),
(984, 'admin', 'Product sold', '2025-01-14 13:42:29'),
(985, 'admin', 'Product sold', '2025-01-14 20:02:00'),
(986, 'admin', 'Product sold', '2025-01-14 20:03:52'),
(987, 'admin', 'Product sold', '2025-01-14 20:05:01'),
(988, 'admin', 'Product sold', '2025-01-14 20:05:39'),
(989, 'admin', 'Product sold', '2025-01-14 20:06:35'),
(990, 'admin', 'Product sold', '2025-01-14 20:14:06'),
(991, 'admin', 'Product sold', '2025-01-14 21:13:49'),
(992, 'admin', 'User admin login', '2025-01-24 09:04:30'),
(993, 'admin', 'User admin login', '2025-01-29 14:46:14'),
(994, 'admin', 'User admin login', '2025-02-06 09:40:12'),
(995, 'admin', 'User admin login', '2025-04-04 07:09:23'),
(997, 'kiruikev', 'User kiruikev login', '2025-04-04 07:12:21'),
(998, 'kiruikev', 'Product sold', '2025-04-04 07:23:49'),
(999, 'kiruikev', 'User kiruikev login', '2025-04-04 07:36:57'),
(1000, 'kiruikev', 'User kiruikev login', '2025-04-04 08:33:25'),
(1001, 'kiruikev', 'User kiruikev login', '2025-04-04 08:42:29'),
(1002, 'kiruikev', 'User kiruikev login', '2025-04-04 08:44:06'),
(1003, 'kiruikev', 'User kiruikev login', '2025-04-04 08:44:07'),
(1004, 'kiruikev', 'Product sold', '2025-04-04 08:50:34'),
(1005, 'kiruikev', 'Product sold', '2025-04-04 08:53:01'),
(1006, 'admin', 'User admin login', '2025-04-04 08:54:57'),
(1007, 'kiruikev', 'User kiruikev login', '2025-04-04 09:06:51'),
(1008, 'admin', 'User admin login', '2025-04-04 09:38:30'),
(1009, 'kiruikev', 'User kiruikev login', '2025-04-04 09:39:09'),
(1010, 'admin', 'User admin login', '2025-04-05 19:08:58'),
(1011, 'admin', 'Product sold', '2025-04-05 19:10:56'),
(1012, 'admin', 'User admin login', '2025-04-05 19:13:06'),
(1013, 'kiruikev', 'User kiruikev login', '2025-04-05 19:20:27'),
(1014, 'kiruikev', 'User kiruikev login', '2025-04-05 19:21:16'),
(1015, 'kiruikev', 'User kiruikev logout', '2025-04-05 19:29:33'),
(1016, 'admin', 'User admin login', '2025-04-05 19:29:50'),
(1017, 'admin', 'User admin login', '2025-04-05 19:34:01'),
(1018, 'kiruikev', 'User kiruikev login', '2025-04-05 19:38:00'),
(1019, 'kiruikev', 'User kiruikev login', '2025-04-05 19:38:13'),
(1020, 'kiruikev', 'User kiruikev login', '2025-04-05 20:03:51'),
(1021, 'kiruikev', 'User kiruikev login', '2025-04-05 20:09:46'),
(1022, 'admin', 'User admin login', '2025-04-05 20:25:57'),
(1023, 'kiruikev', 'User kiruikev login', '2025-04-05 20:32:50'),
(1024, 'admin', 'User admin login', '2025-04-06 23:47:24'),
(1025, 'kiruikev', 'User kiruikev login', '2025-04-28 23:40:29'),
(1026, 'admin', 'User admin login', '2025-04-28 23:41:07'),
(1027, 'admin', 'User admin login', '2026-02-21 22:44:17'),
(1028, 'admin', 'User admin login', '2026-02-21 23:00:56'),
(1030, 'smoothdime', 'User smoothdime login', '2026-02-21 23:10:31'),
(1031, 'smoothdime', 'Product sold', '2026-02-22 08:37:27'),
(1032, 'smoothdime', 'Product sold', '2026-02-22 08:46:11'),
(1033, 'kiruikev', 'User kiruikev login', '2026-02-22 17:58:29'),
(1034, 'admin', 'User admin login', '2026-02-22 17:59:53'),
(1035, 'admin', 'Product sold', '2026-02-22 18:19:34'),
(1039, 'kiruikev123', 'User kiruikev123 login', '2026-02-24 12:38:11'),
(1040, 'kiruikev123', 'User kiruikev123 login', '2026-02-24 12:39:15'),
(1041, 'kiruikev123', 'User kiruikev123 login', '2026-02-24 12:40:40'),
(1042, 'kiruikev123', 'Product sold', '2026-02-24 12:44:01'),
(1043, 'kiruikev123', 'User kiruikev123 login', '2026-02-24 12:45:52'),
(1045, 'mike123', 'User mike123 login', '2026-02-24 12:48:53'),
(1046, 'kiruikev123', 'User kiruikev123 login', '2026-02-24 12:58:46'),
(1047, 'kevin123', 'User kevin123 login', '2026-03-19 11:55:03'),
(1048, 'kevin123', 'User kevin123 login', '2026-03-19 11:55:38'),
(1049, 'kevin123', 'Product sold', '2026-03-19 11:58:48'),
(1050, 'kevin123', 'User kevin123 login', '2026-03-19 14:09:55'),
(1051, 'kevin123', 'Product sold', '2026-03-19 14:10:26'),
(1052, 'mike123', 'User mike123 login', '2026-03-19 14:12:29'),
(1053, 'kevin123', 'User kevin123 login', '2026-03-19 14:15:44'),
(1054, 'kevin123', 'User kevin123 login', '2026-03-19 16:21:31'),
(1055, 'mike123', 'User mike123 login', '2026-03-19 16:33:14'),
(1056, 'kevin123', 'User kevin123 login', '2026-03-19 16:43:08'),
(1057, 'kevin123', 'Product sold', '2026-03-19 16:43:30'),
(1058, 'kevin123', 'User kevin123 login', '2026-03-19 16:44:57'),
(1059, 'mike123', 'User mike123 login', '2026-03-19 16:45:24'),
(1061, 'kiprono04', 'User kiprono04 login', '2026-09-29 20:08:41'),
(1063, 'test', 'User test login', '2026-10-02 10:24:26'),
(1064, 'test', 'User test login', '2026-10-21 14:05:16'),
(1065, 'test', 'Product sold', '2026-10-21 14:05:57'),
(1066, 'test', 'User test login', '2026-10-21 14:08:07'),
(1067, 'test', 'User test login', '2026-10-21 14:14:14'),
(1068, 'test', 'User test login', '2026-10-21 14:14:42'),
(1069, 'test', 'User test login', '2026-10-21 14:15:31'),
(1071, 'kirui1', 'User kirui1 login', '2026-10-21 14:16:39'),
(1072, 'kirui1', 'User kirui1 login', '2026-10-21 14:20:40'),
(1073, 'kirui1', 'User kirui1 login', '2026-10-21 14:21:15'),
(1074, 'kirui1', 'User kirui1 login', '2026-10-21 14:23:36'),
(1075, 'kirui1', 'User kirui1 login', '2026-10-21 14:41:18'),
(1076, 'kirui1', 'User kirui1 login', '2026-10-21 14:58:00'),
(1077, 'kirui1', 'User kirui1 login', '2026-10-21 15:00:24'),
(1078, 'kirui1', 'User kirui1 login', '2026-10-21 15:11:00'),
(1080, 'kiprono04', 'User kiprono04 login', '2026-11-04 20:39:21'),
(1081, 'kiprono04', 'User kiprono04 login', '2026-11-04 20:49:36'),
(1082, 'kiprono04', 'User kiprono04 login', '2026-11-04 20:59:30'),
(1083, 'kiprono04', 'User kiprono04 login', '2026-11-04 20:59:51'),
(1084, 'kiprono04', 'User kiprono04 login', '2026-11-04 21:01:01'),
(1086, 'kiprono04', 'User kiprono04 login', '2026-11-04 21:03:19'),
(1087, 'kiprono04', 'User kiprono04 login', '2026-11-04 21:08:38'),
(1088, 'kiprono04', 'User kiprono04 login', '2026-11-04 21:08:53');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_no` varchar(50) NOT NULL,
  `product_name` varchar(30) DEFAULT NULL,
  `sell_price` varchar(100) DEFAULT NULL,
  `minimum_price` varchar(100) NOT NULL,
  `buying_price` varchar(100) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `unit` varchar(30) DEFAULT NULL,
  `min_stocks` int(11) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL,
  `Expiry_Date` varchar(100) NOT NULL,
  `images` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_no`, `product_name`, `sell_price`, `minimum_price`, `buying_price`, `quantity`, `unit`, `min_stocks`, `remarks`, `location`, `Expiry_Date`, `images`) VALUES
('1', 'Festive Bread 800g', '100', '90', '75', 46, 'pcs', 10, 'Sliced white bread', 'Shelf A1', '2026-03-01', ''),
('10', 'Candy Lollipops', '10', '8', '6', 200, 'pcs', 50, 'Assorted flavours', 'Shelf C1', '2026-12-31', ''),
('11', 'Dextrose Candy', '5', '4', '3', 300, 'pcs', 100, 'Glucose sweets', 'Shelf C1', '2026-12-31', ''),
('12', 'Orbit Chewing Gum', '15', '12', '10', 98, 'pcs', 30, 'Spearmint flavour', 'Shelf C1', '2026-12-31', ''),
('13', 'Digestive Biscuits 200g', '150', '130', '110', 36, 'pcs', 10, 'McVities digestive', 'Shelf A2', '2026-09-30', ''),
('14', 'Cream Crackers 200g', '90', '80', '68', 40, 'pcs', 10, 'Cabin biscuits', 'Shelf A2', '2026-09-30', ''),
('15', 'Hob Nobs 150g', '80', '70', '60', 40, 'pcs', 10, 'Oat biscuits', 'Shelf A2', '2026-09-30', ''),
('16', 'Unga Hostess 2kg', '180', '165', '150', 30, 'pcs', 10, 'Wheat flour', 'Shelf D1', '2026-06-30', ''),
('17', 'Pembe Maize Flour 2kg', '160', '148', '135', 30, 'pcs', 10, 'Maize flour', 'Shelf D1', '2026-06-30', ''),
('18', 'Cooking Oil 1L (Elianto)', '280', '260', '240', 20, 'pcs', 5, 'Sunflower oil', 'Shelf D2', '2026-12-31', ''),
('19', 'Salt 500g (Kensalt)', '35', '30', '25', 50, 'pcs', 15, 'Iodised salt', 'Shelf D2', '2027-12-31', ''),
('2', 'Festive Bread 100g', '60', '55', '45', 60, 'pcs', 15, 'Small loaf', 'Shelf A1', '2026-03-01', ''),
('20', 'Sugar 1kg', '150', '138', '125', 25, 'pcs', 10, 'White sugar', 'Shelf D1', '2027-12-31', ''),
('21', 'Cocoa Milo 400g', '480', '440', '400', 15, 'pcs', 5, 'Chocolate malt drink', 'Shelf E1', '2026-10-31', ''),
('22', 'Ketepa Tea 100 bags', '220', '200', '180', 20, 'pcs', 5, 'Kenya tea bags', 'Shelf E1', '2026-12-31', ''),
('23', 'Nescafe Classic 100g', '380', '350', '320', 15, 'pcs', 5, 'Instant coffee', 'Shelf E1', '2026-12-31', ''),
('24', 'Ribena Blackcurrant 500ml', '120', '110', '95', 30, 'pcs', 10, 'Juice concentrate', 'Shelf E2', '2026-08-31', ''),
('25', 'Colgate Toothpaste 75ml', '120', '110', '95', 20, 'pcs', 10, 'Cavity protection', 'Shelf F1', '2027-06-30', ''),
('26', 'Dettol Soap 125g', '90', '82', '72', 30, 'pcs', 10, 'Antibacterial soap', 'Shelf F1', '2027-12-31', ''),
('27', 'Lifebuoy Soap 100g', '70', '63', '55', 30, 'pcs', 10, 'Protection soap', 'Shelf F1', '2027-12-31', ''),
('28', 'Matches (Kapa)', '10', '8', '6', 100, 'pcs', 30, 'Safety matches', 'Shelf G1', '2028-12-31', ''),
('29', 'Omo Detergent 500g', '180', '165', '148', 20, 'pcs', 5, 'Washing powder', 'Shelf G1', '2027-12-31', ''),
('3', 'Super Loaf 800g', '100', '90', '75', 50, 'pcs', 10, 'Whole wheat loaf', 'Shelf A1', '2026-03-01', ''),
('30', 'Scotch Brite Sponge', '55', '48', '40', 40, 'pcs', 10, 'Kitchen sponge', 'Shelf G1', '2027-12-31', ''),
('31', 'Pringles Original 110g', '320', '295', '268', 20, 'pcs', 5, 'Potato crisps', 'Shelf A3', '2026-08-31', ''),
('32', 'Nik Naks 100g', '80', '72', '62', 30, 'pcs', 10, 'Corn snack', 'Shelf A3', '2026-08-31', ''),
('33', 'Smoky Bacon Crisps 100g', '70', '63', '54', 30, 'pcs', 10, 'Flavoured crisps', 'Shelf A3', '2026-08-31', ''),
('34', 'Kensalt Baked Beans 400g', '120', '110', '95', 24, 'pcs', 8, 'In tomato sauce', 'Shelf H1', '2027-06-30', ''),
('35', 'Safi Sardines 155g', '90', '82', '70', 24, 'pcs', 8, 'In tomato sauce', 'Shelf H1', '2027-06-30', ''),
('36', 'Cerelac Wheat 400g', '380', '350', '320', 10, 'pcs', 5, 'Baby cereal 6m+', 'Shelf I1', '2026-12-31', ''),
('37', 'Pampers Size 3 (10pcs)', '320', '295', '268', 10, 'pcs', 5, 'Baby diapers', 'Shelf I1', '2026-12-31', ''),
('4', 'Super Loaf 100g', '60', '55', '45', 60, 'pcs', 15, 'Mini loaf', 'Shelf A1', '2026-03-01', ''),
('5', 'Broad Ways Bread 800g', '100', '90', '75', 40, 'pcs', 10, 'Brown bread', 'Shelf A1', '2026-03-01', ''),
('6', 'Broad Ways Bread 100g', '60', '55', '45', 50, 'pcs', 15, 'Mini brown bread', 'Shelf A1', '2026-03-01', ''),
('7', 'Brookside Milk 500ml', '55', '50', '42', 80, 'pcs', 20, 'Fresh pasteurised milk', 'Fridge B1', '2026-02-28', ''),
('8', 'Mount Kenya Milk 500ml', '45', '40', '35', 80, 'pcs', 20, 'Fresh milk', 'Fridge B1', '2026-02-28', ''),
('9', 'Gold Crown Milk 500ml', '40', '35', '30', 80, 'pcs', 20, 'Fortified milk', 'Fridge B1', '2026-02-28', '');

--
-- Triggers `products`
--
DELIMITER $$
CREATE TRIGGER `delete` BEFORE DELETE ON `products` FOR EACH ROW DELETE FROM initial_products WHERE id=old.product_no
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `insert` AFTER INSERT ON `products` FOR EACH ROW INSERT INTO initial_products(id,initial_quantity) VALUES(new.product_no,new.quantity)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `product_delivered`
--

CREATE TABLE `product_delivered` (
  `transaction_no` varchar(30) NOT NULL,
  `product_id` varchar(30) NOT NULL,
  `total_qty` int(11) NOT NULL,
  `buy_price` decimal(18,2) NOT NULL,
  `tax_rate` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `product_delivered`
--

INSERT INTO `product_delivered` (`transaction_no`, `product_id`, `total_qty`, `buy_price`, `tax_rate`) VALUES
('5CAAFDA8CD697', '1001', 100, 20.00, 10),
('5CAAFDEEDB333', '10011', 200, 500.00, 20),
('5CAAFDEEDB333', '10012', 100, 2000.00, 20),
('5CAAFE37D21E8', '1', 100, 10.00, 20),
('5CAAFE37D21E8', '2', 200, 20.00, 20),
('5CAAFE37D21E8', '3', 150, 6.00, 10),
('5CAAFE37D21E8', '4', 125, 15.00, 15),
('5CAAFE37D21E8', '5', 100, 10.00, 20),
('5E7F00084C934', '23213', 23, 32.00, 32),
('5E81DF2B7B8F7', '10000', 21, 21313.00, 20),
('66F04F8564B8C', '101', 2000, 100.00, 21),
('5CAAFDA8CD697', '1001', 100, 20.00, 10),
('5CAAFDEEDB333', '10011', 200, 500.00, 20),
('5CAAFDEEDB333', '10012', 100, 2000.00, 20),
('5CAAFE37D21E8', '1', 100, 10.00, 20),
('5CAAFE37D21E8', '2', 200, 20.00, 20),
('5CAAFE37D21E8', '3', 150, 6.00, 10),
('5CAAFE37D21E8', '4', 125, 15.00, 15),
('5CAAFE37D21E8', '5', 100, 10.00, 20),
('5E7F00084C934', '23213', 23, 32.00, 32),
('5E81DF2B7B8F7', '10000', 21, 21313.00, 20),
('66F04F8564B8C', '101', 2000, 100.00, 21),
('673B48EE9028F', 'S9SJS9S', 5, 0.07, 2),
('673C4FC4A8782', 'S9SJS9S', 5, 0.07, 2),
('673C5070BD2D5', 'S9SJS9S', 5, 0.07, 2),
('673C54D833A87', 'S9SJS9S', 5, 0.07, 2),
('673C5607B3577', 'S9SJS9S', 5, 0.07, 2),
('673C5A934A4AE', 'S9SJS9S', 5, 0.07, 2),
('673C5BED556AD', 'S9SJS9S', 5, 0.07, 2),
('673C5CA9A67BC', 'S9SJS9S', 5, 0.09, 2),
('673C5D11CE505', 'S9SJS9S', 5, 0.08, 2),
('673C5D8F64B2E', 'S9SJS9S', 5, 0.09, 2),
('673C604CC9DCF', '4', 98, 15.00, 15),
('673C62352785B', '4', 98, 15.00, 15),
('673C5DB72B142', 'S9SJS9S', 5, 0.07, 2),
('673C67D4DAF21', 'S9SJS9S', 10, 0.07, 2),
('677D4A7FE6927', '4', 196, 15.00, 15),
('6780E52897F18', '60', 30, 1000.00, 2),
('6780E961C1A68', '10', 40, 100.00, 2),
('6780E9F290444', '40', 392, 15.00, 15),
('678132EFDA7EC', '70', 100, 200.00, 2),
('67813501BF839', '12', 20, 30.00, 1),
('67813501BF839', '13', 10, 50.00, 3);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `reciept_no` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL DEFAULT 0,
  `username` varchar(30) NOT NULL,
  `discount` int(11) NOT NULL,
  `payment_method` varchar(100) NOT NULL,
  `total` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`reciept_no`, `customer_id`, `username`, `discount`, `payment_method`, `total`, `date`) VALUES
(20, 16, 'admin', 0, 'Cash', 0, '2025-01-08 07:48:30'),
(21, 16, 'admin', 0, 'Cash', 0, '2025-01-08 07:48:34'),
(22, 16, 'admin', 0, 'Cash', 0, '2025-01-08 07:48:37'),
(23, 16, 'admin', 0, 'MPESA', 0, '2025-01-08 07:49:21'),
(24, 16, 'admin', 0, 'MPESA', 0, '2025-01-08 07:49:24'),
(25, 16, 'admin', 10, 'MPESA', 0, '2025-01-08 07:49:28'),
(26, 16, 'admin', 10, 'Cash', 0, '2025-01-08 07:48:40'),
(27, 16, 'admin', 10, 'Cash', 2160, '2025-01-08 07:49:35'),
(28, 16, 'admin', 20, 'MPESA', 1920, '2025-01-08 07:49:16'),
(29, 16, 'admin', 20, 'MPESA', 4017, '2025-01-08 07:49:12'),
(30, 16, 'admin', 3, 'Cash', 81, '2025-01-08 07:48:43'),
(31, 16, 'admin', 0, 'MPESA', 20, '2025-01-08 07:49:07'),
(32, 16, 'admin', 0, 'MPESA', 66, '2025-01-08 07:49:03'),
(33, 16, 'admin', 16, 'Cash', 157, '2025-01-08 07:48:46'),
(34, 16, 'admin', 10, 'MPESA', 23, '2025-01-08 07:48:59'),
(35, 16, 'admin', 0, 'MPESA', 69, '2025-01-08 07:48:54'),
(36, 16, 'admin', 0, 'Cash', 40, '2024-09-14 09:12:12'),
(37, 16, 'admin', 0, 'MPESA', 69, '2024-09-14 09:15:21'),
(38, 16, 'admin', 0, 'Cash', 53, '2024-09-15 14:13:13'),
(39, 16, 'admin', 0, 'MPESA', 7200, '2024-09-15 14:13:57'),
(40, 16, 'admin', 0, 'Cash', 69, '2024-09-16 17:56:56'),
(41, 16, 'admin', 0, 'Cash', 7, '2024-09-18 08:50:56'),
(42, 16, 'admin', 0, 'Cash', 66, '2024-09-18 09:37:33'),
(43, 16, 'admin', 0, 'Cash', 66, '2024-09-22 16:32:45'),
(44, 16, 'admin', 0, 'MPESA', 35, '2024-09-22 16:33:26'),
(45, 16, 'admin', 0, 'Cash', 200, '2024-09-22 17:15:51'),
(46, 16, 'admin', 0, 'Cash', 400, '2024-10-17 09:39:20'),
(47, 16, 'admin', 0, 'Cash', 16800, '2024-11-19 08:46:08'),
(48, 16, 'admin', 0, 'Cash', 440, '2025-01-08 07:44:00'),
(49, 16, 'admin', 0, 'Cash', 0, '2025-01-11 09:21:46'),
(50, 16, 'admin', 0, 'Cash', 22, '2025-01-11 09:23:20'),
(55, 16, 'admin', 0, 'Cash', 150, '2025-01-14 09:57:09'),
(56, 16, 'admin', 0, 'MPESA', 210, '2025-01-14 10:01:32'),
(57, 16, 'admin', 0, 'MPESA', 210, '2025-01-14 10:01:51'),
(58, 16, 'admin', 0, 'Cash', 210, '2025-01-14 10:02:05'),
(59, 16, 'admin', 0, 'Cash', 60, '2025-01-14 10:12:19'),
(60, 16, 'admin', 0, 'Cash', 100, '2025-01-14 10:13:04'),
(61, 16, 'admin', 0, 'Cash', 1260, '2025-01-14 10:16:23'),
(62, 16, 'admin', 0, 'Cash', 150, '2025-01-14 10:28:32'),
(63, 16, 'admin', 0, 'Cash', 120, '2025-01-14 10:34:34'),
(64, 16, 'admin', 0, 'Cash', 120, '2025-01-14 10:38:39'),
(65, 16, 'admin', 0, 'Cash', 150, '2025-01-14 10:41:26'),
(66, 16, 'admin', 0, 'Cash', 250, '2025-01-14 10:42:28'),
(67, 16, 'admin', 0, 'Cash', 150, '2025-01-14 17:01:59'),
(68, 16, 'admin', 0, 'Cash', 60, '2025-01-14 17:03:51'),
(69, 16, 'admin', 0, 'Cash', 60, '2025-01-14 17:05:01'),
(70, 16, 'admin', 0, 'Cash', 300, '2025-01-14 17:05:39'),
(71, 16, 'admin', 0, 'Cash', 50, '2025-01-14 17:06:35'),
(72, 16, 'admin', 0, 'Cash', 120, '2025-01-14 17:14:06'),
(73, 16, 'admin', 0, 'Cash', 420, '2025-01-14 18:13:49'),
(74, 16, 'kiruikev', 0, 'Cash', 200, '2025-04-04 04:23:49'),
(75, 16, 'kiruikev', 0, 'Cash', 600, '2025-04-04 05:50:33'),
(76, 16, 'kiruikev', 0, 'Cash', 0, '2025-04-04 05:53:01'),
(77, 16, 'admin', 0, 'Cash', 300, '2025-04-05 16:10:55'),
(78, 16, 'smoothdime', 0, 'Cash', 700, '2026-02-22 05:37:27'),
(79, 16, 'smoothdime', 0, 'MPESA', 1200, '2026-02-22 05:46:11'),
(80, 16, 'admin', 0, 'Cash', 100, '2026-02-22 15:19:34'),
(81, 16, 'kiruikev123', 0, 'Cash', 450, '2026-02-24 09:44:01'),
(82, 16, 'kevin123', 0, 'Cash', 150, '2026-03-19 08:58:48'),
(83, 16, 'kevin123', 0, 'Cash', 100, '2026-03-19 11:10:26'),
(84, 16, 'kevin123', 0, 'Cash', 15, '2026-03-19 13:43:30'),
(85, 16, 'test', 0, 'Cash', 215, '2026-10-21 11:05:57');

-- --------------------------------------------------------

--
-- Table structure for table `sales_product`
--

CREATE TABLE `sales_product` (
  `reciept_no` int(11) NOT NULL,
  `product_id` varchar(20) NOT NULL,
  `price` decimal(18,2) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `sales_product`
--

INSERT INTO `sales_product` (`reciept_no`, `product_id`, `price`, `qty`) VALUES
(20, '1', 12.00, 10),
(20, '10011', 600.00, 2),
(21, '1', 12.00, 20),
(22, '10012', 2400.00, 1),
(23, '3', 6.60, 2),
(24, '10012', 2400.00, 1),
(25, '10012', 2400.00, 1),
(26, '10012', 2400.00, 1),
(27, '10012', 2400.00, 1),
(28, '10012', 2400.00, 1),
(29, '10012', 2400.00, 2),
(29, '4', 17.25, 3),
(29, '23213', 42.24, 4),
(30, '23213', 42.24, 2),
(31, '3', 6.60, 3),
(32, '3', 6.60, 10),
(33, '4', 17.25, 10),
(34, '3', 6.60, 5),
(35, '4', 17.25, 4),
(36, '3', 6.60, 6),
(37, '4', 17.25, 4),
(38, '3', 6.60, 8),
(39, '10012', 2400.00, 3),
(40, '4', 17.25, 4),
(41, '3', 6.60, 1),
(42, '3', 6.60, 10),
(43, '3', 6.60, 10),
(44, '4', 17.25, 2),
(45, '101', 200.00, 1),
(20, '1', 12.00, 10),
(20, '10011', 600.00, 2),
(21, '1', 12.00, 20),
(22, '10012', 2400.00, 1),
(23, '3', 6.60, 2),
(24, '10012', 2400.00, 1),
(25, '10012', 2400.00, 1),
(26, '10012', 2400.00, 1),
(27, '10012', 2400.00, 1),
(28, '10012', 2400.00, 1),
(29, '10012', 2400.00, 2),
(29, '4', 17.25, 3),
(29, '23213', 42.24, 4),
(30, '23213', 42.24, 2),
(31, '3', 6.60, 3),
(32, '3', 6.60, 10),
(33, '4', 17.25, 10),
(34, '3', 6.60, 5),
(35, '4', 17.25, 4),
(36, '3', 6.60, 6),
(37, '4', 17.25, 4),
(38, '3', 6.60, 8),
(39, '10012', 2400.00, 3),
(40, '4', 17.25, 4),
(41, '3', 6.60, 1),
(42, '3', 6.60, 10),
(43, '3', 6.60, 10),
(44, '4', 17.25, 2),
(45, '101', 200.00, 1),
(46, '101', 200.00, 2),
(47, '10012', 2400.00, 7),
(48, '1001', 22.00, 20),
(49, '12', 30.30, 1),
(50, '1001', 22.00, 1),
(63, '12', 120.00, 2),
(64, '12', 120.00, 2),
(65, '13', 150.00, 1),
(66, '12', 60.00, 1),
(66, '12', 240.00, 4),
(67, '13', 150.00, 1),
(68, '12', 60.00, 1),
(69, '12', 60.00, 1),
(70, '12', 300.00, 5),
(71, '12', 60.00, 1),
(72, '13', 150.00, 1),
(73, '13', 300.00, 2),
(73, '13', 150.00, 1),
(74, '1', 200.00, 2),
(75, '1', 600.00, 6),
(76, '40', 0.00, 5),
(77, '1', 300.00, 3),
(78, '1', 700.00, 7),
(79, '10011', 1200.00, 2),
(80, '1', 100.00, 1),
(81, '13', 450.00, 3),
(82, '13', 150.00, 1),
(83, '1', 100.00, 1),
(84, '12', 15.00, 1),
(85, '1', 200.00, 2),
(85, '12', 15.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `supplier_id` int(11) NOT NULL,
  `company_name` varchar(30) DEFAULT NULL,
  `firstname` varchar(30) DEFAULT NULL,
  `lastname` varchar(30) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `contact_number` varchar(30) DEFAULT NULL,
  `image` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`supplier_id`, `company_name`, `firstname`, `lastname`, `address`, `contact_number`, `image`) VALUES
(21, 'OrangeCompany', 'Oracle', 'LTD', 'USA', '+63(09)1234-1234', 'Internship-Web-Graphic-01.png'),
(22, 'BrandName', 'Bill', 'Joe', 'Africa', '+63(09)1234-1234', 'multi-user-icon.png'),
(23, 'Culligan Water', 'Culligan ', 'Water', 'Mountain View', '0743928989', 'wozza.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `position` varchar(20) NOT NULL,
  `contact_number` varchar(30) NOT NULL,
  `image` varchar(30) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `firstname`, `lastname`, `position`, `contact_number`, `image`, `password`) VALUES
(1, 'kevin123', 'Kevin', 'Kirui', 'Admin', '0743928989', 'WhatsApp Image 2026-02-07 at 1', 'e98f1ad831802c9d52c83ee4a9627d2b'),
(2, 'admin', 'admin', 'admin', 'admin', '0747289278', 'work.png', '21232f297a57a5a743894a0e4a801fc3\n'),
(3, 'kiruikev123', 'Kevin', 'Kirui', 'Admin', '0743928989', 'WhatsApp Image 2026-02-07 at 1', '21cfe03f294aaf3d2beb51ca428bec3b'),
(4, 'mike123', 'Mike', 'Langat', 'Employee', '0743828989', 'WhatsApp Image 2026-02-11 at 0', '21cfe03f294aaf3d2beb51ca428bec3b'),
(5, 'kiprono04', 'Kevin', 'Kirui', 'admin', '743928989', 'work.jpeg', 'e98f1ad831802c9d52c83ee4a9627d2b'),
(6, 'test', 'test', 'test', 'Admin', '0732460192', 'pic3.jpeg', 'c86c84f6c58de3456398d8d9a77972d0'),
(7, 'kirui1', 'ngeno', 'kirui', 'Employee', '0743928989', 'wp7346938 31.png', 'e98f1ad831802c9d52c83ee4a9627d2b'),
(8, 'debbie', 'Debbie', 'Odaka', 'Admin', '0743928989', 'wp7346938 31.png', '8532c6650095e25395b312a7eeb2bccc'),
(9, 'test1', 'testt', 'testt', 'Admin', '0743928989', 'CamScanner 30-10-2026 12.34_1.', 'e98f1ad831802c9d52c83ee4a9627d2b');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cashflow`
--
ALTER TABLE `cashflow`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `closeofdaysummary`
--
ALTER TABLE `closeofdaysummary`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `delivery`
--
ALTER TABLE `delivery`
  ADD PRIMARY KEY (`transaction_no`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `initial_products`
--
ALTER TABLE `initial_products`
  ADD KEY `id` (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_no`);

--
-- Indexes for table `product_delivered`
--
ALTER TABLE `product_delivered`
  ADD KEY `product_id` (`product_id`),
  ADD KEY `transaction_no` (`transaction_no`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`reciept_no`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `sales_product`
--
ALTER TABLE `sales_product`
  ADD KEY `product_id` (`product_id`),
  ADD KEY `reciept_no` (`reciept_no`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD UNIQUE KEY `user_id` (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cashflow`
--
ALTER TABLE `cashflow`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `closeofdaysummary`
--
ALTER TABLE `closeofdaysummary`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1089;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `reciept_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `sales_product`
--
ALTER TABLE `sales_product`
  MODIFY `reciept_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cashflow`
--
ALTER TABLE `cashflow`
  ADD CONSTRAINT `cashflow_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`);

--
-- Constraints for table `delivery`
--
ALTER TABLE `delivery`
  ADD CONSTRAINT `delivery_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`supplier_id`),
  ADD CONSTRAINT `delivery_ibfk_2` FOREIGN KEY (`username`) REFERENCES `users` (`username`);

--
-- Constraints for table `initial_products`
--
ALTER TABLE `initial_products`
  ADD CONSTRAINT `initial_products_ibfk_1` FOREIGN KEY (`id`) REFERENCES `products` (`product_no`);

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`);

--
-- Constraints for table `product_delivered`
--
ALTER TABLE `product_delivered`
  ADD CONSTRAINT `product_delivered_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_no`),
  ADD CONSTRAINT `product_delivered_ibfk_2` FOREIGN KEY (`transaction_no`) REFERENCES `delivery` (`transaction_no`);

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`),
  ADD CONSTRAINT `sales_ibfk_3` FOREIGN KEY (`username`) REFERENCES `users` (`username`);

--
-- Constraints for table `sales_product`
--
ALTER TABLE `sales_product`
  ADD CONSTRAINT `sales_product_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_no`),
  ADD CONSTRAINT `sales_product_ibfk_3` FOREIGN KEY (`reciept_no`) REFERENCES `sales` (`reciept_no`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;



CREATE TABLE mpesa_payments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    checkout_request_id VARCHAR(120) NOT NULL UNIQUE,
    merchant_request_id VARCHAR(120) DEFAULT NULL,
    phone VARCHAR(15) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,

    status ENUM(
        'pending',
        'reported_success',
        'failed',
        'cancelled',
        'timed_out',
        'needs_review'
    ) NOT NULL DEFAULT 'pending',

    result_code INT DEFAULT NULL,
    result_desc VARCHAR(255) DEFAULT NULL,
    mpesa_receipt VARCHAR(50) DEFAULT NULL UNIQUE,
    callback_amount DECIMAL(10,2) DEFAULT NULL,
    callback_phone VARCHAR(15) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);

ALTER TABLE mpesa_payments
ADD COLUMN cart_snapshot JSON NULL,
ADD COLUMN cashier_username VARCHAR(30) NULL,
ADD COLUMN sale_receipt_no INT NULL,
ADD COLUMN finalized_at TIMESTAMP NULL DEFAULT NULL,
ADD UNIQUE KEY uq_mpesa_sale_receipt (sale_receipt_no);

CREATE TABLE sales_product_backup_20261123 LIKE sales_product
INSERT INTO sales_product_backup_20261123 SELECT * FROM sales_product"
 echo "Backup created successfully"

 foreach (["sales_product","sales_product_backup_20261123"] as $table) { $r=$db->query("SELECT COUNT(*) AS total FROM `$table`"); echo "$table: ".$r->fetch_assoc()["total"]." records\n"; }'
sales_product: 91 records
sales_product_backup_20261123: 91 records


docker compose exec app php -r 'require "/var/www/html/server/connection.php"; $db->query("ALTER TABLE sales_product MODIFY COLUMN reciept_no INT NOT NULL"); echo "AUTO_INCREMENT removed successfully\n";'

docker compose exec app php -r 'require "/var/www/html/server/connection.php"; $r=$db->query("SHOW COLUMNS FROM sales_product LIKE '\''reciept_no'\''"); print_r($r->fetch_assoc());'

ALTER TABLE sales
ADD COLUMN mpesa_payment_id BIGINT UNSIGNED NULL,
ADD UNIQUE KEY uq_sales_mpesa_payment (mpesa_payment_id);

ALTER TABLE mpesa_payments
ADD COLUMN verified_at TIMESTAMP NULL DEFAULT NULL;

ALTER TABLE mpesa_payments
MODIFY COLUMN status ENUM(
    'pending',
    'reported_success',
    'query_confirmed',
    'failed',
    'cancelled',
    'timed_out',
    'needs_review'
) NOT NULL DEFAULT 'pending';

ALTER TABLE mpesa_payments
ADD COLUMN last_query_at DATETIME NULL DEFAULT NULL;

ALTER TABLE mpesa_payments
ADD COLUMN last_query_at DATETIME NULL DEFAULT NULL;