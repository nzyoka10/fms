-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 24, 2024 at 08:53 AM
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
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
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
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `client_id`, `service_type`, `schedule_date`, `vehicle_type`, `request`, `status`, `created_at`, `updated_at`) VALUES
(11, '6', 'burial', '2024-10-04', 'van', 'Family requests photographer and Boda boda escort ', 'completed', '2024-09-30 08:43:11', '2024-10-16 09:31:53'),
(12, '7', 'cremation', '2024-10-05', 'bus', 'The family requests the following:-\r\nDancers, catering team and boda boda escorts.\r\n Photographer and a DJ\r\n', 'completed', '2024-09-30 08:44:42', '2024-10-16 09:42:56'),
(14, '5', 'burial', '2024-10-16', 'van', 'The family requests a photographer and additional security personnel for a two-day body viewing.', 'completed', '2024-09-30 08:55:35', '2024-10-16 09:37:59'),
(15, '8', 'cremation', '2024-10-24', 'bus', 'Boda boda escort and catering team.', 'scheduled', '2024-09-30 12:49:07', '2024-10-16 09:43:14'),
(18, '9', 'cremation', '2024-10-24', 'bus', 'Request mourners for hire, and boda boda escort riders.', 'scheduled', '2024-10-10 15:50:38', '2024-10-10 15:50:38'),
(19, '10', 'burial', '2024-11-02', 'bus', 'The client requests 1 additional driver, a media team including a DJ and mourners for hire, and a Bodaboda riders escort. ', 'scheduled', '2024-10-16 09:45:58', '2024-10-16 09:45:58'),
(20, '4', 'burial', '2024-10-26', 'bus', 'Bodaboda escort', 'scheduled', '2024-10-17 05:09:08', '2024-10-17 05:09:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
