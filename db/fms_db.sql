-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 25, 2024 at 02:50 PM
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
-- Database: `fms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `client_id` varchar(100) NOT NULL,
  `deceased_name` varchar(100) NOT NULL,
  `service_type` enum('burial','cremation','other') DEFAULT 'burial',
  `schedule_date` date NOT NULL,
  `vehicle_type` varchar(150) DEFAULT NULL,
  `request` varchar(255) DEFAULT NULL,
  `status` enum('scheduled','completed') DEFAULT 'scheduled',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `client_id`, `deceased_name`, `service_type`, `schedule_date`, `vehicle_type`, `request`, `status`, `created_at`, `updated_at`) VALUES
(21, '2', 'ELihaji Lewis', 'burial', '2024-11-02', 'van', 'None..', 'scheduled', '2024-10-24 10:09:51', '2024-10-24 10:09:51'),
(22, '1', 'Jane Doe', 'burial', '2024-10-31', 'van', 'Boda boda escorst', 'scheduled', '2024-10-25 11:06:55', '2024-10-25 12:33:52'),
(23, '1', 'Sacha Sawyer', 'cremation', '2024-11-01', 'Bus', 'Music ', 'scheduled', '2024-10-25 11:43:32', '2024-10-25 11:43:32');

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `client_name` varchar(100) NOT NULL,
  `client_phone` varchar(20) NOT NULL,
  `client_email` varchar(100) DEFAULT NULL,
  `client_address` varchar(255) DEFAULT NULL,
  `deceased_name` varchar(100) NOT NULL,
  `deceased_age` int(3) NOT NULL,
  `deceased_date_of_death` date NOT NULL,
  `deceased_cause` enum('natural','sickness','accident','other') NOT NULL,
  `deceased_gender` enum('male','female','other') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `client_name`, `client_phone`, `client_email`, `client_address`, `deceased_name`, `deceased_age`, `deceased_date_of_death`, `deceased_cause`, `deceased_gender`, `created_at`, `updated_at`) VALUES
(1, 'Charity Mcfadden', '(254) 712-579-1', 'test@user.app', 'Gaza city, Down ', 'Sacha Sawyer', 89, '2022-06-15', 'natural', 'female', '2024-10-24 04:41:03', '2024-10-24 05:50:17'),
(2, 'Sonya Cox', '(254) 728-486-849', 'ximeb@app.com', 'Molestiae', 'ELihaji Lewis', 96, '2024-04-02', 'sickness', 'male', '2024-10-24 05:49:50', '2024-10-24 05:50:10'),
(3, 'Mike Aguero', '0736348911', 'mike@app.fms', 'Kinnoo, uptown', 'Jane Doe', 90, '2024-10-22', 'natural', 'female', '2024-10-25 04:56:33', '2024-10-25 04:56:56');

-- --------------------------------------------------------

--
-- Table structure for table `company_info`
--

CREATE TABLE `company_info` (
  `id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `company_address` varchar(255) NOT NULL,
  `company_contact` varchar(20) NOT NULL,
  `company_email` varchar(100) NOT NULL,
  `report_type` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `company_info`
--

INSERT INTO `company_info` (`id`, `company_name`, `company_address`, `company_contact`, `company_email`, `report_type`, `created_at`) VALUES
(1, 'Funeral Management System (FMS)', '123 Avenue, Nairobi, Ke', '+254 700 000 000', 'info@fms.co.ke', 'Weekly Report', '2024-10-16 23:24:27'),
(2, 'Funeral Management System (FMS)', '123 Avenue, Nairobi, Ke', '+254 700 000 000', 'info@fms.co.ke', 'Monthly Report', '2024-10-16 23:24:27'),
(3, 'Funeral Management System (FMS)', '123 Avenue, Nairobi, Ke', '+254 700 000 000', 'info@fms.co.ke', 'Custom Report (01-01-2024 to 01-31-2024)', '2024-10-16 23:24:27');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `category` enum('coffin','urn','chapel','cremator','meeting_room') NOT NULL,
  `quantity` int(11) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `item_name`, `category`, `quantity`, `last_updated`) VALUES
(1, 'Wooden Coffin', 'coffin', 10, '2024-10-16 14:48:43'),
(2, 'Marble Urn', 'urn', 5, '2024-10-16 14:48:43'),
(3, 'Chapel Seating', 'chapel', 20, '2024-10-16 14:48:43'),
(4, 'Industrial Cremator', 'cremator', 3, '2024-10-16 14:48:43'),
(5, 'Meeting Room A', 'meeting_room', 15, '2024-10-16 14:48:43'),
(6, 'Test', 'urn', 5, '2024-10-25 12:24:59');

-- --------------------------------------------------------

--
-- Table structure for table `logistics`
--

CREATE TABLE `logistics` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `vehicle` varchar(255) DEFAULT NULL,
  `driver_name` varchar(100) DEFAULT NULL,
  `pickup_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `destination` varchar(255) DEFAULT NULL,
  `pickup_location` varchar(100) NOT NULL,
  `status` enum('pending','completed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logistics`
--

INSERT INTO `logistics` (`id`, `client_id`, `vehicle`, `driver_name`, `pickup_date`, `destination`, `pickup_location`, `status`, `created_at`, `updated_at`) VALUES
(1, 7, 'Van KBN 234-J', 'Kadere WaShiku', '2024-10-16 10:26:50', '20th Kenya location', 'EternalCare Gold', 'pending', '2024-10-05 16:03:53', '2024-10-16 10:26:50'),
(2, 8, 'Bus KDA 222-F', 'Hassan Omar', '2024-10-16 10:29:02', '30th Kisumu town', 'Jiji, EternalCare Silver', 'pending', '2024-10-05 16:16:47', '2024-10-16 10:29:02'),
(3, 9, 'Van  KDC 826-j', 'Muthee J. Khibaso', '2024-10-16 13:07:23', '12th Kiambu, up town', 'Lee, EternalCare Silver', 'pending', '2024-10-16 10:19:58', '2024-10-16 13:07:23'),
(4, 10, 'Bus  KDA 222-F', 'Muthee J. Khibaso', '2024-10-16 13:07:52', '10th Kiambaa Village', 'EternalCare Gold', 'pending', '2024-10-16 10:25:03', '2024-10-16 13:07:52');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `receipt_number` int(8) DEFAULT NULL,
  `payment_method` enum('cash','mpesa') NOT NULL DEFAULT 'cash',
  `amount` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) GENERATED ALWAYS AS (`amount` + `tax` - `discount`) VIRTUAL,
  `payment_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `booking_id`, `receipt_number`, `payment_method`, `amount`, `tax`, `discount`, `payment_date`, `created_at`, `updated_at`) VALUES
(12, 23, 137281, 'cash', 5000.00, 0.00, 0.00, '2024-10-25', '2024-10-25 12:27:15', '2024-10-25 12:27:15');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','staff') NOT NULL DEFAULT 'staff',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `username`, `email`, `password_hash`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Tom', 'Ouko', 'admin', 'admin@app.com', '$2y$10$m4ZTmmGEXuiCeVcG0qdLZuZcUGyNlT56UMzo/Xtt5MYKndxrracsa', 'admin', '2024-09-28 12:18:53', '2024-09-28 12:18:53'),
(5, 'Kajanah', 'Malwa Jr', 'user', 'super@app.com', '$2y$10$ZFPeUQSY/hyLbKKOjKM6t.XFIzLiU0CXjVLKI6uEeLlDAhbrdH.YW', 'staff', '2024-10-10 13:43:18', '2024-10-17 05:13:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company_info`
--
ALTER TABLE `company_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logistics`
--
ALTER TABLE `logistics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_number` (`receipt_number`),
  ADD KEY `client_id` (`booking_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `company_info`
--
ALTER TABLE `company_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `logistics`
--
ALTER TABLE `logistics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
