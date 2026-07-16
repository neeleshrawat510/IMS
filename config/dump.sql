-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 16, 2026 at 01:29 PM
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
  `created_by` varchar(100) DEFAULT NULL,
  `created_at` varchar(20) DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `updated_at` varchar(20) DEFAULT NULL,
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
  `subtotal` decimal(50,2) NOT NULL DEFAULT 0.00,
  `tax_total` decimal(50,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(50,2) NOT NULL DEFAULT 0.00,
  `status` enum('Draft','Sent','Overdue','Cancelled') NOT NULL DEFAULT 'Draft',
  `email_status` enum('Pending','Sent','Failed') NOT NULL DEFAULT 'Pending',
  `emailed_at` datetime(6) DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `invoice_public_token` char(64) DEFAULT NULL,
  `pdf_path` varchar(100) DEFAULT NULL,
  `created_at` varchar(20) DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `updated_at` varchar(100) DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `remove` tinyint(1) NOT NULL DEFAULT 0,
  `payment_status` enum('Unpaid','Partial','Paid','Refunded') NOT NULL DEFAULT 'Unpaid',
  `amount_paid` decimal(10,2) NOT NULL DEFAULT 0.00,
  `amount_due` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(15) NOT NULL,
  `invoice_id` int(15) NOT NULL,
  `gateway` varchar(50) NOT NULL,
  `gateway_payment_id` varchar(255) DEFAULT NULL,
  `checkout_session_id` varchar(255) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(20) NOT NULL DEFAULT 'INR',
  `status` enum('pending','processing','paid','failed','cancelled','refunded') NOT NULL DEFAULT 'pending',
  `payment_method` varchar(100) DEFAULT NULL,
  `gateway_response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gateway_response`)),
  `failure_reason` text DEFAULT NULL,
  `paid_at` datetime(6) DEFAULT NULL,
  `created_at` datetime(6) NOT NULL DEFAULT current_timestamp(6),
  `updated_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_attempts`
--

CREATE TABLE `payment_attempts` (
  `id` int(15) NOT NULL,
  `payment_id` int(15) NOT NULL,
  `attempt_no` int(15) NOT NULL,
  `status` enum('pending','failed','paid','') NOT NULL DEFAULT 'pending',
  `failure_reason` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_logs`
--

CREATE TABLE `payment_logs` (
  `id` int(15) NOT NULL,
  `payment_id` int(15) NOT NULL,
  `event` varchar(100) NOT NULL,
  `old_status` varchar(50) DEFAULT NULL,
  `new_status` varchar(50) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` int(15) NOT NULL,
  `user_id` int(15) NOT NULL,
  `gateway` varchar(255) NOT NULL,
  `gateway_customer_id` varchar(255) NOT NULL,
  `payment_method_id` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `brand` varchar(50) DEFAULT NULL,
  `last_four` char(4) DEFAULT NULL,
  `expiry_month` tinyint(50) DEFAULT NULL,
  `expiry_year` smallint(50) DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp(6) NOT NULL DEFAULT current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `stock` varchar(100) DEFAULT '0',
  `created_by` varchar(100) DEFAULT NULL,
  `created_at` varchar(50) DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `updated_at` varchar(50) DEFAULT NULL,
  `remove` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_code`, `product_name`, `cost_price`, `selling_price`, `tax`, `stock`, `created_by`, `created_at`, `updated_by`, `updated_at`, `remove`) VALUES
(10, '108', 'Adapter', 15.00, 25.00, 18.00, '', '', '', 'Neelesh Rawat', '2026-06-26 12:30:03', 1),
(20, '109', 'Monitor', 8500.00, 10000.00, 18.00, '', 'Neelesh Rawat', '', '', '', 0),
(26, '111', 'UPS', 3000.00, 3001.00, 5.00, '', 'Neelesh Rawat', '2026-06-18 16:18:47', 'Neelesh Rawat', '2026-06-25 11:17:07', 0),
(29, '255', 'Keyboard', 80.00, 100.00, 5.00, '', 'Neelesh Rawat', '2026-06-24 13:31:49', 'Neelesh Rawat', '2026-06-26 12:55:02', 0);

-- --------------------------------------------------------

--
-- Table structure for table `refunds`
--

CREATE TABLE `refunds` (
  `id` int(15) NOT NULL,
  `payment_id` int(15) NOT NULL,
  `gateway_refund_id` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `status` enum('pending','processing','completed','failed') NOT NULL DEFAULT 'pending',
  `gateway` varchar(50) NOT NULL,
  `gateway_response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gateway_response`)),
  `processed_at` datetime(6) DEFAULT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  `updated_at` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(15) NOT NULL,
  `name` varchar(100) NOT NULL,
  `number` varchar(20) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expiry` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `password` varchar(200) DEFAULT NULL,
  `role` enum('Admin','User') NOT NULL DEFAULT 'User',
  `google_id` varchar(255) DEFAULT NULL,
  `refresh_token` text DEFAULT NULL,
  `refresh_token_expires_at` datetime(6) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `number`, `email`, `reset_token`, `token_expiry`, `password`, `role`, `google_id`, `refresh_token`, `refresh_token_expires_at`, `is_active`) VALUES
(1, 'Neelesh Rawat', '1231231231', 'n@gmail.com', 'NULL', '0000-00-00 00:00:00', '27ffe01ed5a14ff897a42e89d64d8339', 'User', NULL, '', NULL, 1),
(2, 'naman', '7485965478', 'naman@gmail.com', 'NULL', '0000-00-00 00:00:00', 'd41d8cd98f00b204e9800998ecf8427e', 'User', NULL, '', NULL, 1),
(3, 'Neeraj', '7485748596', 'neeraj@gmail.com', 'NULL', '2026-06-16 05:02:51', 'dde31f3ae4ab30ecb8b7e1b800fa6229', 'User', NULL, '', NULL, 1),
(4, 'Aman', '9874563210', 'simranjeetsingh8561111@gmail.com', '06ce6b2ee8c90883bce615c51a9760f0', '2026-06-11 06:16:18', 'dde31f3ae4ab30ecb8b7e1b800fa6229', 'User', NULL, '', NULL, 1),
(5, 'Neeraj', '7485964587', 'neelesh55@gmail.com', '', '2026-06-23 04:41:12', '27ffe01ed5a14ff897a42e89d64d8339', 'User', NULL, '', NULL, 1),
(6, 'Neelesh Rawat', '', 'neeleshrawat510@gmail.com', '', '2026-07-01 08:52:33', '', 'User', NULL, '95e3fc9688a2e72d7cbf6dbbb710786f9fd23dc7d38bf65508eeeb55c257d6d2134ab3ca544deebf492af3ae7572d183cb440b8b08bee3aabba8eee66dd982d8', '2026-07-31 12:57:32.000000', 1);

-- --------------------------------------------------------

--
-- Table structure for table `webhook_logs`
--

CREATE TABLE `webhook_logs` (
  `id` int(15) NOT NULL,
  `gateway` varchar(50) NOT NULL,
  `event_id` varchar(255) NOT NULL,
  `event_type` varchar(255) NOT NULL,
  `signature` varchar(255) DEFAULT NULL,
  `status` enum('received','processed','failed','') NOT NULL DEFAULT 'received',
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`payload`)),
  `error_message` varchar(255) DEFAULT NULL,
  `processed_at` datetime(6) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_attempts`
--
ALTER TABLE `payment_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_logs`
--
ALTER TABLE `payment_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `refunds`
--
ALTER TABLE `refunds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `webhook_logs`
--
ALTER TABLE `webhook_logs`
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
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_attempts`
--
ALTER TABLE `payment_attempts`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_logs`
--
ALTER TABLE `payment_logs`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `refunds`
--
ALTER TABLE `refunds`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `webhook_logs`
--
ALTER TABLE `webhook_logs`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
