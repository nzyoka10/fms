-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 07, 2024 at 01:12 PM
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
(24, '1', 'Demetrius Garcia', 'cremation', '2024-12-13', 'van', 'Golden coffin, PA system', 'scheduled', '2024-11-08 11:23:40', '2024-12-07 09:55:34'),
(25, '8', 'Megan Obrien', 'cremation', '2024-12-14', 'Bus', 'Uran', 'scheduled', '2024-11-08 11:24:06', '2024-12-07 09:54:40');

-- --------------------------------------------------------

--
-- Table structure for table `booking_services`
--

CREATE TABLE `booking_services` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_services`
--

INSERT INTO `booking_services` (`id`, `booking_id`, `service_id`, `quantity`, `total_price`, `created_at`) VALUES
(1, 21, 1, 1, 15000.00, '2024-11-21 09:38:06'),
(3, 24, 4, 1, 3000.00, '2024-11-21 09:38:06'),
(4, 25, 3, 2, 10000.00, '2024-11-21 09:38:06');

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
(1, 'Charity Kinango', '(254) 712-579-1', 'test@user.app', 'Gaza city, Down ', 'Sacha Sawyer', 89, '2022-06-15', 'natural', 'female', '2024-10-24 04:41:03', '2024-10-25 12:52:04'),
(3, 'Mike Aguero', '0736348911', 'mike@app.fms', 'Kinnoo, uptown', 'Jane Doe', 90, '2024-10-22', 'natural', 'female', '2024-10-25 04:56:33', '2024-10-25 04:56:56'),
(4, 'Sebastian Gilliam', '+1 (553) 916-3606', 'nacokukyp@mailinator.com', 'Numquam labore labor', 'Demetrius Garcia', 20, '0000-00-00', 'sickness', 'male', '2024-10-28 05:18:17', '2024-10-28 05:18:17'),
(5, 'Kim Goff', '+1 (387) 943-3018', 'jibaru@mailinator.com', 'Temporibus magnam nu', 'Meghan Thomas', 13, '0000-00-00', 'other', 'male', '2024-10-28 05:18:29', '2024-10-28 05:18:29'),
(7, 'Candace Velasquez', '+1 (464) 219-5548', 'tupoxijy@mailinator.com', 'Officia tempore atq', 'Florence Knowles', 52, '0000-00-00', 'sickness', 'male', '2024-10-28 05:19:09', '2024-10-28 05:19:09'),
(8, 'Stuart Branch', '+1 (758) 366-8151', 'cuxig@mailinator.com', 'Voluptatem Atque no', 'Megan Obrien', 83, '0000-00-00', 'accident', 'male', '2024-10-28 05:19:17', '2024-10-28 05:19:17');

-- --------------------------------------------------------

--
-- Table structure for table `client_logs`
--

CREATE TABLE `client_logs` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `log_data` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Table structure for table `error_logs`
--

CREATE TABLE `error_logs` (
  `id` int(11) NOT NULL,
  `error_message` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `error_logs`
--

INSERT INTO `error_logs` (`id`, `error_message`, `created_at`) VALUES
(1, 'Client not found.', '2024-12-07 13:27:13'),
(2, 'Client not found.', '2024-12-07 13:28:37'),
(3, 'Client not found.', '2024-12-07 13:30:38'),
(4, 'Client not found.', '2024-12-07 13:33:46'),
(5, 'Client not found.', '2024-12-07 13:45:21');

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
(12, 23, 137281, 'cash', 5000.00, 0.00, 0.00, '2024-10-25', '2024-10-25 12:27:15', '2024-10-25 12:27:15'),
(13, 24, 137282, 'cash', 8000.00, 70.00, 0.00, '2024-11-08', '2024-11-08 11:25:52', '2024-11-08 11:25:52'),
(14, 25, 137283, 'mpesa', 12000.00, 0.00, 0.00, '2024-11-08', '2024-11-08 11:28:56', '2024-11-08 11:28:56'),
(15, 23, 137284, 'cash', 400.00, 0.00, 0.00, '2024-11-14', '2024-11-14 10:17:26', '2024-11-14 10:17:26');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `service_name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `service_name`, `description`, `price`, `created_at`) VALUES
(1, 'Burial Arrangement', 'Complete burial arrangement services including coffin, grave digging, and floral decor.', 15000.00, '2024-11-21 09:36:56'),
(2, 'Cremation', 'Cremation services with options for urns.', 10000.00, '2024-11-21 09:36:56'),
(3, 'Transportation', 'Vehicle services for transportation of the deceased.', 5000.00, '2024-11-21 09:36:56'),
(4, 'Special Music Request', 'Musical arrangements during the ceremony.', 3000.00, '2024-11-21 09:36:56');

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

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_types`
--

CREATE TABLE `vehicle_types` (
  `id` int(11) NOT NULL,
  `vehicle_type_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicle_types`
--

INSERT INTO `vehicle_types` (`id`, `vehicle_type_name`, `created_at`, `updated_at`) VALUES
(1, 'Hearse + Body (KDJ-225-F)', '2024-11-22 12:47:22', '2024-11-22 12:47:22'),
(2, 'Van + Body (KBL-205-C)', '2024-11-22 12:47:22', '2024-11-22 12:47:22'),
(3, 'Bus - (KCJ-004-I)', '2024-11-22 12:47:22', '2024-11-22 12:47:22'),
(4, 'SUV - (KDJ-215-J)', '2024-11-22 12:47:22', '2024-11-22 12:47:22'),
(5, 'Limousine + Body (KDA-005-A)', '2024-11-22 12:47:22', '2024-11-22 12:47:22');

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
-- Indexes for table `booking_services`
--
ALTER TABLE `booking_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_logs`
--
ALTER TABLE `client_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company_info`
--
ALTER TABLE `company_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `error_logs`
--
ALTER TABLE `error_logs`
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
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `vehicle_types`
--
ALTER TABLE `vehicle_types`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `booking_services`
--
ALTER TABLE `booking_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `client_logs`
--
ALTER TABLE `client_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `company_info`
--
ALTER TABLE `company_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `error_logs`
--
ALTER TABLE `error_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `vehicle_types`
--
ALTER TABLE `vehicle_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking_services`
--
ALTER TABLE `booking_services`
  ADD CONSTRAINT `booking_services_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `booking_services_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
