-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 30, 2024 at 12:47 AM
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
-- Database: `dolphin_crm`
--

-- --------------------------------------------------------

--
-- Table structure for table `contacts table`
--

DROP DATABASE IF EXISTS dolphin_crm;
CREATE DATABASE dolphin_crm;
USE dolphin_crm;


-- Create users table
DROP TABLE IF EXISTS `users`;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(255),
    lastname VARCHAR(255),
    email VARCHAR(255),
    password VARCHAR(255),
    role VARCHAR(50),
    created_at DATETIME
);

-- Insert a user with hashed password
INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Admin', 'User', 'admin@project2.com', '$2y$10$2oaxXxv0LU8vzTaDftSTuuWUaQDhH.yORO2IGFc.9agComH4CKQTu', 'Admin', '2024-12-03 00:13:18'),
(2, 'Micheal', 'Scott', 'micheal.scott@paper.co', '$2y$10$h5uA0pIZk2ETEhs2QdgTUeVd.M1PMvisYxO14gPXRcE2JgJ5UUTCW', 'Member', '2024-12-03 00:14:07'),
(3, 'Jim', 'Halpert', 'jim.halpert@dunder.mif', '$2y$10$Lig4NuPaQkvnIrvH7/HoBOHGbs8IwmbPx23aR2PPZGr/qQtd0eWDC', 'Member', '2024-12-03 00:14:45'),
(4, 'Pam', 'Beasly', 'pam.beasly@dunder.mif', '$2y$10$38DT81AO6/fT6M4o51LeMuAMdaPH4kEp/IQssYWdq1bqAWkuKZIgS', 'Member', '2024-12-03 00:16:38');

-- Create contacts table
DROP TABLE IF EXISTS `contacts`;
CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(50),
    firstname VARCHAR(255),
    lastname VARCHAR(255),
    email VARCHAR(255),
    telephone VARCHAR(20),
    company VARCHAR(255),
    type ENUM('sales lead', 'support'),
    assigned_to INT,
    created_by INT,
    created_at DATETIME,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO `contacts` (`title`, `firstname`, `lastname`, `email`, `telephone`, `company`, `type`, `assigned_to`, `created_by`) VALUES 
('Mr.','John','Doe','john.doe@example.com','888-777-6666','Acme Corporation','Sales Lead',1,2);

-- Create notes table
DROP TABLE IF EXISTS `notes`;
CREATE TABLE notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    contact_id INT,
    comment TEXT,
    created_by INT,
    created_at DATETIME
);

GRANT ALL PRIVILEGES ON dolphin_crm.* TO 'Admin'@'localhost'IDENTIFIED BY 'password123';
FlUSH PRIVILEGES;