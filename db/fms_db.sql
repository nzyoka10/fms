-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 17, 2024 at 03:01 AM
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
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `full_name`, `email`, `phone`, `address`, `created_at`, `updated_at`) VALUES
(1, 'Juma Hunderson', 'hunter@app.com', '0789256714', '51 Street, Limuru', '2024-09-28 13:09:02', '2024-10-10 15:47:05'),
(2, 'Mike Aguero Jr', 'mike@app.fms', '0712671267', '20th, Malawi sub county', '2024-09-28 13:14:52', '2024-10-16 09:59:32'),
(3, 'Lamar Test', 'exampl@info.com', '0736348911', '23 Street, Nairobi', '2024-09-28 13:22:31', '2024-09-29 05:28:03'),
(4, 'Limuru Loresho', 'loresho@mail.com', '0789256714', '20 Street, Juja', '2024-09-28 13:29:59', '2024-10-10 15:47:42'),
(5, 'Jayden Uhuru ', 'jayden@mail.fms', '0736348911', '100 Kijabe, Nairobi', '2024-09-28 15:10:30', '2024-09-28 15:15:50'),
(6, 'Super Metro', 'super@app.com', '07911782123', '51 Street, Waiyaki', '2024-09-29 05:25:58', '2024-09-29 05:25:58'),
(7, 'Griffin Kioko', 'kioko@app.com', '0789125826', '87 Street, Kinoo', '2024-09-29 06:24:15', '2024-09-29 06:24:15'),
(8, 'Juma Ali Noor', 'noor@app.com', '0736348911', '20 Street, Katani', '2024-09-29 11:46:20', '2024-10-10 15:24:46'),
(9, 'Constance Minoo', 'minoo@app.com', '254789452731', '14 Street, Kalawa', '2024-10-05 17:02:09', '2024-10-10 15:48:48'),
(10, 'Gage Kamau', 'kamaa@app.com', '254756127812', '20 Street, Limuru', '2024-10-10 15:52:51', '2024-10-10 15:52:51');

-- --------------------------------------------------------

--
-- Table structure for table `client_interactions`
--

CREATE TABLE `client_interactions` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `interaction_type` varchar(100) DEFAULT NULL,
  `interaction_date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
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
-- Table structure for table `deceased`
--

CREATE TABLE `deceased` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `deceased_name` varchar(100) NOT NULL,
  `death_date` date NOT NULL,
  `cause_of_death` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(5, 'Meeting Room A', 'meeting_room', 15, '2024-10-16 14:48:43');

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
(4, 11, 137281, 'cash', 6000.00, 150.00, 0.00, '2024-09-30', '2024-09-30 11:19:28', '2024-09-30 11:19:28'),
(5, 13, 137282, 'mpesa', 17000.00, 1000.00, 500.00, '2024-09-30', '2024-09-30 11:21:21', '2024-09-30 11:21:21'),
(6, 14, 137283, 'cash', 3000.00, 0.00, 0.00, '2024-09-30', '2024-09-30 12:46:17', '2024-09-30 12:46:17'),
(7, 15, 137284, 'mpesa', 21000.00, 0.00, 0.00, '2024-09-30', '2024-09-30 12:49:41', '2024-09-30 12:49:41'),
(8, 16, 137285, 'cash', 50000.00, 0.00, 0.00, '2024-10-01', '2024-10-01 17:04:36', '2024-10-01 17:04:36'),
(9, 17, 137286, 'cash', 17800.00, 0.00, 0.00, '2024-10-05', '2024-10-05 17:03:16', '2024-10-05 17:03:16');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` int(11) NOT NULL,
  `client_id` varchar(100) NOT NULL,
  `service_type` enum('burial','cremation','other') DEFAULT 'burial',
  `schedule_date` date NOT NULL,
  `vehicle_type` varchar(150) DEFAULT NULL,
  `request` varchar(255) DEFAULT NULL,
  `status` enum('scheduled','completed') DEFAULT 'scheduled',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `client_id`, `service_type`, `schedule_date`, `vehicle_type`, `request`, `status`, `created_at`, `updated_at`) VALUES
(11, '6', 'burial', '2024-10-04', 'van', 'Family requests photographer and Boda boda escort ', 'completed', '2024-09-30 08:43:11', '2024-10-16 09:31:53'),
(12, '7', 'cremation', '2024-10-05', 'bus', 'The family requests the following:-\r\nDancers, catering team and boda boda escorts.\r\n Photographer and a DJ\r\n', 'completed', '2024-09-30 08:44:42', '2024-10-16 09:42:56'),
(14, '5', 'burial', '2024-10-16', 'van', 'The family requests a photographer and additional security personnel for a two-day body viewing.', 'completed', '2024-09-30 08:55:35', '2024-10-16 09:37:59'),
(15, '8', 'cremation', '2024-10-24', 'bus', 'Boda boda escort and catering team.', 'scheduled', '2024-09-30 12:49:07', '2024-10-16 09:43:14'),
(18, '9', 'cremation', '2024-10-24', 'bus', 'Request mourners for hire, and boda boda escort riders.', 'scheduled', '2024-10-10 15:50:38', '2024-10-10 15:50:38'),
(19, '10', 'burial', '2024-11-02', 'bus', 'The client requests 1 additional driver, a media team including a DJ and mourners for hire, and a Bodaboda riders escort. ', 'scheduled', '2024-10-16 09:45:58', '2024-10-16 09:45:58');

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
(5, 'Kajanah', 'Malwa Jr', 'user', 'super@app.com', '$2y$10$7ty6LtBpkAMXr9.KAhvVIO7odxoeRkg0G7580Jbq5xciNUy8No3Py', 'staff', '2024-10-10 13:43:18', '2024-10-17 00:56:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_interactions`
--
ALTER TABLE `client_interactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `company_info`
--
ALTER TABLE `company_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deceased`
--
ALTER TABLE `deceased`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`);

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
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`);

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
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `client_interactions`
--
ALTER TABLE `client_interactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `company_info`
--
ALTER TABLE `company_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `deceased`
--
ALTER TABLE `deceased`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `logistics`
--
ALTER TABLE `logistics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
