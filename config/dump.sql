-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 02, 2026 at 05:52 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ims`
--

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(15) NOT NULL,
  `name` varchar(100) NOT NULL,
  `number` varchar(20) NOT NULL,
  `email` varchar(200) NOT NULL,
  `company` varchar(100) NOT NULL,
  `gst` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `updated_by` varchar(100) NOT NULL,
  `updated_at` varchar(20) NOT NULL,
  `remove` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `number`, `email`, `company`, `gst`, `address`, `created_by`, `created_at`, `updated_by`, `updated_at`, `remove`) VALUES
(6, 'Gagandeep', '9872441301', 'gagandeepattri8@gmail.com', 'Baseline', '22ABCDE2222A1Z3', 'test', '', '2026-06-11 05:06:13', '', '', 0),
(12, 'Neelesh', '7485963210', 'neelesh@gmail.com', 'Base IT', '29ABCDE1234F1Z5', 'Mohali', 'Neelesh Rawat', '2026-06-19 15:00:57', '', '', 0),
(13, 'Sagar Joshi', '7888765928', 'sagarj@gmail.com', 'IT', '29ABCDE4234F2Z5', 'Patiala', 'Neelesh Rawat', '2026-06-24 12:11:35', '', '2026-06-24 08:42:05', 0),
(14, 'Nitin Rana', '7888565928', 'sagarj@gmail.com', 'IT', '29ABCEE4234F2Z5', 'Punjab', 'Neelesh Rawat', '2026-06-24 14:37:28', 'Neelesh Rawat', '2026-06-26 12:59:43', 0);

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(15) NOT NULL,
  `contact_id` varchar(50) NOT NULL,
  `invoice_no` varchar(100) NOT NULL,
  `invoice_date` varchar(100) NOT NULL,
  `due_date` varchar(100) NOT NULL,
  `subtotal` decimal(50,2) NOT NULL,
  `tax_total` decimal(50,2) NOT NULL,
  `grand_total` decimal(50,2) NOT NULL,
  `status` varchar(20) NOT NULL,
  `pdf_path` varchar(100) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `updated_at` varchar(100) NOT NULL,
  `updated_by` varchar(100) NOT NULL,
  `remove` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `contact_id`, `invoice_no`, `invoice_date`, `due_date`, `subtotal`, `tax_total`, `grand_total`, `status`, `pdf_path`, `created_at`, `created_by`, `updated_at`, `updated_by`, `remove`) VALUES
(1, '6', 'INV-10001', '2026-06-10', '2026-06-26', 10500.00, 525.00, 11025.00, 'Unpaid', 'invoices/invoice_1.pdf', '2026-06-10 11:25:03', '', '2026-06-26 14:39:35', 'Neelesh Rawat', 0),
(2, '6', 'INV-10002', '2026-06-10', '2026-06-26', 9940.00, 37.80, 9977.80, 'paid', 'invoices/invoice_2.pdf', '2026-06-10 12:33:01', '', '2026-06-29 07:19:39', 'Neelesh Rawat', 0),
(3, '5', 'INV-10003', '2026-06-11', '2026-06-11', 12740.00, 1901.90, 14641.90, 'Unpaid', 'invoices/invoice_3.pdf', '2026-06-11 08:00:24', '', '', '', 0),
(8, '6', 'INV-10004', '2026-06-23', '2026-06-25', 70.00, 12.60, 82.60, 'Unpaid', 'invoices/invoice_8.pdf', '2026-06-23 10:09:55', '', '', '', 0),
(9, '6', 'INV-10005', '2026-06-23', '2026-06-23', 0.00, 0.00, 0.00, 'Paid', '', '2026-06-23 12:59:11', 'Neelesh Rawat', '', '', 0),
(10, '6', 'INV-10006', '2026-06-23', '2026-06-23', 0.00, 0.00, 0.00, 'Paid', '', '2026-06-23 13:04:01', 'Neelesh Rawat', '', '', 0),
(11, '6', 'INV-10007', '2026-06-23', '2026-06-24', 165.00, 29.70, 165.00, 'Unpaid', '', '2026-06-23 13:10:23', 'Neelesh Rawat', '', '', 0),
(12, '6', 'INV-10009', '2026-06-23', '2026-06-24', 23500.00, 3775.00, 27275.00, 'Unpaid', 'invoices/invoice_12.pdf', '2026-06-23 13:14:03', 'Neelesh Rawat', '', '', 0),
(13, '6', 'INV-10010', '2026-06-23', '2026-06-24', 23500.00, 3775.00, 27275.00, 'Unpaid', '', '2026-06-23 13:21:26', 'Neelesh Rawat', '', '', 0),
(14, '12', 'INV_10007', '2026-06-24', '2026-07-24', 20050.00, 3609.00, 23659.00, 'unpaid', '', '2026-06-24 09:27:03', 'Neelesh Rawat', '', '', 0),
(17, '6', 'INV-10008', '2026-06-24', '2026-07-24', 20050.00, 3609.00, 23659.00, 'Paid', 'invoices/invoice_17.pdf', '2026-06-24 09:49:22', 'Neelesh Rawat', '2026-06-24 10:31:42', 'Neelesh Rawat', 0),
(18, '12', 'INV-10015', '2026-06-24', '2026-07-24', 20050.00, 3609.00, 23659.00, 'unpaid', 'invoices/invoice_18.pdf', '2026-06-24 14:38:03', 'Neelesh Rawat', '', '', 0),
(19, '6', 'INV-10016', '2026-06-24', '2026-07-24', 27000.00, 3950.00, 30950.00, 'unpaid', 'invoices/invoice_19.pdf', '2026-06-24 16:37:19', 'Neelesh Rawat', '', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` int(12) NOT NULL,
  `invoice_id` varchar(100) NOT NULL,
  `product_id` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `qty` decimal(10,2) NOT NULL,
  `price` decimal(50,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL,
  `amount` decimal(50,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`id`, `invoice_id`, `product_id`, `description`, `qty`, `price`, `tax`, `amount`) VALUES
(3, '1', '5', 'Keyboard', 2.00, 70.00, 18.00, 140.00),
(4, '2', '7', 'laptop', 1.00, 4865.00, 0.00, 4865.00),
(6, '2', '5', 'Keyboard', 3.00, 70.00, 18.00, 210.00),
(7, '2', '7', 'laptop', 2.00, 4865.00, 0.00, 9730.00),
(13, '3', '7', 'laptop', 2.00, 4865.00, 18.00, 9730.00),
(14, '3', '9', 'Mouse', 1.00, 10.00, 5.00, 10.00),
(15, '3', '11', 'Mobile', 1.00, 3000.00, 5.00, 3000.00),
(18, '7', '5', '', 2.00, 0.00, 0.00, 0.00),
(19, '7', '10', '', 1.00, 0.00, 0.00, 0.00),
(20, '8', '5', 'Keyboard', 1.00, 70.00, 18.00, 70.00),
(21, '11', '5', 'Keyboard', 2.00, 70.00, 18.00, 158.00),
(22, '11', '10', 'Adapter', 1.00, 25.00, 18.00, 43.00),
(25, '13', '20', 'Monitor', 2.00, 10000.00, 18.00, 20018.00),
(26, '13', '26', 'UPS', 1.00, 3500.00, 5.00, 3505.00),
(27, '12', '20', 'Monitor', 2.00, 10000.00, 18.00, 20018.00),
(28, '12', '26', 'UPS', 1.00, 3500.00, 5.00, 3500.00),
(31, '0', '26', 'UPS', 2.00, 3500.00, 5.00, 7000.00),
(32, '0', '26', 'UPS', 1.00, 3500.00, 5.00, 3500.00),
(37, '6', '26', 'UPS', 2.00, 3500.00, 5.00, 7000.00),
(38, '6', '26', 'UPS', 1.00, 3500.00, 5.00, 3500.00),
(45, '', '10', 'Adapter', 2.00, 25.00, 18.00, 50.00),
(46, '', '20', 'Monitor', 2.00, 10000.00, 18.00, 20000.00),
(47, '', '10', 'Adapter', 2.00, 25.00, 18.00, 50.00),
(48, '', '20', 'Monitor', 2.00, 10000.00, 18.00, 20000.00),
(49, '', '10', 'Adapter', 2.00, 25.00, 18.00, 50.00),
(50, '', '20', 'Monitor', 2.00, 10000.00, 18.00, 20000.00),
(71, '17', '10', 'Adapter', 2.00, 25.00, 18.00, 50.00),
(72, '17', '20', 'Monitor', 2.00, 10000.00, 18.00, 20000.00),
(73, '18', '10', 'Adapter', 2.00, 25.00, 18.00, 50.00),
(74, '18', '20', 'Monitor', 2.00, 10000.00, 18.00, 20000.00),
(75, '19', '26', 'UPS', 2.00, 3500.00, 5.00, 7000.00),
(76, '19', '20', 'Monitor', 2.00, 10000.00, 18.00, 20000.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(15) NOT NULL,
  `product_code` varchar(20) NOT NULL,
  `product_name` varchar(200) NOT NULL,
  `cost_price` decimal(50,2) NOT NULL,
  `selling_price` decimal(50,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `created_at` varchar(50) NOT NULL,
  `updated_by` varchar(100) NOT NULL,
  `updated_at` varchar(50) NOT NULL,
  `remove` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_code`, `product_name`, `cost_price`, `selling_price`, `tax`, `created_by`, `created_at`, `updated_by`, `updated_at`, `remove`) VALUES
(10, '108', 'Adapter', 15.00, 25.00, 18.00, '', '', 'Neelesh Rawat', '2026-06-26 12:30:03', 1),
(20, '109', 'Monitor', 8500.00, 10000.00, 18.00, 'Neelesh Rawat', '', '', '', 0),
(26, '111', 'UPS', 3000.00, 3001.00, 5.00, 'Neelesh Rawat', '2026-06-18 16:18:47', 'Neelesh Rawat', '2026-06-25 11:17:07', 0),
(29, '255', 'Keyboard', 80.00, 100.00, 5.00, 'Neelesh Rawat', '2026-06-24 13:31:49', 'Neelesh Rawat', '2026-06-26 12:55:02', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(15) NOT NULL,
  `name` varchar(100) NOT NULL,
  `number` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `reset_token` varchar(255) NOT NULL,
  `token_expiry` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `password` varchar(200) NOT NULL,
  `refresh_token` text NOT NULL,
  `refresh_token_expires_at` datetime(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `number`, `email`, `reset_token`, `token_expiry`, `password`, `refresh_token`, `refresh_token_expires_at`) VALUES
(1, 'Neelesh Rawat', '1231231231', 'n@gmail.com', 'NULL', '0000-00-00 00:00:00', '27ffe01ed5a14ff897a42e89d64d8339', '', NULL),
(2, 'naman', '7485965478', 'naman@gmail.com', 'NULL', '0000-00-00 00:00:00', 'd41d8cd98f00b204e9800998ecf8427e', '', NULL),
(3, 'Neeraj', '7485748596', 'neeraj@gmail.com', 'NULL', '2026-06-16 05:02:51', 'dde31f3ae4ab30ecb8b7e1b800fa6229', '', NULL),
(4, 'Aman', '9874563210', 'simranjeetsingh8561111@gmail.com', '06ce6b2ee8c90883bce615c51a9760f0', '2026-06-11 06:16:18', 'dde31f3ae4ab30ecb8b7e1b800fa6229', '', NULL),
(5, 'Neeraj', '7485964587', 'neelesh55@gmail.com', '', '2026-06-23 04:41:12', '27ffe01ed5a14ff897a42e89d64d8339', '', NULL),
(6, 'Neelesh Rawat', '', 'neeleshrawat510@gmail.com', '', '2026-07-01 08:52:33', '', '95e3fc9688a2e72d7cbf6dbbb710786f9fd23dc7d38bf65508eeeb55c257d6d2134ab3ca544deebf492af3ae7572d183cb440b8b08bee3aabba8eee66dd982d8', '2026-07-31 12:57:32.000000');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
