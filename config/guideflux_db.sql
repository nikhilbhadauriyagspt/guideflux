-- GuideFlux Database Schema & Initial Data Export
-- Can be imported directly into phpMyAdmin or MySQL Workbench

CREATE DATABASE IF NOT EXISTS `guideflux_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `guideflux_db`;

-- --------------------------------------------------------
-- Table structure for `admins`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admins` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('superadmin', 'admin', 'editor') DEFAULT 'admin',
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed default admin account (Password: admin123)
INSERT INTO `admins` (`name`, `email`, `password`, `role`, `status`) 
VALUES ('Admin Manager', 'admin@guideflux.com', '$2y$10$tZcQ2H6kL/eE4J40pU01n.H2z/OQ8eR6VqRk3Z5P5h3xZ2o1ZqK1e', 'superadmin', 'active')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- --------------------------------------------------------
-- Table structure for `packages`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `packages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `location` VARCHAR(150) NOT NULL,
  `duration` VARCHAR(50) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `category` ENUM('domestic', 'international', 'adventure', 'luxury') DEFAULT 'international',
  `image` VARCHAR(255) NULL,
  `status` ENUM('active', 'draft') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for `bookings`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `booking_code` VARCHAR(50) NOT NULL UNIQUE,
  `customer_name` VARCHAR(100) NOT NULL,
  `customer_email` VARCHAR(150) NOT NULL,
  `customer_phone` VARCHAR(30) NOT NULL,
  `package_title` VARCHAR(255) NOT NULL,
  `travel_date` DATE NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `status` ENUM('confirmed', 'pending', 'completed', 'cancelled') DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Sample Bookings
INSERT INTO `bookings` (`booking_code`, `customer_name`, `customer_email`, `customer_phone`, `package_title`, `travel_date`, `amount`, `status`) VALUES
('GF-1001', 'Arjun Mehta', 'arjun.m@example.com', '+91 9876543210', 'Dubai Desert Safari & Burj Khalifa Experience', '2026-10-18', 1450.00, 'confirmed'),
('GF-1002', 'Sophia Martinez', 'sophia.m@gmail.com', '+1 555-0199', 'Bali Luxury Villa 7D/6N Tropical Getaway', '2026-11-02', 2100.00, 'pending'),
('GF-1003', 'David Wilson', 'david.w@corp.org', '+44 20 7946 0912', 'Swiss Alps Ski & Scenic Train Tour', '2026-12-15', 3850.00, 'confirmed'),
('GF-1004', 'Ananya Verma', 'ananya.v@yahoo.com', '+91 9123456780', 'Goa Beachside Sunset Resort & Watersports', '2026-10-25', 780.00, 'completed')
ON DUPLICATE KEY UPDATE `booking_code` = VALUES(`booking_code`);
