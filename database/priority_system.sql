-- Priority Number System Tables for SOCOTECO II Billing Management System
-- This file contains the database schema for the priority number generator system

-- Table for storing priority numbers
CREATE TABLE `priority_numbers` (
  `priority_id` int(11) NOT NULL AUTO_INCREMENT,
  `priority_number` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `service_date` date NOT NULL,
  `generated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','served','expired','cancelled') DEFAULT 'pending',
  `served_at` timestamp NULL DEFAULT NULL,
  `served_by` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`priority_id`),
  UNIQUE KEY `priority_number` (`priority_number`),
  KEY `customer_id` (`customer_id`),
  KEY `service_date` (`service_date`),
  KEY `status` (`status`),
  KEY `served_by` (`served_by`),
  CONSTRAINT `priority_numbers_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`),
  CONSTRAINT `priority_numbers_ibfk_2` FOREIGN KEY (`served_by`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table for managing the current priority number and queue status
CREATE TABLE `priority_queue_status` (
  `status_id` int(11) NOT NULL AUTO_INCREMENT,
  `current_priority_number` int(11) DEFAULT 0,
  `last_served_number` int(11) DEFAULT 0,
  `queue_date` date NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `daily_capacity` int(11) DEFAULT 1000,
  `served_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`status_id`),
  UNIQUE KEY `queue_date` (`queue_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table for tracking service days and capacity
CREATE TABLE `service_days` (
  `service_day_id` int(11) NOT NULL AUTO_INCREMENT,
  `service_date` date NOT NULL,
  `max_capacity` int(11) DEFAULT 1000,
  `current_count` int(11) DEFAULT 0,
  `is_available` tinyint(1) DEFAULT 1,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`service_day_id`),
  UNIQUE KEY `service_date` (`service_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table for priority number history and analytics
CREATE TABLE `priority_number_history` (
  `history_id` int(11) NOT NULL AUTO_INCREMENT,
  `priority_id` int(11) NOT NULL,
  `action` varchar(50) NOT NULL,
  `old_status` varchar(20) DEFAULT NULL,
  `new_status` varchar(20) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`history_id`),
  KEY `priority_id` (`priority_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `priority_number_history_ibfk_1` FOREIGN KEY (`priority_id`) REFERENCES `priority_numbers` (`priority_id`),
  CONSTRAINT `priority_number_history_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert default system settings for priority system
INSERT INTO `system_settings` (`setting_key`, `setting_value`, `description`) VALUES
('priority_daily_capacity', '1000', 'Maximum number of customers that can be served per day'),
('priority_advance_days', '7', 'Number of days in advance customers can get priority numbers'),
('priority_expiry_hours', '24', 'Hours after which a priority number expires if not served'),
('priority_notification_enabled', '1', 'Enable SMS/Email notifications for priority numbers'),
('priority_auto_assign_days', '1', 'Automatically assign service days when generating priority numbers');

-- Insert today's queue status
INSERT INTO `priority_queue_status` (`current_priority_number`, `last_served_number`, `queue_date`, `daily_capacity`, `served_count`) 
VALUES (0, 0, CURDATE(), 1000, 0);

-- Create indexes for better performance
CREATE INDEX `idx_priority_numbers_customer_date` ON `priority_numbers` (`customer_id`, `service_date`);
CREATE INDEX `idx_priority_numbers_status_date` ON `priority_numbers` (`status`, `service_date`);
CREATE INDEX `idx_priority_numbers_generated_at` ON `priority_numbers` (`generated_at`);
