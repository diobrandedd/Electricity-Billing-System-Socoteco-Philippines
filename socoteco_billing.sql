-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 06, 2025 at 09:34 PM
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
-- Database: `socoteco_billing`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_trail`
--

CREATE TABLE `audit_trail` (
  `audit_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(100) NOT NULL,
  `table_name` varchar(50) DEFAULT NULL,
  `record_id` int(11) DEFAULT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_trail`
--

INSERT INTO `audit_trail` (`audit_id`, `user_id`, `action`, `table_name`, `record_id`, `old_values`, `new_values`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 4, 'User login', 'users', 4, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-01 08:20:18'),
(2, 4, 'User logout', 'users', 4, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-01 08:32:31'),
(3, 4, 'User login', 'users', 4, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-01 08:32:37'),
(4, 4, 'User logout', 'users', 4, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-01 08:33:29'),
(5, 4, 'User login', 'users', 4, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-01 08:35:45'),
(6, 4, 'Customer created', 'customers', 0, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-01 08:40:01'),
(7, 4, 'Meter reading created', 'meter_readings', 0, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-01 08:41:04'),
(8, 4, 'Bill generated', 'bills', 0, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-01 08:41:32');

-- --------------------------------------------------------

--
-- Table structure for table `billing_rates`
--

CREATE TABLE `billing_rates` (
  `rate_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `min_kwh` int(11) NOT NULL,
  `max_kwh` int(11) DEFAULT NULL,
  `rate_per_kwh` decimal(10,4) NOT NULL,
  `effective_date` date NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bills`
--

CREATE TABLE `bills` (
  `bill_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `reading_id` int(11) NOT NULL,
  `bill_number` varchar(20) NOT NULL,
  `billing_period_start` date NOT NULL,
  `billing_period_end` date NOT NULL,
  `consumption` decimal(10,2) NOT NULL,
  `generation_charge` decimal(10,2) NOT NULL,
  `distribution_charge` decimal(10,2) NOT NULL,
  `transmission_charge` decimal(10,2) DEFAULT 0.00,
  `system_loss_charge` decimal(10,2) DEFAULT 0.00,
  `vat` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `due_date` date NOT NULL,
  `status` enum('pending','paid','overdue','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bills`
--

INSERT INTO `bills` (`bill_id`, `customer_id`, `reading_id`, `bill_number`, `billing_period_start`, `billing_period_end`, `consumption`, `generation_charge`, `distribution_charge`, `transmission_charge`, `system_loss_charge`, `vat`, `total_amount`, `due_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'SOC2025090001', '2025-09-01', '2025-09-30', 123.00, 553.50, 147.60, 98.40, 61.50, 103.32, 964.32, '2025-09-16', 'pending', '2025-09-01 08:41:32', '2025-09-01 08:41:32');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `account_number` varchar(20) NOT NULL,
  `meter_number` varchar(20) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `address` text NOT NULL,
  `barangay` varchar(100) NOT NULL,
  `municipality` varchar(100) NOT NULL,
  `province` varchar(100) DEFAULT 'South Cotabato',
  `contact_number` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `connection_date` date NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `account_number`, `meter_number`, `first_name`, `last_name`, `middle_name`, `address`, `barangay`, `municipality`, `province`, `contact_number`, `email`, `category_id`, `connection_date`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '124124', '12312', 'asdg', 'sdgsdg', 'hdsf', 'ZONE 1 PUROK VICENTE FIN', 'asd', 'aasga', 'South Cotabato', '12351', 'wmoon146@gmail.com', 1, '2025-09-01', 1, '2025-09-01 08:40:01', '2025-09-01 08:40:01');

-- --------------------------------------------------------

--
-- Table structure for table `customer_categories`
--

CREATE TABLE `customer_categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `base_rate` decimal(10,4) DEFAULT 0.0000,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer_categories`
--

INSERT INTO `customer_categories` (`category_id`, `category_name`, `description`, `base_rate`, `created_at`) VALUES
(1, 'Residential', 'Residential customers', 8.5000, '2025-09-01 08:06:40'),
(2, 'Commercial', 'Commercial establishments', 9.2000, '2025-09-01 08:06:40'),
(3, 'Industrial', 'Industrial customers', 8.8000, '2025-09-01 08:06:40'),
(4, 'Government', 'Government offices and facilities', 8.0000, '2025-09-01 08:06:40'),
(5, 'Residential', 'Residential customers', 8.5000, '2025-09-01 08:17:10'),
(6, 'Commercial', 'Commercial establishments', 9.2000, '2025-09-01 08:17:10'),
(7, 'Industrial', 'Industrial customers', 8.8000, '2025-09-01 08:17:10'),
(8, 'Government', 'Government offices and facilities', 8.0000, '2025-09-01 08:17:10');

-- --------------------------------------------------------

--
-- Table structure for table `meter_readings`
--

CREATE TABLE `meter_readings` (
  `reading_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `reading_date` date NOT NULL,
  `previous_reading` decimal(10,2) NOT NULL,
  `current_reading` decimal(10,2) NOT NULL,
  `consumption` decimal(10,2) NOT NULL,
  `reading_type` enum('actual','estimated','adjusted') DEFAULT 'actual',
  `meter_reader_id` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meter_readings`
--

INSERT INTO `meter_readings` (`reading_id`, `customer_id`, `reading_date`, `previous_reading`, `current_reading`, `consumption`, `reading_type`, `meter_reader_id`, `notes`, `created_at`) VALUES
(1, 1, '2025-09-01', 0.00, 123.00, 123.00, 'actual', 4, 'kalas kuryente', '2025-09-01 08:41:04');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `bill_id` int(11) NOT NULL,
  `payment_date` date NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','check','bank_transfer','online') DEFAULT 'cash',
  `or_number` varchar(20) NOT NULL,
  `cashier_id` int(11) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `bill_id`, `payment_date`, `amount_paid`, `payment_method`, `or_number`, `cashier_id`, `notes`, `created_at`) VALUES
(1, 1, '2025-09-01', 964.32, 'cash', 'OR2025000001', 4, 'oksoks', '2025-09-01 08:42:34');

-- --------------------------------------------------------

--
-- Table structure for table `payment_history`
--

CREATE TABLE `payment_history` (
  `history_id` int(11) NOT NULL,
  `bill_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `setting_id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`setting_id`, `setting_key`, `setting_value`, `description`, `updated_at`) VALUES
(1, 'company_name', 'SOCOTECO II', 'Electric Cooperative Name', '2025-09-01 08:06:40'),
(2, 'company_address', 'Koronadal City, South Cotabato', 'Company Address', '2025-09-01 08:06:40'),
(3, 'vat_rate', '12', 'VAT Percentage', '2025-09-01 08:06:40'),
(4, 'penalty_rate', '2', 'Monthly Penalty Rate', '2025-09-01 08:06:40'),
(5, 'due_days', '15', 'Days from bill date to due date', '2025-09-01 08:06:40'),
(6, 'generation_rate', '4.5000', 'Generation charge per kWh', '2025-09-01 08:06:40'),
(7, 'distribution_rate', '1.2000', 'Distribution charge per kWh', '2025-09-01 08:06:40'),
(8, 'transmission_rate', '0.8000', 'Transmission charge per kWh', '2025-09-01 08:06:40'),
(9, 'system_loss_rate', '0.5000', 'System loss charge per kWh', '2025-09-01 08:06:40');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('admin','cashier','meter_reader','customer') NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `full_name`, `email`, `role`, `is_active`, `created_at`, `updated_at`) VALUES
(4, 'admin', '$2y$10$b61uZ4nyV3OVcaU2qzOxQOVgwUEKVqIqhMVJDkJS9i/sJFNHQ3Jyu', 'System Administrator', 'admin@socoteco2.com', 'admin', 1, '2025-09-01 08:19:15', '2025-09-01 08:20:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_trail`
--
ALTER TABLE `audit_trail`
  ADD PRIMARY KEY (`audit_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `billing_rates`
--
ALTER TABLE `billing_rates`
  ADD PRIMARY KEY (`rate_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`bill_id`),
  ADD UNIQUE KEY `bill_number` (`bill_number`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `reading_id` (`reading_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `account_number` (`account_number`),
  ADD UNIQUE KEY `meter_number` (`meter_number`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `customer_categories`
--
ALTER TABLE `customer_categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `meter_readings`
--
ALTER TABLE `meter_readings`
  ADD PRIMARY KEY (`reading_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `meter_reader_id` (`meter_reader_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD UNIQUE KEY `or_number` (`or_number`),
  ADD KEY `bill_id` (`bill_id`),
  ADD KEY `cashier_id` (`cashier_id`);

--
-- Indexes for table `payment_history`
--
ALTER TABLE `payment_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `bill_id` (`bill_id`),
  ADD KEY `payment_id` (`payment_id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`setting_id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_trail`
--
ALTER TABLE `audit_trail`
  MODIFY `audit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `billing_rates`
--
ALTER TABLE `billing_rates`
  MODIFY `rate_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bills`
--
ALTER TABLE `bills`
  MODIFY `bill_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customer_categories`
--
ALTER TABLE `customer_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `meter_readings`
--
ALTER TABLE `meter_readings`
  MODIFY `reading_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payment_history`
--
ALTER TABLE `payment_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_trail`
--
ALTER TABLE `audit_trail`
  ADD CONSTRAINT `audit_trail_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `billing_rates`
--
ALTER TABLE `billing_rates`
  ADD CONSTRAINT `billing_rates_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `customer_categories` (`category_id`);

--
-- Constraints for table `bills`
--
ALTER TABLE `bills`
  ADD CONSTRAINT `bills_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`),
  ADD CONSTRAINT `bills_ibfk_2` FOREIGN KEY (`reading_id`) REFERENCES `meter_readings` (`reading_id`);

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `customer_categories` (`category_id`);

--
-- Constraints for table `meter_readings`
--
ALTER TABLE `meter_readings`
  ADD CONSTRAINT `meter_readings_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`),
  ADD CONSTRAINT `meter_readings_ibfk_2` FOREIGN KEY (`meter_reader_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`bill_id`) REFERENCES `bills` (`bill_id`),
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`cashier_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `payment_history`
--
ALTER TABLE `payment_history`
  ADD CONSTRAINT `payment_history_ibfk_1` FOREIGN KEY (`bill_id`) REFERENCES `bills` (`bill_id`),
  ADD CONSTRAINT `payment_history_ibfk_2` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`payment_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
