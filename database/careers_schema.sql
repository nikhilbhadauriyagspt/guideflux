-- Orion Advent - Careers & Job Openings Database Schema Migration
-- Run this on your MySQL database (e.g. guideflux_db)

CREATE TABLE IF NOT EXISTS `jobs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `department` VARCHAR(100) NOT NULL,
  `location` VARCHAR(150) NOT NULL DEFAULT 'Remote / On-site',
  `job_type` ENUM('Full-time', 'Part-time', 'Contract', 'Remote', 'Internship') NOT NULL DEFAULT 'Full-time',
  `experience_level` VARCHAR(100) DEFAULT '1-3 Years',
  `salary_range` VARCHAR(100) DEFAULT 'Best in Industry',
  `openings` INT DEFAULT 1,
  `short_description` TEXT NULL,
  `description` LONGTEXT NOT NULL,
  `requirements` LONGTEXT NULL,
  `benefits` LONGTEXT NULL,
  `status` ENUM('active', 'closed', 'draft') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX (`status`),
  INDEX (`department`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `job_applications` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `job_id` INT NOT NULL,
  `candidate_name` VARCHAR(200) NOT NULL,
  `candidate_email` VARCHAR(200) NOT NULL,
  `candidate_phone` VARCHAR(50) NOT NULL,
  `experience_years` DECIMAL(4,1) DEFAULT 0,
  `current_location` VARCHAR(150) NULL,
  `notice_period` VARCHAR(100) DEFAULT 'Immediate',
  `portfolio_url` VARCHAR(255) NULL,
  `resume_file` VARCHAR(255) NOT NULL,
  `cover_letter` TEXT NULL,
  `status` ENUM('pending', 'reviewed', 'shortlisted', 'selected', 'rejected') DEFAULT 'pending',
  `admin_notes` TEXT NULL,
  `email_sent` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`job_id`) REFERENCES `jobs`(`id`) ON DELETE CASCADE,
  INDEX (`status`),
  INDEX (`job_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
